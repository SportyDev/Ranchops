@extends('layouts.admin')

@section('titulo', 'Salud')
@section('titulo_pagina', 'Salud y Veterinaria')
@section('subtitulo_pagina', 'Control clínico, tratamientos y calendario de vacunación.')

@section('acciones_cabecera')
<button onclick="abrirModalSalud()"
    class="rounded-lg bg-primary px-4 py-2 text-sm font-medium text-white shadow-lg shadow-primary/30 hover:bg-primary-dark transition-colors flex items-center gap-2">
    <span class="material-symbols-outlined text-[20px]">add</span>
    Nuevo Registro Médico
</button>
@endsection

@section('contenido')
<div class="grid grid-cols-1 gap-6 sm:grid-cols-3 mb-8">
    <div
        class="group relative overflow-hidden rounded-xl bg-surface-light p-6 shadow-sm ring-1 ring-slate-200 transition-all hover:shadow-md hover:ring-red-500/20">
        <div class="flex items-center justify-between">
            <p class="text-sm font-medium text-slate-500">Enfermos / Cuarentena</p>
            <span
                class="material-symbols-outlined text-red-400 group-hover:text-red-500 transition-colors">warning</span>
        </div>
        <div class="mt-4 flex items-baseline gap-2">
            <span class="text-3xl font-bold text-slate-900">{{ $enfermos }}</span>
            <span class="rounded-full bg-red-50 px-2 py-0.5 text-xs font-medium text-red-600">En Tratamiento</span>
        </div>
    </div>
    <div
        class="group relative overflow-hidden rounded-xl bg-surface-light p-6 shadow-sm ring-1 ring-slate-200 transition-all hover:shadow-md hover:ring-blue-500/20">
        <div class="flex items-center justify-between">
            <p class="text-sm font-medium text-slate-500">Vacunas (Próx. 7 días)</p>
            <span
                class="material-symbols-outlined text-blue-400 group-hover:text-blue-500 transition-colors">vaccines</span>
        </div>
        <div class="mt-4 flex items-baseline gap-2">
            <span class="text-3xl font-bold text-slate-900">{{ $vacunasProximas }}</span>
            <span class="text-sm text-slate-500">Dosis programadas</span>
        </div>
    </div>
    <div
        class="group relative overflow-hidden rounded-xl bg-surface-light p-6 shadow-sm ring-1 ring-slate-200 transition-all hover:shadow-md hover:ring-primary/20">
        <div class="flex items-center justify-between">
            <p class="text-sm font-medium text-slate-500">Gasto Mensual (Medicina)</p>
            <span
                class="material-symbols-outlined text-slate-400 group-hover:text-primary transition-colors">payments</span>
        </div>
        <div class="mt-4 flex items-baseline gap-2">
            <span class="text-3xl font-bold text-slate-900">${{ number_format($gastoMensual, 2) }}</span>
            <span class="text-sm text-slate-500">MXN</span>
        </div>
    </div>
</div>

