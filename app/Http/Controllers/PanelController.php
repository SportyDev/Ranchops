<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Animal;
use App\Models\Produccion;
use Carbon\Carbon;

class PanelController extends Controller
{
    public function index()
    {
        // 1. Tarjetas de Resumen Principales
        $totalGanado = Animal::count();
        $enOrdena = Animal::where('estado', 'lactante')->count();

        $enTratamiento = 0;
        $proximosPartos = 0;

        // 2. Producción del Mes Actual
        $mesActual = Carbon::now()->month;
        $anioActual = Carbon::now()->year;
        $produccionMensual = Produccion::whereMonth('fecha_registro', $mesActual)
            ->whereYear('fecha_registro', $anioActual)
            ->sum('litros');

        // 3. Composición del Rebaño (Agrupación)
        $secas = Animal::where('estado', 'seca')->count();
        $becerros = Animal::whereIn('estado', ['becerro', 'novilla'])->count();
        $toros = Animal::where('estado', 'toro')->count();

        // 4. Matemáticas para los Porcentajes de la Gráfica
        $porcOrdena = $totalGanado > 0 ? round(($enOrdena / $totalGanado) * 100) : 0;
        $porcSecas = $totalGanado > 0 ? round(($secas / $totalGanado) * 100) : 0;
        $porcBecerros = $totalGanado > 0 ? round(($becerros / $totalGanado) * 100) : 0;
        $porcToros = $totalGanado > 0 ? round(($toros / $totalGanado) * 100) : 0;

        return view('panel.index', compact(
            'totalGanado',
            'enOrdena',
            'enTratamiento',
            'proximosPartos',
            'produccionMensual',
            'secas',
            'becerros',
            'toros',
            'porcOrdena',
            'porcSecas',
            'porcBecerros',
            'porcToros'
        ));
    }
}
