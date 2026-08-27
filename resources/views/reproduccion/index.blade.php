@extends('layouts.admin')

@section('titulo', 'Reproducción')
@section('titulo_pagina', 'Maternidad y Reproducción')
@section('subtitulo_pagina', 'Control de gestaciones, inseminaciones y nacimientos.')

@section('acciones_cabecera')
<button onclick="abrirModalReproduccion()"
    class="rounded-lg bg-primary px-4 py-2 text-sm font-medium text-white shadow-lg shadow-primary/30 hover:bg-primary-dark transition-colors flex items-center gap-2">
    <span class="material-symbols-outlined text-[20px]">add</span>
    Nueva Inseminación
</button>
@endsection

@section('contenido')
<div class="grid grid-cols-1 gap-6 sm:grid-cols-3 mb-8">
    <div
        class="group relative overflow-hidden rounded-xl bg-surface-light p-6 shadow-sm ring-1 ring-slate-200 transition-all hover:shadow-md hover:ring-emerald-500/20">
        <div class="flex items-center justify-between">
            <p class="text-sm font-medium text-slate-500">Próximos Partos (30 días)</p>
            <span
                class="material-symbols-outlined text-emerald-400 group-hover:text-emerald-500 transition-colors">event</span>
        </div>
        <div class="mt-4 flex items-baseline gap-2">
            <span class="text-3xl font-bold text-slate-900">{{ $proximosPartos }}</span>
            <span class="rounded-full bg-emerald-50 px-2 py-0.5 text-xs font-medium text-emerald-600">Preparar
                corral</span>
        </div>
    </div>
    <div
        class="group relative overflow-hidden rounded-xl bg-surface-light p-6 shadow-sm ring-1 ring-slate-200 transition-all hover:shadow-md hover:ring-purple-500/20">
        <div class="flex items-center justify-between">
            <p class="text-sm font-medium text-slate-500">Total Gestantes</p>
            <span
                class="material-symbols-outlined text-purple-400 group-hover:text-purple-500 transition-colors">favorite</span>
        </div>
        <div class="mt-4 flex items-baseline gap-2">
            <span class="text-3xl font-bold text-slate-900">{{ $totalGestantes }}</span>
            <span class="text-sm text-slate-500">Confirmadas</span>
        </div>
    </div>
    <div
        class="group relative overflow-hidden rounded-xl bg-surface-light p-6 shadow-sm ring-1 ring-slate-200 transition-all hover:shadow-md hover:ring-amber-500/20">
        <div class="flex items-center justify-between">
            <p class="text-sm font-medium text-slate-500">En Observación</p>
            <span
                class="material-symbols-outlined text-amber-400 group-hover:text-amber-500 transition-colors">biotech</span>
        </div>
        <div class="mt-4 flex items-baseline gap-2">
            <span class="text-3xl font-bold text-slate-900">{{ $enObservacion }}</span>
            <span class="text-sm text-slate-500">Esperando tacto</span>
        </div>
    </div>
</div>

