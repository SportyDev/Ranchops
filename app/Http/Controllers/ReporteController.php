<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Reporte;
use App\Models\Animal;
use App\Models\Produccion;
use App\Models\RegistroMedico;
use Barryvdh\DomPDF\Facade\Pdf;

class ReporteController extends Controller
{
    // Mostrar la vista con el historial
    public function index(Request $request)
    {
        $query = Reporte::with('user');

        if ($request->filled('buscar')) {
            $query->where('nombre_documento', 'LIKE', '%' . $request->buscar . '%');
        }

        $historial = $query->latest()->get();
        return view('reportes.index', compact('historial'));
    }

    // Función a para generar y descargar Excel (CSV)
    public function exportarCsv($categoria)
    {
        $fecha = date('Y-m-d_His');
        $fileName = "Reporte_{$categoria}_{$fecha}.csv";

        // Registrar en el historial
        Reporte::create([
            'nombre_documento' => $fileName,
            'categoria' => ucfirst($categoria),
            'formato' => 'CSV',
            'user_id' => auth()->id()
        ]);

        $headers = [
            "Content-type"        => "text/csv; charset=UTF-8",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        $callback = function () use ($categoria) {
            $file = fopen('php://output', 'w');
            fprintf($file, chr(0xEF) . chr(0xBB) . chr(0xBF)); // Soporte para acentos (UTF-8 BOM)

            if ($categoria == 'inventario') {
                fputcsv($file, ['Arete', 'Nombre', 'Raza', 'Estado', 'Fecha Registro']);
                $datos = Animal::all();
                foreach ($datos as $row) {
                    fputcsv($file, [$row->arete, $row->nombre, $row->raza, $row->estado, $row->created_at->format('Y-m-d')]);
                }
            } elseif ($categoria == 'produccion') {
                fputcsv($file, ['Fecha', 'Turno', 'Vaca (Arete)', 'Litros', 'Vaquero']);
                $datos = Produccion::with(['animal', 'user'])->get();
                foreach ($datos as $row) {
                    fputcsv($file, [\Carbon\Carbon::parse($row->fecha_registro)->format('Y-m-d'), ucfirst($row->turno), $row->animal->arete ?? 'N/A', $row->litros, $row->user->name]);
                }
            } elseif ($categoria == 'salud') {
                fputcsv($file, ['Fecha', 'Categoría', 'Animal/Lote', 'Diagnóstico', 'Estado', 'Costo', 'Veterinario']);
                $datos = RegistroMedico::with('animal')->get();
                foreach ($datos as $row) {
                    $sujeto = $row->animal ? $row->animal->arete : $row->lote_nombre;
                    fputcsv($file, [$row->fecha, ucfirst($row->categoria), $sujeto, $row->diagnostico_tratamiento, ucfirst($row->estado), $row->costo, $row->veterinario]);
                }
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
    //Generar y descargar PDF
    public function exportarPdf($categoria)
    {
        $fecha = date('Y-m-d_His');
        $fileName = "Reporte_{$categoria}_{$fecha}.pdf";

        // Registrar en el historial
        Reporte::create([
            'nombre_documento' => $fileName,
            'categoria' => ucfirst($categoria),
            'formato' => 'PDF',
            'user_id' => auth()->id()
        ]);

        $columnas = [];
        $filas = [];

        // Preparamos los datos según la categoría
        if ($categoria == 'inventario') {
            $columnas = ['Arete', 'Nombre', 'Raza', 'Estado', 'Fecha Ingreso'];
            $datos = Animal::all();
            foreach ($datos as $row) {
                $filas[] = [$row->arete, $row->nombre ?? '-', $row->raza, $row->estado, $row->created_at->format('d/m/Y')];
            }
        } elseif ($categoria == 'produccion') {
            $columnas = ['Fecha', 'Turno', 'Vaca (Arete)', 'Litros', 'Registrado por'];
            $datos = Produccion::with(['animal', 'user'])->get();
            foreach ($datos as $row) {
                $filas[] = [\Carbon\Carbon::parse($row->fecha_registro)->format('d/m/Y'), ucfirst($row->turno), $row->animal->arete ?? 'N/A', $row->litros . ' L', $row->user->name];
            }
        } elseif ($categoria == 'salud') {
            $columnas = ['Fecha', 'Categoría', 'Animal/Lote', 'Diagnóstico', 'Estado', 'Costo'];
            $datos = RegistroMedico::with('animal')->get();
            foreach ($datos as $row) {
                $sujeto = $row->animal ? $row->animal->arete : $row->lote_nombre;
                $filas[] = [\Carbon\Carbon::parse($row->fecha)->format('d/m/Y'), ucfirst($row->categoria), $sujeto, $row->diagnostico_tratamiento, ucfirst($row->estado), '$' . number_format($row->costo, 2)];
            }
        }

        // Enviamos los datos al "molde" que creamos
        $pdf = Pdf::loadView('reportes.pdf', compact('categoria', 'columnas', 'filas'));

        // Formato carta, orientación vertical
        $pdf->setPaper('letter', 'portrait');

        return $pdf->download($fileName);
    }
    // 1. FUNCIÓN PRINCIPAL DEL CONSTRUCTOR DE REPORTES
    public function personalizado(Request $request)
    {
        $modulo = $request->modulo;

        // Configuramos la tabla base y el campo de fecha correcto según el módulo
        if ($modulo == 'inventario') {
            $query = Animal::query();
            $campoFecha = 'created_at';
        } elseif ($modulo == 'produccion') {
            $query = Produccion::with(['animal', 'user']);
            $campoFecha = 'fecha_registro';
        } elseif ($modulo == 'salud') {
            $query = RegistroMedico::with(['animal', 'user']);
            $campoFecha = 'fecha';
        } elseif ($modulo == 'reproduccion') {
            $query = Reproduccion::with(['animal', 'user']);
            $campoFecha = 'fecha_servicio';
        } else {
            return redirect()->back(); // Por si hay algún error
        }

        // Filtro 1: Fechas (Desde - Hasta)
        if ($request->fecha_inicio) {
            $query->whereDate($campoFecha, '>=', $request->fecha_inicio);
        }
        if ($request->fecha_fin) {
            $query->whereDate($campoFecha, '<=', $request->fecha_fin);
        }

        // Filtro 2: Arete (Filtro avanzado opcional)
        if ($request->animal_id) {
            $termino = $request->animal_id;
            if ($modulo == 'inventario') {
                $query->where('arete', 'LIKE', "%{$termino}%");
            } else {
                $query->whereHas('animal', function ($q) use ($termino) {
                    $q->where('arete', 'LIKE', "%{$termino}%");
                });
            }
        }

        // Ejecutamos la consulta con los filtros seleccionados
        $resultados = $query->get();

        // Mandamos los datos a la función generadora
        return $this->generarArchivoPersonalizado($resultados, $modulo, $request->formato);
    }

    // 2. Genera el PDF o Excel
    private function generarArchivoPersonalizado($datos, $categoria, $formato)
    {
        $fecha = date('Y-m-d_His');
        $fileName = "Reporte_Especial_{$categoria}_{$fecha}";

        // 1. Registrar en el Archivo Histórico
        Reporte::create([
            'nombre_documento' => $fileName . ($formato == 'pdf' ? '.pdf' : '.csv'),
            'categoria' => ucfirst($categoria) . ' (Personalizado)',
            'formato' => strtoupper($formato),
            'user_id' => auth()->id()
        ]);

        // 2. Preparar los datos limpios para la tabla
        $columnas = [];
        $filas = [];

        if ($categoria == 'inventario') {
            $columnas = ['Arete', 'Nombre', 'Raza', 'Estado', 'Fecha Ingreso'];
            foreach ($datos as $row) {
                $filas[] = [$row->arete, $row->nombre ?? '-', $row->raza, $row->estado, $row->created_at->format('d/m/Y')];
            }
        } elseif ($categoria == 'produccion') {
            $columnas = ['Fecha', 'Turno', 'Vaca (Arete)', 'Litros', 'Registrado por'];
            foreach ($datos as $row) {
                $filas[] = [\Carbon\Carbon::parse($row->fecha_registro)->format('d/m/Y'), ucfirst($row->turno), $row->animal->arete ?? 'N/A', $row->litros . ' L', $row->user->name ?? '-'];
            }
        } elseif ($categoria == 'salud') {
            $columnas = ['Fecha', 'Categoría', 'Animal/Lote', 'Diagnóstico', 'Estado', 'Costo'];
            foreach ($datos as $row) {
                $sujeto = $row->animal ? $row->animal->arete : $row->lote_nombre;
                $filas[] = [\Carbon\Carbon::parse($row->fecha)->format('d/m/Y'), ucfirst($row->categoria), $sujeto, $row->diagnostico_tratamiento, ucfirst($row->estado), '$' . number_format($row->costo, 2)];
            }
        } elseif ($categoria == 'reproduccion') {
            $columnas = ['Fecha Servicio', 'Vaca (Arete)', 'Método', 'Toro/Semen', 'Estado', 'Parto Estimado'];
            foreach ($datos as $row) {
                $parto = $row->fecha_parto_estimada ? \Carbon\Carbon::parse($row->fecha_parto_estimada)->format('d/m/Y') : 'N/A';
                $filas[] = [\Carbon\Carbon::parse($row->fecha_servicio)->format('d/m/Y'), $row->animal->arete ?? 'N/A', $row->metodo, $row->toro_semen ?? '-', ucfirst($row->estado), $parto];
            }
        }

        // 3. Exportar según el formato elegido
        if ($formato == 'pdf') {
            $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('reportes.pdf', compact('categoria', 'columnas', 'filas'));
            $pdf->setPaper('letter', 'portrait');
            return $pdf->download($fileName . '.pdf');
        }

        // Si es Excel (CSV)
        $headers = [
            "Content-type"        => "text/csv; charset=UTF-8",
            "Content-Disposition" => "attachment; filename={$fileName}.csv",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        $callback = function () use ($columnas, $filas) {
            $file = fopen('php://output', 'w');
            fprintf($file, chr(0xEF) . chr(0xBB) . chr(0xBF));
            fputcsv($file, $columnas);
            foreach ($filas as $fila) {
                fputcsv($file, $fila);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
