<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Produccion;
use App\Models\Animal;
use Carbon\Carbon;

class ProduccionController extends Controller
{
    public function index(Request $request)
    {
        // 1. EL BUSCADOR Y LOS FILTROS
        $query = Produccion::with(['animal', 'user']);

        // Filtro por Fecha
        if ($request->has('fecha_inicio') && $request->fecha_inicio != '') {
            $query->whereDate('fecha_registro', '>=', $request->fecha_inicio);
        }
        if ($request->has('fecha_fin') && $request->fecha_fin != '') {
            $query->whereDate('fecha_registro', '<=', $request->fecha_fin);
        }

        // Filtro por Turno
        if ($request->has('turno') && $request->turno != '') {
            $query->where('turno', $request->turno);
        }

        // Buscador por Arete o Nombre (Buscamos en la tabla relacionada 'animals')
        if ($request->has('buscar') && $request->buscar != '') {
            $termino = $request->buscar;
            $query->whereHas('animal', function ($q) use ($termino) {
                $q->where('arete', 'LIKE', '%' . $termino . '%')
                    ->orWhere('nombre', 'LIKE', '%' . $termino . '%');
            });
        }

        $producciones = $query->latest('fecha_registro')->get();
        $animales = Animal::all(); // Para los modales

        // 2. MATEMÁTICAS PARA LAS TARJETAS (Dashboard)
        $hoy = Carbon::today();

        // Suma de litros de hoy
        $produccionHoy = Produccion::whereDate('fecha_registro', $hoy)->sum('litros');

        // Promedio general histórico
        $promedioVaca = Produccion::avg('litros') ?? 0;

        // Vaca Estrella del Mes (La que más litros sumó este mes)
        $vacaEstrellaInfo = Produccion::with('animal')
            ->selectRaw('animal_id, sum(litros) as total_litros')
            ->whereMonth('fecha_registro', date('m'))
            ->whereYear('fecha_registro', date('Y'))
            ->groupBy('animal_id')
            ->orderByDesc('total_litros')
            ->first();

        return view('produccion.index', compact('producciones', 'animales', 'produccionHoy', 'promedioVaca', 'vacaEstrellaInfo'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'animal_id' => 'required|exists:animals,id',
            'litros' => 'required|numeric|min:0.1',
            'turno' => 'required|in:manana,tarde',
            'fecha_registro' => 'required|date',
        ]);

        Produccion::create([
            'animal_id' => $request->animal_id,
            'user_id' => auth()->id(),
            'litros' => $request->litros,
            'turno' => $request->turno,
            'fecha_registro' => Carbon::parse($request->fecha_registro)->format('Y-m-d H:i:s'),
        ]);

        return redirect()->back()->with('exito', 'Registro guardado.');
    }

    // actualizar el registro editado
    public function update(Request $request, Produccion $produccion)
    {
        $request->validate([
            'litros' => 'required|numeric|min:0.1',
            'turno' => 'required|in:manana,tarde',
            'fecha_registro' => 'required|date',
        ]);

        // Actualizamos los datos 
        $produccion->update([
            'litros' => $request->litros,
            'turno' => $request->turno,
            'fecha_registro' => Carbon::parse($request->fecha_registro)->format('Y-m-d H:i:s'),
            'user_id' => auth()->id(), // Guardamos quién fue el último en editarlo
        ]);

        return redirect()->route('produccion.index')->with('exito', 'Registro actualizado correctamente.');
    }
    //Eliminar registro de producción
    public function destroy(Produccion $produccion)
    {
        $produccion->delete();
        return redirect()->route('produccion.index')->with('exito', 'Registro de ordeña eliminado correctamente.');
    }
}