<form method="GET" action="{{ route('reproduccion.index') }}"
    class="bg-surface-light p-4 rounded-xl shadow-sm ring-1 ring-slate-200 flex flex-col lg:flex-row gap-4 justify-between items-center mb-6">
    <div class="flex flex-col sm:flex-row gap-3 w-full lg:w-auto items-center">

        <div class="relative w-full sm:w-60">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <span class="material-symbols-outlined text-slate-400 text-[20px]">search</span>
            </div>
            <input name="buscar" value="{{ request('buscar') }}"
                class="block w-full pl-10 pr-3 py-2.5 border-none bg-slate-50 rounded-lg text-sm placeholder-slate-500 focus:ring-2 focus:ring-primary/50 text-slate-900 transition-shadow"
                placeholder="Buscar por Arete..." type="text" />
        </div>

        <div class="relative w-full sm:w-48">
            <select name="estado" onchange="this.form.submit()"
                class="block w-full pl-3 pr-10 py-2.5 text-sm border-none bg-slate-50 rounded-lg focus:ring-2 focus:ring-primary/50 text-slate-900 cursor-pointer appearance-none">
                <option value="">Estado: Todos</option>
                <option value="gestante" {{ request('estado') == 'gestante' ? 'selected' : '' }}>Gestante Confirmada
                </option>
                <option value="observacion" {{ request('estado') == 'observacion' ? 'selected' : '' }}>En Observación
                </option>
                <option value="vacia" {{ request('estado') == 'vacia' ? 'selected' : '' }}>Vacía / Abierta</option>
            </select>
            <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                <span class="material-symbols-outlined text-slate-400 text-[20px]">expand_more</span>
            </div>
        </div>

    </div>

    <div class="flex gap-2">
        @if(request()->hasAny(['buscar', 'estado']))
        <a href="{{ route('reproduccion.index') }}"
            class="bg-slate-100 hover:bg-slate-200 text-slate-700 font-medium py-2 px-4 rounded-lg transition-colors flex items-center text-sm">Limpiar</a>
        @endif
        <button type="submit"
            class="bg-primary hover:bg-primary-dark text-white font-medium py-2 px-4 rounded-lg transition-colors flex items-center text-sm shadow-sm">Buscar</button>
    </div>
</form>

