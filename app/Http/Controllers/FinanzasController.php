<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaccion;
use Carbon\Carbon;

class FinanzasController extends Controller
{
    public function index()
    {
        $mesActual = Carbon::now()->month;
        $anioActual = Carbon::now()->year;

        // 1. Cálculos Generales del Mes
        $ingresosTotales = Transaccion::where('tipo', 'ingreso')
                            ->whereMonth('fecha', $mesActual)
                            ->whereYear('fecha', $anioActual)
                            ->sum('monto');

        $gastosTotales = Transaccion::where('tipo', 'gasto')
                            ->whereMonth('fecha', $mesActual)
                            ->whereYear('fecha', $anioActual)
                            ->sum('monto');

        $utilidad = $ingresosTotales - $gastosTotales;

        // 2. Desglose para las mini-estadísticas
        $ingresosLeche = Transaccion::where('categoria', 'Leche')->whereMonth('fecha', $mesActual)->sum('monto');
        $ingresosGanado = Transaccion::where('categoria', 'Venta Ganado')->whereMonth('fecha', $mesActual)->sum('monto');
        
        $gastosAlimento = Transaccion::where('categoria', 'Alimento')->whereMonth('fecha', $mesActual)->sum('monto');
        $gastosVeterinaria = Transaccion::where('categoria', 'Veterinaria')->whereMonth('fecha', $mesActual)->sum('monto');
        $gastosOtros = Transaccion::whereIn('categoria', ['Nómina', 'Mantenimiento', 'Otros'])->whereMonth('fecha', $mesActual)->sum('monto');

        // 3. Historial para la tabla (Últimos 20 movimientos)
        $movimientos = Transaccion::with('user')->orderBy('fecha', 'desc')->take(20)->get();

        return view('finanzas.index', compact(
            'ingresosTotales', 'gastosTotales', 'utilidad',
            'ingresosLeche', 'ingresosGanado',
            'gastosAlimento', 'gastosVeterinaria', 'gastosOtros',
            'movimientos'
        ));
    }

    public function store(Request $request)
    {
        // Guardar un nuevo ingreso o gasto manual
        Transaccion::create([
            'tipo' => $request->tipo,
            'categoria' => $request->categoria,
            'monto' => $request->monto,
            'fecha' => $request->fecha,
            'descripcion' => $request->descripcion,
            'user_id' => auth()->id()
        ]);

        return redirect()->route('finanzas.index');
    }
}