<form method="GET" action="{{ route('salud.index') }}"
    class="bg-surface-light p-4 rounded-xl shadow-sm ring-1 ring-slate-200 flex flex-col lg:flex-row gap-4 justify-between items-center mb-6">
    <div class="flex flex-col sm:flex-row gap-3 w-full lg:w-auto items-center">
        <div class="relative w-full sm:w-60">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <span class="material-symbols-outlined text-slate-400 text-[20px]">search</span>
            </div>
            <input name="buscar" value="{{ request('buscar') }}"
                class="block w-full pl-10 pr-3 py-2.5 border-none bg-slate-50 rounded-lg text-sm placeholder-slate-500 focus:ring-2 focus:ring-primary/50 text-slate-900 transition-shadow"
                placeholder="Buscar por Arete o Lote..." type="text" />
        </div>
        <div class="relative w-full sm:w-48">
            <select name="categoria" onchange="this.form.submit()"
                class="block w-full pl-3 pr-10 py-2.5 text-sm border-none bg-slate-50 rounded-lg focus:ring-2 focus:ring-primary/50 text-slate-900 cursor-pointer appearance-none">
                <option value="">Categoría: Todas</option>
                <option value="enfermedad" {{ request('categoria') == 'enfermedad' ? 'selected' : '' }}>Enfermedad
                </option>
                <option value="vacuna" {{ request('categoria') == 'vacuna' ? 'selected' : '' }}>Vacunación</option>
                <option value="revision" {{ request('categoria') == 'revision' ? 'selected' : '' }}>Revisión General
                </option>
            </select>
            <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                <span class="material-symbols-outlined text-slate-400 text-[20px]">expand_more</span>
            </div>
        </div>
        <div class="relative w-full sm:w-48">
            <select name="estado" onchange="this.form.submit()"
                class="block w-full pl-3 pr-10 py-2.5 text-sm border-none bg-slate-50 rounded-lg focus:ring-2 focus:ring-primary/50 text-slate-900 cursor-pointer appearance-none">
                <option value="">Estado: Todos</option>
                <option value="activo" {{ request('estado') == 'activo' ? 'selected' : '' }}>En Tratamiento</option>
                <option value="programado" {{ request('estado') == 'programado' ? 'selected' : '' }}>Programado</option>
                <option value="completado" {{ request('estado') == 'completado' ? 'selected' : '' }}>Completado</option>
            </select>
            <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                <span class="material-symbols-outlined text-slate-400 text-[20px]">expand_more</span>
            </div>
        </div>
    </div>
    <div class="flex gap-2">
        @if(request()->hasAny(['buscar', 'categoria', 'estado']))
        <a href="{{ route('salud.index') }}"
            class="bg-slate-100 hover:bg-slate-200 text-slate-700 font-medium py-2.5 px-4 rounded-lg transition-colors flex items-center text-sm">Limpiar</a>
        @endif
        <button type="submit"
            class="bg-primary hover:bg-primary-dark text-white font-medium py-2.5 px-4 rounded-lg transition-colors flex items-center text-sm shadow-sm">Buscar</button>
    </div>
</form>