<div class="bg-surface-light rounded-xl shadow-sm ring-1 ring-slate-200 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left text-sm text-slate-600">
            <thead class="border-b border-slate-100 bg-slate-50 text-xs uppercase text-slate-500">
                <tr>
                    <th class="px-6 py-4 font-medium">Vaca (Arete / Nombre)</th>
                    <th class="px-6 py-4 font-medium">Último Servicio</th>
                    <th class="px-6 py-4 font-medium">Método / Toro</th>
                    <th class="px-6 py-4 font-medium">Estado</th>
                    <th class="px-6 py-4 font-medium">Parto Estimado</th>
                    <th class="px-6 py-4 font-medium text-right">Acciones</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @foreach($registros as $reg)
                <tr class="hover:bg-slate-50/50 transition-colors">
                    <td class="px-6 py-3 whitespace-nowrap">
                        <div class="flex flex-col">
                            <span class="font-medium text-slate-900">{{ $reg->animal->arete }}</span>
                            <span class="text-xs text-slate-500">{{ $reg->animal->nombre ?? 'Sin nombre' }}</span>
                        </div>
                    </td>
                    <td class="px-6 py-3 whitespace-nowrap text-slate-900">
                        {{ \Carbon\Carbon::parse($reg->fecha_servicio)->format('d M Y') }}
                    </td>
                    <td class="px-6 py-3 whitespace-nowrap">
                        {{ $reg->metodo }} <br>
                        <span class="text-xs text-slate-500">{{ $reg->toro_semen }}</span>
                    </td>
                    <td class="px-6 py-3 whitespace-nowrap">
                        @if($reg->estado == 'gestante')
                        <span
                            class="inline-flex items-center px-2 py-1 rounded-md text-xs font-medium bg-purple-50 text-purple-700 ring-1 ring-inset ring-purple-600/20">Gestante
                            Confirmada</span>
                        @elseif($reg->estado == 'observacion')
                        <span
                            class="inline-flex items-center px-2 py-1 rounded-md text-xs font-medium bg-amber-50 text-amber-700 ring-1 ring-inset ring-amber-600/20">En
                            Observación</span>
                        @else
                        <span
                            class="inline-flex items-center px-2 py-1 rounded-md text-xs font-medium bg-slate-100 text-slate-700 ring-1 ring-inset ring-slate-500/20">Vacía
                            / Falló</span>
                        @endif
                    </td>
                    <td class="px-6 py-3 whitespace-nowrap">
                        @if($reg->fecha_parto_estimada)
                        <div class="flex items-center gap-1 font-bold text-emerald-600">
                            <span class="material-symbols-outlined text-[16px]">priority_high</span>
                            {{ \Carbon\Carbon::parse($reg->fecha_parto_estimada)->format('d M Y') }}
                        </div>
                        @else
                        <span class="text-slate-400">N/A</span>
                        @endif
                    </td>
                    <td class="px-6 py-3 whitespace-nowrap text-right flex justify-end items-center gap-2">
                        <button
                            onclick="abrirModalEditarReproduccion('{{ $reg->id }}', '{{ $reg->animal->arete }}', '{{ \Carbon\Carbon::parse($reg->fecha_servicio)->format('Y-m-d') }}', '{{ $reg->estado }}', '{{ $reg->metodo }}', '{{ $reg->toro_semen }}')"
                            class="text-slate-400 hover:text-primary transition-colors p-1" title="Editar">
                            <span class="material-symbols-outlined text-[20px]">edit</span>
                        </button>

                        <button onclick="abrirModalEliminarReproduccion('{{ $reg->id }}')"
                            class="text-slate-400 hover:text-red-500 transition-colors p-1 flex" title="Eliminar">
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
<div id="modalReproduccion" class="hidden relative z-50">
    <div class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm transition-opacity"></div>
    <div class="fixed inset-0 z-10 flex items-center justify-center p-4">
        <div class="bg-white rounded-xl shadow-xl w-full max-w-md p-6 ring-1 ring-slate-200">
            <div class="flex items-center justify-between mb-5">
                <h3 class="text-lg font-bold text-slate-900 flex items-center gap-2">
                    <span class="material-symbols-outlined text-primary">genetics</span> Registrar Servicio
                </h3>
                <button type="button" onclick="cerrarModalReproduccion()"
                    class="text-slate-400 hover:text-red-500 transition-colors">
                    <span class="material-symbols-outlined">close</span>
                </button>
            </div>

            <form action="{{ route('reproduccion.store') }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Vaca a inseminar</label>
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
                        <label class="block text-sm font-medium text-slate-700 mb-1">Fecha del Servicio</label>
                        <input type="date" name="fecha_servicio" value="{{ date('Y-m-d') }}" required
                            class="w-full rounded-lg border-slate-200 text-sm">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Estado</label>
                        <select name="estado" required class="w-full rounded-lg border-slate-200 text-sm">
                            <option value="observacion" selected>En Observación</option>
                            <option value="gestante">Confirmada Gestante</option>
                            <option value="vacia">Vacía</option>
                        </select>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Método</label>
                    <select name="metodo" required class="w-full rounded-lg border-slate-200 text-sm">
                        <option value="Inseminación Artificial">Inseminación Artificial</option>
                        <option value="Monta Natural">Monta Natural</option>
                        <option value="Transferencia de Embrión">Transferencia de Embrión</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Toro o Código de Semen
                        (Opcional)</label>
                    <input type="text" name="toro_semen" placeholder="Ej. Angus T-04"
                        class="w-full rounded-lg border-slate-200 text-sm">
                </div>

                <div class="pt-4 flex justify-end gap-3 border-t border-slate-100">
                    <button type="button" onclick="cerrarModalReproduccion()"
                        class="px-4 py-2 text-sm text-slate-600 hover:bg-slate-50 rounded-lg">Cancelar</button>
                    <button type="submit"
                        class="bg-primary text-white px-4 py-2 rounded-lg text-sm font-bold shadow-sm">Guardar
                        Registro</button>
                </div>
            </form>
        </div>
    </div>
