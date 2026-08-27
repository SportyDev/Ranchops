<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Reproduccion;
use App\Models\Animal;
use Carbon\Carbon;

class ReproduccionController extends Controller
{
    public function index(Request $request)
    {
        // 1. Filtros y Buscador
        $query = Reproduccion::with(['animal', 'user']);

        if ($request->has('estado') && $request->estado != '') {
            $query->where('estado', $request->estado);
        }

        if ($request->has('buscar') && $request->buscar != '') {
            $termino = $request->buscar;
            $query->whereHas('animal', function ($q) use ($termino) {
                $q->where('arete', 'LIKE', '%' . $termino . '%')
                    ->orWhere('nombre', 'LIKE', '%' . $termino . '%');
            });
        }

        $registros = $query->latest('fecha_servicio')->get();
        $animales = Animal::all();

        // 2. Matemáticas para las Tarjetas Superiores
        $hoy = Carbon::today();
        $dentroDe30Dias = Carbon::today()->addDays(30);

        // Partos próximos (Gestantes con fecha de parto en los próximos 30 días o menos)
        $proximosPartos = Reproduccion::where('estado', 'gestante')
            ->whereBetween('fecha_parto_estimada', [$hoy->copy()->subDays(10), $dentroDe30Dias])
            ->count();

        // Total de gestantes confirmadas
        $totalGestantes = Reproduccion::where('estado', 'gestante')->count();

        // En observación (esperando diagnóstico)
        $enObservacion = Reproduccion::where('estado', 'observacion')->count();

        return view('reproduccion.index', compact('registros', 'animales', 'proximosPartos', 'totalGestantes', 'enObservacion'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'animal_id' => 'required|exists:animals,id',
            'fecha_servicio' => 'required|date',
            'metodo' => 'required|string',
            'toro_semen' => 'nullable|string',
            'estado' => 'required|in:gestante,observacion,vacia',
        ]);

        // Si entra como 'gestante', le sumamos 283 días al parto
        $fechaParto = null;
        if ($request->estado == 'gestante') {
            $fechaParto = Carbon::parse($request->fecha_servicio)->addDays(283)->format('Y-m-d');
        }

        Reproduccion::create([
            'animal_id' => $request->animal_id,
            'user_id' => auth()->id(),
            'fecha_servicio' => $request->fecha_servicio,
            'metodo' => $request->metodo,
            'toro_semen' => $request->toro_semen,
            'estado' => $request->estado,
            'fecha_parto_estimada' => $fechaParto,
        ]);


        return redirect()->back()->with('exito', 'Servicio registrado correctamente.');
    }
    //Actualizar el registro (Editar)
    public function update(Request $request, Reproduccion $reproduccion)
    {
        $request->validate([
            'fecha_servicio' => 'required|date',
            'metodo' => 'required|string',
            'toro_semen' => 'nullable|string',
            'estado' => 'required|in:gestante,observacion,vacia',
        ]);

        // Volvemos a calcular la fecha si cambió a gestante
        $fechaParto = null;
        if ($request->estado == 'gestante') {
            $fechaParto = Carbon::parse($request->fecha_servicio)->addDays(283)->format('Y-m-d');
        }

        $reproduccion->update([
            'fecha_servicio' => $request->fecha_servicio,
            'metodo' => $request->metodo,
            'toro_semen' => $request->toro_semen,
            'estado' => $request->estado,
            'fecha_parto_estimada' => $fechaParto,
            'user_id' => auth()->id(), // Registramos quién hizo la última edición
        ]);

        return redirect()->route('reproduccion.index')->with('exito', 'Servicio actualizado correctamente.');
    }

    //Eliminar registro
    public function destroy(Reproduccion $reproduccion)
    {
        $reproduccion->delete();
        return redirect()->route('reproduccion.index')->with('exito', 'Registro eliminado.');
    }
}