<div class="bg-surface-light rounded-xl shadow-sm ring-1 ring-slate-200 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left text-sm text-slate-600">
            <thead class="border-b border-slate-100 bg-slate-50 text-xs uppercase text-slate-500">
                <tr>
                    <th class="px-6 py-4 font-medium">Fecha</th>
                    <th class="px-6 py-4 font-medium">Categoría</th>
                    <th class="px-6 py-4 font-medium">Vaca / Lote</th>
                    <th class="px-6 py-4 font-medium">Diagnóstico / Procedimiento</th>
                    <th class="px-6 py-4 font-medium">Estado</th>
                    <th class="px-6 py-4 font-medium">Veterinario</th>
                    <th class="px-6 py-4 font-medium text-right">Acciones</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @foreach($registros as $reg)
                <tr class="hover:bg-slate-50/50 transition-colors">
                    <td class="px-6 py-3 whitespace-nowrap text-slate-900">
                        {{ \Carbon\Carbon::parse($reg->fecha)->format('d M Y') }}</td>
                    <td class="px-6 py-3 whitespace-nowrap">
                        @if($reg->categoria == 'enfermedad')
                        <span class="inline-flex items-center gap-1 text-xs font-medium text-slate-600"><span
                                class="material-symbols-outlined text-[16px] text-red-500">coronavirus</span>
                            Enfermedad</span>
                        @elseif($reg->categoria == 'vacuna')
                        <span class="inline-flex items-center gap-1 text-xs font-medium text-slate-600"><span
                                class="material-symbols-outlined text-[16px] text-blue-500">vaccines</span>
                            Vacuna</span>
                        @else
                        <span class="inline-flex items-center gap-1 text-xs font-medium text-slate-600"><span
                                class="material-symbols-outlined text-[16px] text-green-500">check_circle</span>
                            Revisión</span>
                        @endif
                    </td>
                    <td class="px-6 py-3 whitespace-nowrap">
                        <div class="flex flex-col">
                            @if($reg->animal)
                            <span class="font-medium text-slate-900">{{ $reg->animal->arete }}</span>
                            <span class="text-xs text-slate-500">{{ $reg->animal->nombre ?? 'Sin nombre' }}</span>
                            @else
                            <span class="font-medium text-slate-900">Lote</span>
                            <span class="text-xs text-slate-500">{{ $reg->lote_nombre }}</span>
                            @endif
                        </div>
                    </td>
                    <td class="px-6 py-3 whitespace-nowrap">{{ $reg->diagnostico_tratamiento }} <br> <span
                            class="text-[10px] text-slate-400">Costo: ${{ $reg->costo }}</span></td>
                    <td class="px-6 py-3 whitespace-nowrap">
                        @if($reg->estado == 'activo')
                        <span
                            class="inline-flex items-center px-2 py-1 rounded-md text-xs font-medium bg-red-50 text-red-700 ring-1 ring-inset ring-red-600/20">En
                            Tratamiento</span>
                        @elseif($reg->estado == 'programado')
                        <span
                            class="inline-flex items-center px-2 py-1 rounded-md text-xs font-medium bg-amber-50 text-amber-700 ring-1 ring-inset ring-amber-600/20">Programado</span>
                        @else
                        <span
                            class="inline-flex items-center px-2 py-1 rounded-md text-xs font-medium bg-green-50 text-green-700 ring-1 ring-inset ring-green-600/20">Completado</span>
                        @endif
                    </td>
                    <td class="px-6 py-3 whitespace-nowrap">{{ $reg->veterinario ?? 'N/A' }}</td>
                    <td class="px-6 py-3 whitespace-nowrap text-right flex justify-end items-center gap-2">
                        <button
                            onclick="abrirModalEditarSalud('{{ $reg->id }}', '{{ $reg->fecha }}', '{{ $reg->categoria }}', '{{ addslashes($reg->diagnostico_tratamiento) }}', '{{ $reg->estado }}', '{{ $reg->veterinario }}', '{{ $reg->costo }}')"
                            class="text-slate-400 hover:text-primary transition-colors p-1" title="Editar">
                            <span class="material-symbols-outlined text-[20px]">edit</span>
                        </button>

                        <button onclick="abrirModalEliminarSalud('{{ $reg->id }}')"
                            class="text-slate-400 hover:text-red-500 transition-colors p-1" title="Eliminar">
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
<div id="modalSalud" class="hidden relative z-50">
    <div class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm transition-opacity"></div>
    <div class="fixed inset-0 z-10 flex items-center justify-center p-4">
        <div class="bg-white rounded-xl shadow-xl w-full max-w-md p-6 ring-1 ring-slate-200">
            <div class="flex items-center justify-between mb-5">
                <h3 class="text-lg font-bold text-slate-900 flex items-center gap-2">
                    <span class="material-symbols-outlined text-primary">medical_services</span> Nuevo Registro
                </h3>
                <button type="button" onclick="cerrarModalSalud()"
                    class="text-slate-400 hover:text-red-500 transition-colors">
                    <span class="material-symbols-outlined">close</span>
                </button>
            </div>

            <form action="{{ route('salud.store') }}" method="POST" class="space-y-4">
                @csrf
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Categoría</label>
                        <select name="categoria" required class="w-full rounded-lg border-slate-200 text-sm">
                            <option value="enfermedad">Enfermedad</option>
                            <option value="vacuna">Vacunación</option>
                            <option value="revision">Revisión General</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Estado</label>
                        <select name="estado" required class="w-full rounded-lg border-slate-200 text-sm">
                            <option value="activo">En Tratamiento</option>
                            <option value="programado">Programado</option>
                            <option value="completado">Completado</option>
                        </select>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Aplicación a:</label>
                    <select id="tipoAplicacion" onchange="toggleLoteVaca()"
                        class="w-full rounded-lg border-slate-200 text-sm mb-2">
                        <option value="vaca">Vaca Individual</option>
                        <option value="lote">Lote / Grupo</option>
                    </select>

                    <select name="animal_id" id="selectVaca" class="w-full rounded-lg border-slate-200 text-sm">
                        <option value="">Selecciona una vaca...</option>
                        @foreach($animales as $vaca)
                        <option value="{{ $vaca->id }}">{{ $vaca->arete }} - {{ $vaca->nombre }}</option>
                        @endforeach
                    </select>
                    <input type="text" name="lote_nombre" id="inputLote" placeholder="Ej: Lote Becerros (12)"
                        class="hidden w-full rounded-lg border-slate-200 text-sm">
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Diagnóstico / Procedimiento</label>
                    <input type="text" name="diagnostico_tratamiento" required
                        placeholder="Ej: Vacuna Brucelosis o Mastitis"
                        class="w-full rounded-lg border-slate-200 text-sm">
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Fecha</label>
                        <input type="date" name="fecha" value="{{ date('Y-m-d') }}" required
                            class="w-full rounded-lg border-slate-200 text-sm">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Veterinario (Opcional)</label>
                        <input type="text" name="veterinario" placeholder="Dr. Ríos"
                            class="w-full rounded-lg border-slate-200 text-sm">
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Costo del Tratamiento / Medicina
                        ($)</label>
                    <input type="number" step="0.01" name="costo" placeholder="0.00"
                        class="w-full rounded-lg border-slate-200 text-sm text-green-700 font-bold">
                </div>

                <div class="pt-4 flex justify-end gap-3 border-t border-slate-100">
                    <button type="button" onclick="cerrarModalSalud()"
                        class="px-4 py-2 text-sm text-slate-600 hover:bg-slate-50 rounded-lg">Cancelar</button>
                    <button type="submit"
                        class="bg-primary text-white px-4 py-2 rounded-lg text-sm font-bold shadow-sm">Guardar
                        Registro</button>
                </div>
            </form>
        </div>
    </div>