</div>
<div id="modalEditarReproduccion" class="hidden relative z-50">
    <div class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm transition-opacity"></div>
    <div class="fixed inset-0 z-10 flex items-center justify-center p-4">
        <div class="bg-white rounded-xl shadow-xl w-full max-w-md p-6 ring-1 ring-slate-200">
            <div class="flex items-center justify-between mb-5">
                <h3 class="text-lg font-bold text-slate-900 flex items-center gap-2">
                    <span class="material-symbols-outlined text-primary">edit</span> Editar Servicio
                </h3>
                <button type="button" onclick="cerrarModalEditarReproduccion()"
                    class="text-slate-400 hover:text-red-500 transition-colors">
                    <span class="material-symbols-outlined">close</span>
                </button>
            </div>

            <form id="formEditarReproduccion" method="POST" class="space-y-4">
                @csrf
                @method('PUT')

                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Vaca (No se puede cambiar)</label>
                    <input type="text" id="editVacaRepro" disabled
                        class="w-full rounded-lg border-slate-200 bg-slate-100 text-slate-500 text-sm">
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Fecha del Servicio</label>
                        <input type="date" id="editFechaRepro" name="fecha_servicio" required
                            class="w-full rounded-lg border-slate-200 text-sm">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Estado</label>
                        <select id="editEstadoRepro" name="estado" required
                            class="w-full rounded-lg border-slate-200 text-sm">
                            <option value="observacion">En Observación</option>
                            <option value="gestante">Confirmada Gestante</option>
                            <option value="vacia">Vacía</option>
                        </select>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Método</label>
                    <select id="editMetodoRepro" name="metodo" required
                        class="w-full rounded-lg border-slate-200 text-sm">
                        <option value="Inseminación Artificial">Inseminación Artificial</option>
                        <option value="Monta Natural">Monta Natural</option>
                        <option value="Transferencia de Embrión">Transferencia de Embrión</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Toro o Código de Semen</label>
                    <input type="text" id="editToroRepro" name="toro_semen"
                        class="w-full rounded-lg border-slate-200 text-sm">
                </div>

                <div class="pt-4 flex justify-end gap-3 border-t border-slate-100 mt-4">
                    <button type="button" onclick="cerrarModalEditarReproduccion()"
                        class="px-4 py-2 text-sm text-slate-600 hover:bg-slate-50 rounded-lg">Cancelar</button>
                    <button type="submit"
                        class="bg-primary text-white px-4 py-2 rounded-lg text-sm font-bold shadow-sm">Guardar
                        Cambios</button>
                </div>
            </form>
        </div>
    </div>
</div>
<div id="modalEliminarReproduccion" class="hidden relative z-[100]" aria-labelledby="modal-title" role="dialog"
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
                        <h3 class="text-lg font-bold text-slate-900" id="modal-title">¿Eliminar Registro de
                            Reproducción?</h3>
                        <p class="mt-2 text-sm text-slate-500">Se borrará este registro de servicio/inseminación del
                            historial de la vaca. Esta acción no se puede deshacer.</p>
                    </div>
                </div>
                <div class="bg-slate-50 px-6 py-4 flex flex-col sm:flex-row-reverse gap-3">
                    <form id="formEliminarReproduccion" method="POST" class="w-full sm:w-auto m-0 p-0">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                            class="inline-flex w-full justify-center rounded-lg bg-red-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-red-500 sm:w-auto transition-colors">
                            Sí, eliminar
                        </button>
                    </form>
                    <button type="button" onclick="cerrarModalEliminarReproduccion()"
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
function abrirModalReproduccion() {
    document.getElementById('modalReproduccion').classList.remove('hidden');
}

function cerrarModalReproduccion() {
    document.getElementById('modalReproduccion').classList.add('hidden');
}

function abrirModalEditarReproduccion(id, arete, fecha, estado, metodo, toro) {
    document.getElementById('editVacaRepro').value = arete;
    document.getElementById('editFechaRepro').value = fecha;
    document.getElementById('editEstadoRepro').value = estado;
    document.getElementById('editMetodoRepro').value = metodo;
    document.getElementById('editToroRepro').value = toro;

    document.getElementById('formEditarReproduccion').action = "/reproduccion/" + id;
    document.getElementById('modalEditarReproduccion').classList.remove('hidden');
}

function cerrarModalEditarReproduccion() {
    document.getElementById('modalEditarReproduccion').classList.add('hidden');
}

function abrirModalEliminarReproduccion(id) {
    document.getElementById('formEliminarReproduccion').action = "/reproduccion/" + id;
    document.getElementById('modalEliminarReproduccion').classList.remove('hidden');
}

function cerrarModalEliminarReproduccion() {
    document.getElementById('modalEliminarReproduccion').classList.add('hidden');
}
</script>
@endsection