<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\RegistroMedico;
use App\Models\Animal;
use Carbon\Carbon;

class SaludController extends Controller
{
    public function index(Request $request)
    {
        // 1. Filtros y Buscador
        $query = RegistroMedico::with(['animal', 'user']);

        if ($request->has('categoria') && $request->categoria != '') {
            $query->where('categoria', $request->categoria);
        }

        if ($request->has('estado') && $request->estado != '') {
            $query->where('estado', $request->estado);
        }

        if ($request->has('buscar') && $request->buscar != '') {
            $termino = $request->buscar;
            $query->where(function ($q) use ($termino) {
                $q->whereHas('animal', function ($subQ) use ($termino) {
                    $subQ->where('arete', 'LIKE', "%{$termino}%")
                        ->orWhere('nombre', 'LIKE', "%{$termino}%");
                })->orWhere('lote_nombre', 'LIKE', "%{$termino}%");
            });
        }

        $registros = $query->latest('fecha')->get();
        $animales = Animal::all(); // Para el modal

        // 2. Matemáticas para las Tarjetas
        $hoy = Carbon::today();

        $enfermos = RegistroMedico::where('categoria', 'enfermedad')->where('estado', 'activo')->count();

        $vacunasProximas = RegistroMedico::where('categoria', 'vacuna')
            ->where('estado', 'programado')
            ->whereBetween('fecha', [$hoy, $hoy->copy()->addDays(7)])
            ->count();

        $gastoMensual = RegistroMedico::whereMonth('fecha', date('m'))
            ->whereYear('fecha', date('Y'))
            ->sum('costo');

        return view('salud.index', compact('registros', 'animales', 'enfermos', 'vacunasProximas', 'gastoMensual'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'fecha' => 'required|date',
            'categoria' => 'required|in:enfermedad,vacuna,revision',
            'diagnostico_tratamiento' => 'required|string|max:255',
            'estado' => 'required|in:activo,programado,completado',
        ]);

        RegistroMedico::create([
            'animal_id' => $request->animal_id, // Puede ser null
            'lote_nombre' => $request->lote_nombre, // Puede ser null
            'user_id' => auth()->id(),
            'fecha' => $request->fecha,
            'categoria' => $request->categoria,
            'diagnostico_tratamiento' => $request->diagnostico_tratamiento,
            'estado' => $request->estado,
            'veterinario' => $request->veterinario,
            'costo' => $request->costo ?? 0,
        ]);

        return redirect()->back()->with('exito', 'Registro médico guardado.');
    }

    public function destroy(RegistroMedico $salud)
    {
        $salud->delete();
        return redirect()->route('salud.index')->with('exito', 'Registro eliminado.');
    }
    // Actualizar el registro (Editar)
    public function update(Request $request, RegistroMedico $salud)
    {
        $request->validate([
            'fecha' => 'required|date',
            'categoria' => 'required|in:enfermedad,vacuna,revision',
            'diagnostico_tratamiento' => 'required|string|max:255',
            'estado' => 'required|in:activo,programado,completado',
        ]);

        // Actualizamos los datos (No dejamos cambiar la vaca/lote para evitar cruce de historiales)
        $salud->update([
            'fecha' => $request->fecha,
            'categoria' => $request->categoria,
            'diagnostico_tratamiento' => $request->diagnostico_tratamiento,
            'estado' => $request->estado,
            'veterinario' => $request->veterinario,
            'costo' => $request->costo ?? 0,
            'user_id' => auth()->id(), // Registramos quién hizo la última edición
        ]);

        return redirect()->route('salud.index')->with('exito', 'Registro médico actualizado.');
    }
}
