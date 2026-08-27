@extends('layouts.admin')

@section('titulo', 'Producción')
@section('titulo_pagina', 'Producción de Leche')
@section('subtitulo_pagina', 'Auditoría y registro detallado de la ordeña diaria.')

@section('acciones_cabecera')
<button onclick="abrirModalProduccion()"
    class="rounded-lg bg-primary px-4 py-2 text-sm font-medium text-white shadow-lg shadow-primary/30 hover:bg-primary-dark transition-colors flex items-center gap-2">
    <span class="material-symbols-outlined text-[20px]">add</span> Registro Manual
</button>
@endsection

@section('contenido')
<div class="grid grid-cols-1 gap-6 sm:grid-cols-3 mb-8">
    <div
        class="group relative overflow-hidden rounded-xl bg-surface-light p-6 shadow-sm ring-1 ring-slate-200 transition-all hover:shadow-md hover:ring-primary/20">
        <div class="flex items-center justify-between">
            <p class="text-sm font-medium text-slate-500">Producción de Hoy</p>
            <span
                class="material-symbols-outlined text-blue-400 group-hover:text-blue-500 transition-colors">water_drop</span>
        </div>
        <div class="mt-4 flex items-baseline gap-2">
            <span class="text-3xl font-bold text-slate-900">{{ number_format($produccionHoy, 1) }} L</span>
        </div>
    </div>
    <div
        class="group relative overflow-hidden rounded-xl bg-surface-light p-6 shadow-sm ring-1 ring-slate-200 transition-all hover:shadow-md hover:ring-primary/20">
        <div class="flex items-center justify-between">
            <p class="text-sm font-medium text-slate-500">Promedio Histórico</p>
            <span
                class="material-symbols-outlined text-slate-400 group-hover:text-primary transition-colors">scale</span>
        </div>
        <div class="mt-4 flex items-baseline gap-2">
            <span class="text-3xl font-bold text-slate-900">{{ number_format($promedioVaca, 1) }} L</span>
            <span class="text-sm text-slate-500">/ registro</span>
        </div>
    </div>
    <div
        class="group relative overflow-hidden rounded-xl bg-surface-light p-6 shadow-sm ring-1 ring-slate-200 transition-all hover:shadow-md hover:ring-amber-500/20">
        <div class="flex items-center justify-between">
            <p class="text-sm font-medium text-slate-500">Vaca Estrella (Mes)</p>
            <span
                class="material-symbols-outlined text-amber-400 group-hover:text-amber-500 transition-colors">star</span>
        </div>
        <div class="mt-4 flex items-baseline gap-2">
            @if($vacaEstrellaInfo)
            <span class="text-3xl font-bold text-slate-900 truncate max-w-[120px]"
                title="{{ $vacaEstrellaInfo->animal->nombre }}">{{ $vacaEstrellaInfo->animal->nombre ?? $vacaEstrellaInfo->animal->arete }}</span>
            <span class="text-sm text-slate-500">({{ number_format($vacaEstrellaInfo->total_litros, 1) }}L
                sumados)</span>
            @else
            <span class="text-xl font-bold text-slate-500">Sin datos aún</span>
            @endif
        </div>
    </div>
</div>