</div>
<div id="modalEditarSalud" class="hidden relative z-50">
    <div class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm transition-opacity"></div>
    <div class="fixed inset-0 z-10 flex items-center justify-center p-4">
        <div class="bg-white rounded-xl shadow-xl w-full max-w-md p-6 ring-1 ring-slate-200">
            <div class="flex items-center justify-between mb-5">
                <h3 class="text-lg font-bold text-slate-900 flex items-center gap-2">
                    <span class="material-symbols-outlined text-primary">edit</span> Editar Registro
                </h3>
                <button type="button" onclick="cerrarModalEditarSalud()"
                    class="text-slate-400 hover:text-red-500 transition-colors">
                    <span class="material-symbols-outlined">close</span>
                </button>
            </div>

            <form id="formEditarSalud" method="POST" class="space-y-4">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Categoría</label>
                        <select id="editCategoriaSalud" name="categoria" required
                            class="w-full rounded-lg border-slate-200 text-sm">
                            <option value="enfermedad">Enfermedad</option>
                            <option value="vacuna">Vacunación</option>
                            <option value="revision">Revisión General</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Estado</label>
                        <select id="editEstadoSalud" name="estado" required
                            class="w-full rounded-lg border-slate-200 text-sm">
                            <option value="activo">En Tratamiento</option>
                            <option value="programado">Programado</option>
                            <option value="completado">Completado</option>
                        </select>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Diagnóstico / Procedimiento</label>
                    <input type="text" id="editDiagnosticoSalud" name="diagnostico_tratamiento" required
                        class="w-full rounded-lg border-slate-200 text-sm">
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Fecha</label>
                        <input type="date" id="editFechaSalud" name="fecha" required
                            class="w-full rounded-lg border-slate-200 text-sm">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Veterinario</label>
                        <input type="text" id="editVeterinarioSalud" name="veterinario"
                            class="w-full rounded-lg border-slate-200 text-sm">
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Costo del Tratamiento ($)</label>
                    <input type="number" step="0.01" id="editCostoSalud" name="costo"
                        class="w-full rounded-lg border-slate-200 text-sm text-green-700 font-bold">
                </div>

                <div class="pt-4 flex justify-end gap-3 border-t border-slate-100">
                    <button type="button" onclick="cerrarModalEditarSalud()"
                        class="px-4 py-2 text-sm text-slate-600 hover:bg-slate-50 rounded-lg">Cancelar</button>
                    <button type="submit"
                        class="bg-primary text-white px-4 py-2 rounded-lg text-sm font-bold shadow-sm">Guardar
                        Cambios</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div id="modalEliminarSalud" class="hidden relative z-[100]" aria-labelledby="modal-title" role="dialog"
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
                        <h3 class="text-lg font-bold text-slate-900" id="modal-title">¿Eliminar Registro Médico?</h3>
                        <p class="mt-2 text-sm text-slate-500">Esta acción no se puede deshacer. Se borrará del
                            historial de la vaca y se restará del gasto mensual.</p>
                    </div>
                </div>
                <div class="bg-slate-50 px-6 py-4 flex flex-col sm:flex-row-reverse gap-3">
                    <form id="formEliminarSalud" method="POST" class="w-full sm:w-auto m-0 p-0">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                            class="inline-flex w-full justify-center rounded-lg bg-red-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-red-500 sm:w-auto transition-colors">
                            Sí, eliminar
                        </button>
                    </form>
                    <button type="button" onclick="cerrarModalEliminarSalud()"
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
function abrirModalSalud() {
    document.getElementById('modalSalud').classList.remove('hidden');
}

