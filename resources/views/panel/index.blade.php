@extends('layouts.admin')

@section('titulo', 'Inicio')
@section('titulo_pagina', 'Resumen General')
@section('subtitulo_pagina', 'Panel de control de la Unidad Ganadera')

@section('contenido')
<div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-4">
    <div class="group relative overflow-hidden rounded-xl bg-surface-light p-6 shadow-sm ring-1 ring-slate-200 transition-all hover:shadow-md hover:ring-primary/20">
        <div class="flex items-center justify-between">
            <p class="text-sm font-medium text-slate-500">Total de Ganado</p>
            <span class="material-symbols-outlined text-slate-400 group-hover:text-primary transition-colors">view_list</span>
        </div>
        <div class="mt-4 flex items-baseline gap-2">
            <span class="text-3xl font-bold text-slate-900">{{ $totalGanado }}</span>
            <span class="rounded-full bg-emerald-50 px-2 py-0.5 text-xs font-medium text-emerald-600 flex items-center">
                Cabezas
            </span>
        </div>
    </div>
    
    <div class="group relative overflow-hidden rounded-xl bg-surface-light p-6 shadow-sm ring-1 ring-slate-200 transition-all hover:shadow-md hover:ring-primary/20">
        <div class="flex items-center justify-between">
            <p class="text-sm font-medium text-slate-500">En Ordeña</p>
            <span class="material-symbols-outlined text-slate-400 group-hover:text-primary transition-colors">water_drop</span>
        </div>
        <div class="mt-4 flex items-baseline gap-2">
            <span class="text-3xl font-bold text-slate-900">{{ $enOrdena }}</span>
            <span class="rounded-full bg-emerald-50 px-2 py-0.5 text-xs font-medium text-emerald-600 flex items-center">
                <span class="material-symbols-outlined text-[14px] mr-0.5">trending_up</span> {{ $porcOrdena }}%
            </span>
        </div>
    </div>
    
    <div class="group relative overflow-hidden rounded-xl bg-surface-light p-6 shadow-sm ring-1 ring-slate-200 transition-all hover:shadow-md hover:ring-red-500/20">
        <div class="flex items-center justify-between">
            <p class="text-sm font-medium text-slate-500">En Tratamiento</p>
            <span class="material-symbols-outlined text-red-400 group-hover:text-red-500 transition-colors">medical_services</span>
        </div>
        <div class="mt-4 flex items-baseline gap-2">
            <span class="text-3xl font-bold text-slate-900">{{ $enTratamiento }}</span>
            <span class="rounded-full bg-red-50 px-2 py-0.5 text-xs font-medium text-red-600 flex items-center">
                <span class="material-symbols-outlined text-[14px] mr-0.5">warning</span> Alerta
            </span>
        </div>
    </div>
    
    <div class="group relative overflow-hidden rounded-xl bg-surface-light p-6 shadow-sm ring-1 ring-slate-200 transition-all hover:shadow-md hover:ring-primary/20">
        <div class="flex items-center justify-between">
            <p class="text-sm font-medium text-slate-500">Próximos Partos</p>
            <span class="material-symbols-outlined text-slate-400 group-hover:text-primary transition-colors">event_repeat</span>
        </div>
        <div class="mt-4 flex items-baseline gap-2">
            <span class="text-3xl font-bold text-slate-900">{{ $proximosPartos }}</span>
            <span class="rounded-full bg-amber-50 px-2 py-0.5 text-xs font-medium text-amber-600 flex items-center">
                <span class="material-symbols-outlined text-[14px] mr-0.5">calendar_month</span> Este mes
            </span>
        </div>
    </div>
</div>