<form method="GET" action="{{ route('produccion.index') }}"
    class="bg-surface-light p-4 rounded-xl shadow-sm ring-1 ring-slate-200 flex flex-col lg:flex-row gap-4 justify-between items-center mb-6">
    <div class="flex flex-col sm:flex-row gap-3 w-full lg:w-auto items-center">

        <div class="flex items-center gap-2 w-full sm:w-auto">
            <input type="date" name="fecha_inicio" value="{{ request('fecha_inicio') }}"
                class="block w-full sm:w-36 px-3 py-2 border-none bg-slate-50 rounded-lg text-sm text-slate-600 focus:ring-2 focus:ring-primary/50">
            <span class="text-slate-400 text-sm">a</span>
            <input type="date" name="fecha_fin" value="{{ request('fecha_fin') }}"
                class="block w-full sm:w-36 px-3 py-2 border-none bg-slate-50 rounded-lg text-sm text-slate-600 focus:ring-2 focus:ring-primary/50">
        </div>

        <div class="relative w-full sm:w-40">
            <select name="turno"
                class="block w-full pl-3 pr-10 py-2 text-sm border-none bg-slate-50 rounded-lg focus:ring-2 focus:ring-primary/50 text-slate-900 cursor-pointer appearance-none"
                onchange="this.form.submit()">
                <option value="">Turno: Todos</option>
                <option value="manana" {{ request('turno') == 'manana' ? 'selected' : '' }}>Mañana</option>
                <option value="tarde" {{ request('turno') == 'tarde' ? 'selected' : '' }}>Tarde</option>
            </select>
            <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                <span class="material-symbols-outlined text-slate-400 text-[20px]">expand_more</span>
            </div>
        </div>

        <div class="relative w-full sm:w-60">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <span class="material-symbols-outlined text-slate-400 text-[20px]">search</span>
            </div>
            <input name="buscar" value="{{ request('buscar') }}"
                class="block w-full pl-10 pr-3 py-2 border-none bg-slate-50 rounded-lg text-sm placeholder-slate-500 focus:ring-2 focus:ring-primary/50 text-slate-900 transition-shadow"
                placeholder="Buscar Arete o Nombre..." type="text" />
        </div>

    </div>

    <div class="flex gap-2">
        @if(request()->hasAny(['buscar', 'fecha_inicio', 'fecha_fin', 'turno']))
        <a href="{{ route('produccion.index') }}"
            class="bg-slate-100 hover:bg-slate-200 text-slate-700 font-medium py-2 px-4 rounded-lg transition-colors flex items-center gap-2 text-sm">
            Limpiar Filtros
        </a>
        @endif
        <button type="submit"
            class="bg-primary hover:bg-primary-dark text-white font-medium py-2 px-4 rounded-lg transition-colors flex items-center gap-2 text-sm shadow-sm">
            Buscar
        </button>
    </div>
</form>

<div class="bg-surface-light rounded-xl shadow-sm ring-1 ring-slate-200 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left text-sm text-slate-600">
            <thead class="border-b border-slate-100 bg-slate-50 text-xs uppercase text-slate-500">
                <tr>
                    <th class="px-6 py-4 font-medium">Fecha y Hora</th>
                    <th class="px-6 py-4 font-medium">Turno</th>
                    <th class="px-6 py-4 font-medium">Vaca (Arete / Nombre)</th>
                    <th class="px-6 py-4 font-medium">Litros (L)</th>
                    <th class="px-6 py-4 font-medium">Registrado por</th>
                    <th class="px-6 py-4 font-medium text-right">Acciones</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @foreach($producciones as $prod)
                <tr class="hover:bg-slate-50/50 transition-colors">
                    <td class="px-6 py-3 whitespace-nowrap text-slate-900">
                        {{ \Carbon\Carbon::parse($prod->fecha_registro)->format('d M Y') }}
                    </td>
                    <td class="px-6 py-3 whitespace-nowrap">
                        @if($prod->turno == 'manana')
                        <span
                            class="inline-flex items-center gap-1 text-xs font-medium text-amber-600 bg-amber-50 px-2 py-1 rounded-md">
                            <span class="material-symbols-outlined text-[14px]">light_mode</span> Mañana
                        </span>
                        @else
                        <span
                            class="inline-flex items-center gap-1 text-xs font-medium text-indigo-600 bg-indigo-50 px-2 py-1 rounded-md">
                            <span class="material-symbols-outlined text-[14px]">dark_mode</span> Tarde
                        </span>
                        @endif
                    </td>
                    <td class="px-6 py-3 whitespace-nowrap">
                        <div class="flex flex-col">
                            <span class="font-medium text-slate-900">{{ $prod->animal->arete }}</span>
                            <span class="text-xs text-slate-500">{{ $prod->animal->nombre ?? 'Sin nombre' }}</span>
                        </div>
                    </td>
                    <td class="px-6 py-3 whitespace-nowrap font-bold text-slate-900">{{ $prod->litros }} L</td>
                    <td class="px-6 py-3 whitespace-nowrap">{{ $prod->user->name }}</td>

                    <td class="px-6 py-3 whitespace-nowrap text-right flex justify-end items-center gap-2">
                        @php
                        $fechaFormateada = \Carbon\Carbon::parse($prod->fecha_registro)->format('Y-m-d');
                        @endphp
                        <button type="button"
                            onclick="abrirModalEditarProduccion('{{ $prod->id }}', '{{ $prod->animal->arete }}', '{{ $prod->litros }}', '{{ $prod->turno }}', '{{ $fechaFormateada }}')"
                            class="text-slate-400 hover:text-primary transition-colors p-1" title="Corregir Registro">
                            <span class="material-symbols-outlined text-[20px]">edit</span>
                        </button>

                        <button onclick="abrirModalEliminarProduccion('{{ $prod->id }}')"
                            class="text-slate-400 hover:text-red-500 transition-colors p-1" title="Eliminar Registro">
                            <span class="material-symbols-outlined text-[20px]">delete</span>
                        </button>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection


@section('modales')
<div id="modalProduccion" class="hidden relative z-50">
    <div class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm transition-opacity"></div>
    <div class="fixed inset-0 z-10 flex items-center justify-center p-4">
        <div class="bg-white rounded-xl shadow-xl w-full max-w-md p-6 ring-1 ring-slate-200">
            <div class="flex items-center justify-between mb-5">
                <h3 class="text-lg font-bold text-slate-900 flex items-center gap-2">
                    <span class="material-symbols-outlined text-primary">water_drop</span> Registrar Ordeña
                </h3>
                <button onclick="cerrarModalProduccion()" class="text-slate-400 hover:text-red-500 transition-colors">
                    <span class="material-symbols-outlined">close</span>
                </button>
            </div>

            <form action="{{ route('produccion.store') }}" method="POST" class="space-y-4">
                @csrf

                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Vaca Ordeñada</label>
                    <select name="animal_id" required
                        class="w-full rounded-lg border-slate-200 text-sm focus:ring-primary focus:border-primary">
                        <option value="">Selecciona una vaca...</option>
                        @foreach($animales as $vaca)
                        <option value="{{ $vaca->id }}">{{ $vaca->arete }} - {{ $vaca->nombre }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Litros (L)</label>
                        <input type="number" step="0.1" min="0.1" name="litros" placeholder="Ej: 15.5" required
                            class="w-full rounded-lg border-slate-200 text-sm focus:ring-primary focus:border-primary">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Turno</label>
                        <select name="turno" required
                            class="w-full rounded-lg border-slate-200 text-sm focus:ring-primary focus:border-primary">
                            <option value="manana">Mañana</option>
                            <option value="tarde">Tarde</option>
                        </select>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Fecha</label>
                    <input type="date" name="fecha_registro" value="{{ date('Y-m-d') }}" required
                        class="w-full rounded-lg border-slate-200 text-sm focus:ring-primary focus:border-primary">
                </div>

                <div class="pt-4 flex justify-end gap-3 border-t border-slate-100">
                    <button type="button" onclick="cerrarModalProduccion()"
                        class="px-4 py-2 text-sm font-medium text-slate-600 hover:bg-slate-50 rounded-lg transition-colors">Cancelar</button>
                    <button type="submit"
                        class="bg-primary text-white px-4 py-2 rounded-lg text-sm font-bold hover:bg-primary-dark transition-colors shadow-sm">Guardar
                        Registro</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div id="modalEditarProduccion" class="hidden relative z-50">
    <div class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm"></div>
    <div class="fixed inset-0 z-10 flex items-center justify-center p-4">
        <div class="bg-white rounded-xl shadow-xl w-full max-w-md p-6 ring-1 ring-slate-200">
            <h3 class="text-lg font-bold text-slate-900 mb-4 flex items-center gap-2">
                <span class="material-symbols-outlined text-primary">edit</span> Editar Registro
            </h3>

            <form id="formEditarProduccion" method="POST" class="space-y-4">
                @csrf
                @method('PUT')

                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Vaca Ordeñada</label>
                    <input type="text" id="editVaca" disabled
                        class="w-full rounded-lg border-slate-200 bg-slate-100 text-slate-500 text-sm">
                    <p class="text-[10px] text-slate-400 mt-1">La vaca no se puede cambiar. Si es un error, elimina el
                        registro y crea uno nuevo.</p>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Litros (L)</label>
                        <input type="number" step="0.1" min="0.1" id="editLitros" name="litros" required
                            class="w-full rounded-lg border-slate-200 text-sm">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Turno</label>
                        <select id="editTurno" name="turno" required class="w-full rounded-lg border-slate-200 text-sm">
                            <option value="manana">Mañana</option>
                            <option value="tarde">Tarde</option>
                        </select>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Fecha</label>
                    <input type="date" id="editFecha" name="fecha_registro" required
                        class="w-full rounded-lg border-slate-200 text-sm">
                </div>

                <div class="pt-4 flex justify-end gap-3 border-t border-slate-100 mt-4">
                    <button type="button" onclick="cerrarModalEditarProduccion()"
                        class="px-4 py-2 text-sm text-slate-600 hover:bg-slate-50 rounded-lg">Cancelar</button>
                    <button type="submit"
                        class="bg-primary text-white px-4 py-2 rounded-lg text-sm font-bold shadow-sm">Guardar
                        Cambios</button>
                </div>
            </form>
        </div>
    </div>
</div>
<div id="modalEliminarProduccion" class="hidden relative z-[100]" aria-labelledby="modal-title" role="dialog"
    aria-modal="true">
    <div class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm transition-opacity"></div>
    <div class="fixed inset-0 z-10 w-screen overflow-y-auto">
        <div class="flex min-h-full items-center justify-center p-4 text-center sm:p-0">
            <div
                class="relative transform overflow-hidden rounded-xl bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-sm ring-1 ring-slate-200">
                <div class="p-6">
                    <div
                        class="mx-auto flex h-12 w-12 items-center justify-center rounded-full bg-red-50 text-red-600 mb-4">
                        <span class="material-symbols-outlined text-2xl">warning</span>
                    </div>
                    <div class="text-center">
                        <h3 class="text-lg font-bold text-slate-900" id="modal-title">¿Eliminar Registro de Ordeña?</h3>
                        <p class="mt-2 text-sm text-slate-500">Esta acción borrará estos litros del historial de la vaca
                            y del total de producción del día. No se puede deshacer.</p>
                    </div>
                </div>
                <div class="bg-slate-50 px-6 py-4 flex flex-col sm:flex-row-reverse gap-3">
                    <form id="formEliminarProduccion" method="POST" class="w-full sm:w-auto m-0 p-0">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                            class="inline-flex w-full justify-center rounded-lg bg-red-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-red-500 sm:w-auto transition-colors">
                            Sí, eliminar
                        </button>
                    </form>
                    <button type="button" onclick="cerrarModalEliminarProduccion()"
                        class="inline-flex w-full justify-center rounded-lg bg-white px-4 py-2 text-sm font-semibold text-slate-700 shadow-sm ring-1 ring-inset ring-slate-300 hover:bg-slate-50 sm:w-auto transition-colors">
                        Cancelar
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
function abrirModalProduccion() {
    document.getElementById('modalProduccion').classList.remove('hidden');
}

function cerrarModalProduccion() {
    document.getElementById('modalProduccion').classList.add('hidden');
}

function abrirModalEditarProduccion(id, arete, litros, turno, fecha) {
    document.getElementById('editVaca').value = arete;
    document.getElementById('editLitros').value = litros;
    document.getElementById('editTurno').value = turno;
    document.getElementById('editFecha').value = fecha;

    document.getElementById('formEditarProduccion').action = "/produccion/" + id;
    document.getElementById('modalEditarProduccion').classList.remove('hidden');
}

function cerrarModalEditarProduccion() {
    document.getElementById('modalEditarProduccion').classList.add('hidden');
}

function abrirModalEliminarProduccion(id) {
    document.getElementById('formEliminarProduccion').action = "/produccion/" + id;
    document.getElementById('modalEliminarProduccion').classList.remove('hidden');
}

function cerrarModalEliminarProduccion() {
    document.getElementById('modalEliminarProduccion').classList.add('hidden');
}
</script>
@endsection