function cerrarModalSalud() {
    document.getElementById('modalSalud').classList.add('hidden');
}
// Lógica para cambiar entre Vaca Individual y Lote
function toggleLoteVaca() {
    const tipo = document.getElementById('tipoAplicacion').value;
    const selectVaca = document.getElementById('selectVaca');
    const inputLote = document.getElementById('inputLote');

    if (tipo === 'lote') {
        selectVaca.classList.add('hidden');
        selectVaca.value = ""; // Limpiamos selección
        inputLote.classList.remove('hidden');
    } else {
        inputLote.classList.add('hidden');
        inputLote.value = ""; // Limpiamos texto
        selectVaca.classList.remove('hidden');
    }
}
// --- Lógica de Edición ---
function abrirModalEditarSalud(id, fecha, categoria, diagnostico, estado, veterinario, costo) {
    document.getElementById('editFechaSalud').value = fecha;
    document.getElementById('editCategoriaSalud').value = categoria;
    document.getElementById('editDiagnosticoSalud').value = diagnostico;
    document.getElementById('editEstadoSalud').value = estado;
    document.getElementById('editVeterinarioSalud').value = veterinario;
    document.getElementById('editCostoSalud').value = costo;

    document.getElementById('formEditarSalud').action = "/salud/" + id;
    document.getElementById('modalEditarSalud').classList.remove('hidden');
}

function cerrarModalEditarSalud() {
    document.getElementById('modalEditarSalud').classList.add('hidden');
}

// --- Lógica de Súper Modal de Eliminar ---
function abrirModalEliminarSalud(id) {
    document.getElementById('formEliminarSalud').action = "/salud/" + id;
    document.getElementById('modalEliminarSalud').classList.remove('hidden');
}

function cerrarModalEliminarSalud() {
    document.getElementById('modalEliminarSalud').classList.add('hidden');
}
</script>
@endsection