<div class="mt-8 grid grid-cols-1 gap-6 lg:grid-cols-3">
    <div class="rounded-xl bg-surface-light p-6 shadow-sm ring-1 ring-slate-200 lg:col-span-2">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h3 class="text-lg font-bold text-slate-900">Producción de Leche (Este Mes)</h3>
                <p class="text-sm text-slate-500 font-medium">Producción total en Litros</p>
            </div>
            <div class="flex items-center gap-2 rounded-lg bg-slate-50 p-1">
                <button class="rounded-md bg-white px-3 py-1 text-xs font-bold text-slate-900 shadow-sm">{{ date('Y') }}</button>
            </div>
        </div>
        <div class="flex items-baseline gap-4 mb-6">
            <h4 class="text-4xl font-bold text-slate-900">{{ number_format($produccionMensual, 1) }} L</h4>
        </div>
        
        <div class="relative h-64 w-full">
            <svg class="h-full w-full overflow-visible" preserveAspectRatio="none" viewBox="0 0 800 200">
                <line stroke="#f1f5f9" stroke-width="1" x1="0" x2="800" y1="0" y2="0"></line>
                <line stroke="#f1f5f9" stroke-width="1" x1="0" x2="800" y1="50" y2="50"></line>
                <line stroke="#f1f5f9" stroke-width="1" x1="0" x2="800" y1="100" y2="100"></line>
                <line stroke="#f1f5f9" stroke-width="1" x1="0" x2="800" y1="150" y2="150"></line>
                <line stroke="#f1f5f9" stroke-width="1" x1="0" x2="800" y1="200" y2="200"></line>
                <path d="M0,150 C50,140 100,160 150,120 C200,80 250,90 300,70 C350,50 400,60 450,40 C500,20 550,30 600,20 C650,10 700,25 750,15 L800,10 L800,200 L0,200 Z" fill="url(#gradient)" opacity="0.2"></path>
                <path d="M0,150 C50,140 100,160 150,120 C200,80 250,90 300,70 C350,50 400,60 450,40 C500,20 550,30 600,20 C650,10 700,25 750,15 L800,10" fill="none" stroke="#11d432" stroke-linecap="round" stroke-width="3"></path>
                <circle cx="150" cy="120" fill="#fff" r="4" stroke="#11d432" stroke-width="2"></circle>
                <circle cx="300" cy="70" fill="#fff" r="4" stroke="#11d432" stroke-width="2"></circle>
                <circle cx="450" cy="40" fill="#fff" r="4" stroke="#11d432" stroke-width="2"></circle>
                <circle cx="600" cy="20" fill="#fff" r="4" stroke="#11d432" stroke-width="2"></circle>
                <circle cx="750" cy="15" fill="#fff" r="4" stroke="#11d432" stroke-width="2"></circle>
                <defs>
                    <linearGradient id="gradient" x1="0%" x2="0%" y1="0%" y2="100%">
                        <stop offset="0%" stop-color="#11d432"></stop>
                        <stop offset="100%" stop-color="#ffffff" stop-opacity="0"></stop>
                    </linearGradient>
                </defs>
            </svg>
            <div class="mt-4 flex justify-between text-xs font-medium text-slate-400 px-2">
                <span>Ene</span><span>Feb</span><span>Mar</span><span>Abr</span><span>May</span><span>Jun</span><span>Jul</span><span>Ago</span><span>Sep</span><span>Oct</span><span>Nov</span><span>Dic</span>
            </div>
        </div>
    </div>

    <div class="flex flex-col rounded-xl bg-surface-light p-6 shadow-sm ring-1 ring-slate-200">
        <h3 class="text-lg font-bold text-slate-900 mb-6">Composición del Rebaño</h3>
        <div class="flex flex-1 flex-col items-center justify-center gap-6">
            <div class="relative flex h-48 w-48 items-center justify-center">
                @php
                    $corte1 = $porcOrdena;
                    $corte2 = $corte1 + $porcSecas;
                    $corte3 = $corte2 + $porcBecerros;
                @endphp
                <div class="absolute inset-0 rounded-full" style="background: conic-gradient(#5E1B22 0% {{ $corte1 }}%, #fbbf24 {{ $corte1 }}% {{ $corte2 }}%, #60a5fa {{ $corte2 }}% {{ $corte3 }}%, #94a3b8 {{ $corte3 }}% 100%);"></div>
                <div class="absolute inset-3 rounded-full bg-surface-light flex flex-col items-center justify-center shadow-inner">
                    <span class="text-3xl font-bold text-slate-900">{{ $totalGanado }}</span>
                    <span class="text-[10px] text-slate-500 font-bold uppercase tracking-tighter">Cabezas Totales</span>
                </div>
            </div>
            
            <div class="grid w-full grid-cols-2 gap-4">
                <div class="flex items-center gap-2">
                    <div class="h-3 w-3 rounded-full bg-primary"></div>
                    <div class="flex flex-col">
                        <span class="text-xs font-bold text-slate-700">En Ordeña</span>
                        <span class="text-[10px] text-slate-500">{{ $enOrdena }} cabezas ({{ $porcOrdena }}%)</span>
                    </div>
                </div>
                <div class="flex items-center gap-2">
                    <div class="h-3 w-3 rounded-full bg-amber-400"></div>
                    <div class="flex flex-col">
                        <span class="text-xs font-bold text-slate-700">Secas</span>
                        <span class="text-[10px] text-slate-500">{{ $secas }} cabezas ({{ $porcSecas }}%)</span>
                    </div>
                </div>
                <div class="flex items-center gap-2">
                    <div class="h-3 w-3 rounded-full bg-blue-400"></div>
                    <div class="flex flex-col">
                        <span class="text-xs font-bold text-slate-700">Becerros</span>
                        <span class="text-[10px] text-slate-500">{{ $becerros }} cabezas ({{ $porcBecerros }}%)</span>
                    </div>
                </div>
                <div class="flex items-center gap-2">
                    <div class="h-3 w-3 rounded-full bg-slate-400"></div>
                    <div class="flex flex-col">
                        <span class="text-xs font-bold text-slate-700">Toros</span>
                        <span class="text-[10px] text-slate-500">{{ $toros }} cabezas ({{ $porcToros }}%)</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="mt-8 rounded-xl bg-surface-light p-6 shadow-sm ring-1 ring-slate-200">
    <div class="flex items-center justify-between mb-4">
        <h3 class="text-lg font-bold text-slate-900">Alertas Recientes</h3>
        <a class="text-sm font-semibold text-primary hover:text-primary-dark transition-colors" href="#">Ver todas</a>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-left text-sm text-slate-600">
            <thead class="border-b border-slate-100 bg-slate-50 text-[11px] uppercase tracking-wider font-bold text-slate-500">
                <tr>
                    <th class="px-4 py-3">Fecha</th>
                    <th class="px-4 py-3">Tipo</th>
                    <th class="px-4 py-3">Descripción</th>
                    <th class="px-4 py-3">Estado</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                <tr>
                    <td colspan="4" class="px-4 py-8 text-center text-slate-500">
                        Aún no hay alertas generadas en el sistema.
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
@endsection