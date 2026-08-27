@extends('layouts.admin')

@section('titulo', 'Inventario')
@section('titulo_pagina', 'Inventario de Ganado')
@section('subtitulo_pagina', 'Administra y rastrea todo el registro de la granja.')

@section('acciones_cabecera')
<button onclick="abrirModalAgregarInventario()"
    class="rounded-lg bg-primary px-4 py-2 text-sm font-medium text-white shadow-lg shadow-primary/30 hover:bg-primary-dark transition-colors flex items-center gap-2">
    <span class="material-symbols-outlined text-[20px]">add</span> Agregar Animal
</button>
@endsection

@section('contenido')
<form method="GET" action="{{ route('inventario.index') }}"
    class="bg-surface-light p-4 rounded-xl shadow-sm ring-1 ring-slate-200 flex flex-col lg:flex-row gap-4 justify-between items-center mb-6">
    <div class="flex flex-col sm:flex-row gap-3 w-full lg:w-auto items-center">

        <div class="relative w-full sm:w-40">
            <select name="estado"
                class="block w-full pl-3 pr-10 py-2 text-sm border-none bg-slate-50 rounded-lg focus:ring-2 focus:ring-primary/50 text-slate-900 cursor-pointer appearance-none"
                onchange="this.form.submit()">
                <option value="">Estado: Todos</option>
                <option value="becerro" {{ request('estado') == 'becerro' ? 'selected' : '' }}>Becerros/as</option>
                <option value="novilla" {{ request('estado') == 'novilla' ? 'selected' : '' }}>Novillas</option>
                <option value="lactante" {{ request('estado') == 'lactante' ? 'selected' : '' }}>Lactantes</option>
                <option value="seca" {{ request('estado') == 'seca' ? 'selected' : '' }}>Secas</option>
                <option value="toro" {{ request('estado') == 'toro' ? 'selected' : '' }}>Toros</option>
            </select>
            <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                <span class="material-symbols-outlined text-slate-400 text-[20px]">expand_more</span>
            </div>
        </div>

        <div class="relative w-full sm:w-40">
            <select name="raza"
                class="block w-full pl-3 pr-10 py-2 text-sm border-none bg-slate-50 rounded-lg focus:ring-2 focus:ring-primary/50 text-slate-900 cursor-pointer appearance-none"
                onchange="this.form.submit()">
                <option value="">Raza: Todas</option>
                <option value="holstein" {{ request('raza') == 'holstein' ? 'selected' : '' }}>Holstein</option>
                <option value="jersey" {{ request('raza') == 'jersey' ? 'selected' : '' }}>Jersey</option>
                <option value="angus" {{ request('raza') == 'angus' ? 'selected' : '' }}>Angus</option>
                <option value="highland" {{ request('raza') == 'highland' ? 'selected' : '' }}>Highland</option>
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
        @if(request()->hasAny(['buscar', 'estado', 'raza']) && (request('buscar') != '' || request('estado') != '' ||
        request('raza') != ''))
        <a href="{{ route('inventario.index') }}"
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
                    <th class="px-6 py-4 font-medium">Foto</th>
                    <th class="px-6 py-4 font-medium">Arete</th>
                    <th class="px-6 py-4 font-medium">Nombre/Alias</th>
                    <th class="px-6 py-4 font-medium">Raza</th>
                    <th class="px-6 py-4 font-medium">Estado</th>
                    <th class="px-6 py-4 font-medium text-right">Acciones</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse ($animales as $animal)
                <tr class="hover:bg-slate-50/50 transition-colors">
                    <td class="px-6 py-3 whitespace-nowrap">
                        @if($animal->foto)
                        <img src="{{ asset('storage/' . $animal->foto) }}" alt="Foto"
                            class="h-10 w-10 rounded-full object-cover shadow-sm ring-1 ring-slate-200">
                        @else
                        <div
                            class="flex h-10 w-10 items-center justify-center rounded-full bg-slate-100 text-slate-400 ring-1 ring-slate-200">
                            <span class="material-symbols-outlined text-[20px]">pets</span>
                        </div>
                        @endif
                    </td>
                    <td class="px-6 py-3 whitespace-nowrap font-medium text-slate-900">{{ $animal->arete }}</td>
                    <td class="px-6 py-3 whitespace-nowrap">{{ $animal->nombre ?? 'Sin nombre' }}</td>
                    <td class="px-6 py-3 whitespace-nowrap">{{ ucfirst($animal->raza) }}</td>
                    <td class="px-6 py-3 whitespace-nowrap">
                        <span
                            class="inline-flex items-center px-2 py-1 rounded-md text-xs font-medium bg-green-50 text-green-700 ring-1 ring-inset ring-green-600/20">
                            {{ ucfirst($animal->estado) }}
                        </span>
                    </td>
                    <td class="px-6 py-3 whitespace-nowrap text-right flex justify-end items-center gap-2">
                        @php
                        $fechaFormateada = $animal->fecha_nacimiento ?
                        \Carbon\Carbon::parse($animal->fecha_nacimiento)->format('Y-m-d') : '';
                        $nombreAnimal = $animal->nombre ?? '';
                        @endphp

                        <button type="button"
                            onclick="abrirModalEditarInventario('{{ $animal->id }}', '{{ $animal->arete }}', '{{ $nombreAnimal }}', '{{ strtolower($animal->raza) }}', '{{ strtolower($animal->sexo) }}', '{{ $fechaFormateada }}', '{{ strtolower($animal->estado) }}')"
                            class="text-slate-400 hover:text-primary transition-colors p-1" title="Editar Registro">
                            <span class="material-symbols-outlined text-[20px]">edit</span>
                        </button>

                        <button type="button" onclick="abrirModalEliminarInventario('{{ $animal->id }}')"
                            class="text-slate-400 hover:text-red-500 transition-colors p-1" title="Eliminar Registro">
                            <span class="material-symbols-outlined text-[20px]">delete</span>
                        </button>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-6 py-8 text-center text-slate-500">
                        No hay animales registrados aún. ¡Agrega el primero!
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection

@section('modales')
<div id="modalAgregarInventario" class="hidden relative z-50">
    <div class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm transition-opacity"></div>
    <div class="fixed inset-0 z-10 flex items-center justify-center p-4">
        <div class="bg-white rounded-xl shadow-xl w-full max-w-2xl p-6 ring-1 ring-slate-200">
            <div class="flex items-center justify-between mb-5 border-b border-slate-100 pb-4">
                <h3 class="text-lg font-bold text-slate-900 flex items-center gap-2">
                    <span class="material-symbols-outlined text-primary">add_circle</span> Registrar Nuevo Animal
                </h3>
                <button onclick="cerrarModalAgregarInventario()"
                    class="text-slate-400 hover:text-red-500 transition-colors">
                    <span class="material-symbols-outlined">close</span>
                </button>
            </div>

            <form action="{{ route('inventario.store') }}" method="POST" enctype="multipart/form-data"
                class="space-y-4">
                @csrf
                <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Foto del Animal</label>
                        <input type="file" name="foto" accept="image/*"
                            class="block w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-primary/10 file:text-primary hover:file:bg-primary/20 transition-colors">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">ID Arete (Oficial)</label>
                        <input type="text" name="arete" required placeholder="Ej. #SF-2026-001"
                            class="w-full rounded-lg border-slate-200 text-sm focus:ring-primary focus:border-primary">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Nombre o Alias</label>
                        <input type="text" name="nombre" placeholder="Ej. Pinto"
                            class="w-full rounded-lg border-slate-200 text-sm focus:ring-primary focus:border-primary">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Raza</label>
                        <select name="raza" required
                            class="w-full rounded-lg border-slate-200 text-sm focus:ring-primary focus:border-primary">
                            <option value="" disabled selected>Selecciona una raza...</option>
                            <option value="holstein">Holstein</option>
                            <option value="jersey">Jersey</option>
                            <option value="angus">Angus</option>
                            <option value="highland">Highland</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Sexo</label>
                        <div class="flex items-center gap-4 mt-2">
                            <label class="flex items-center gap-2 text-sm text-slate-700">
                                <input type="radio" name="sexo" value="hembra" checked
                                    class="text-primary focus:ring-primary"> Hembra
                            </label>
                            <label class="flex items-center gap-2 text-sm text-slate-700">
                                <input type="radio" name="sexo" value="macho" class="text-primary focus:ring-primary">
                                Macho
                            </label>
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Fecha de Nacimiento</label>
                        <input type="date" name="fecha_nacimiento"
                            class="w-full rounded-lg border-slate-200 text-sm focus:ring-primary focus:border-primary">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Estado Inicial</label>
                        <select name="estado" required
                            class="w-full rounded-lg border-slate-200 text-sm focus:ring-primary focus:border-primary">
                            <option value="becerro">Becerro/a (Cría)</option>
                            <option value="novilla">Novilla</option>
                            <option value="lactante">Lactante (Producción)</option>
                            <option value="seca">Seca</option>
                            <option value="toro">Toro (Semental)</option>
                        </select>
                    </div>
                </div>

                <div class="pt-4 flex justify-end gap-3 border-t border-slate-100 mt-4">
                    <button type="button" onclick="cerrarModalAgregarInventario()"
                        class="px-4 py-2 text-sm font-medium text-slate-600 hover:bg-slate-50 rounded-lg transition-colors">Cancelar</button>
                    <button type="submit"
                        class="bg-primary text-white px-4 py-2 rounded-lg text-sm font-bold hover:bg-primary-dark transition-colors shadow-sm">Guardar
                        Registro</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div id="modalEditarInventario" class="hidden relative z-50">
    <div class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm"></div>
    <div class="fixed inset-0 z-10 flex items-center justify-center p-4">
        <div class="bg-white rounded-xl shadow-xl w-full max-w-2xl p-6 ring-1 ring-slate-200">
            <div class="flex items-center justify-between mb-5 border-b border-slate-100 pb-4">
                <h3 class="text-lg font-bold text-slate-900 flex items-center gap-2">
                    <span class="material-symbols-outlined text-primary">edit</span> Editar Animal
                </h3>
                <button type="button" onclick="cerrarModalEditarInventario()"
                    class="text-slate-400 hover:text-red-500 transition-colors">
                    <span class="material-symbols-outlined">close</span>
                </button>
            </div>

            <form id="formEditarInventario" method="POST" enctype="multipart/form-data" class="space-y-4">
                @csrf
                @method('PUT')
                <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Foto del Animal</label>
                        <input type="file" name="foto" accept="image/*"
                            class="block w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-primary/10 file:text-primary hover:file:bg-primary/20 transition-colors">
                        <p class="text-[11px] text-blue-600 mt-1 flex items-center gap-1"><span
                                class="material-symbols-outlined text-[14px]">info</span> *La foto actual se conserva si
                            dejas esto vacío.</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">ID Arete (Oficial)</label>
                        <input type="text" id="editArete" name="arete" required
                            class="w-full rounded-lg border-slate-200 text-sm focus:ring-primary focus:border-primary">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Nombre o Alias</label>
                        <input type="text" id="editNombre" name="nombre"
                            class="w-full rounded-lg border-slate-200 text-sm focus:ring-primary focus:border-primary">
                    </div>
                </div>
                <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Raza</label>
                        <select id="editRaza" name="raza" required
                            class="w-full rounded-lg border-slate-200 text-sm focus:ring-primary focus:border-primary">
                            <option value="holstein">Holstein</option>
                            <option value="jersey">Jersey</option>
                            <option value="angus">Angus</option>
                            <option value="highland">Highland</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Sexo</label>
                        <div class="flex items-center gap-4 mt-2">
                            <label class="flex items-center gap-2 text-sm text-slate-700">
                                <input type="radio" id="editSexoHembra" name="sexo" value="hembra"
                                    class="text-primary focus:ring-primary"> Hembra
                            </label>
                            <label class="flex items-center gap-2 text-sm text-slate-700">
                                <input type="radio" id="editSexoMacho" name="sexo" value="macho"
                                    class="text-primary focus:ring-primary"> Macho
                            </label>
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Fecha de Nacimiento</label>
                        <input type="date" id="editFecha" name="fecha_nacimiento"
                            class="w-full rounded-lg border-slate-200 text-sm focus:ring-primary focus:border-primary">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Estado</label>
                        <select id="editEstado" name="estado" required
                            class="w-full rounded-lg border-slate-200 text-sm focus:ring-primary focus:border-primary">
                            <option value="becerro">Becerro/a (Cría)</option>
                            <option value="novilla">Novilla</option>
                            <option value="lactante">Lactante (Producción)</option>
                            <option value="seca">Seca</option>
                            <option value="toro">Toro (Semental)</option>
                        </select>
                    </div>
                </div>

                <div class="pt-4 flex justify-end gap-3 border-t border-slate-100 mt-4">
                    <button type="button" onclick="cerrarModalEditarInventario()"
                        class="px-4 py-2 text-sm text-slate-600 hover:bg-slate-50 rounded-lg transition-colors">Cancelar</button>
                    <button type="submit"
                        class="bg-primary text-white px-4 py-2 rounded-lg text-sm font-bold shadow-sm">Guardar
                        Cambios</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div id="modalEliminarInventario" class="hidden relative z-[100]" aria-labelledby="modal-title" role="dialog"
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
                        <h3 class="text-lg font-bold text-slate-900" id="modal-title">¿Eliminar Animal?</h3>
                        <p class="mt-2 text-sm text-slate-500">Esta acción borrará permanentemente a este animal del
                            inventario. No se puede deshacer.</p>
                    </div>
                </div>
                <div class="bg-slate-50 px-6 py-4 flex flex-col sm:flex-row-reverse gap-3">
                    <form id="formEliminarInventario" method="POST" class="w-full sm:w-auto m-0 p-0">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                            class="inline-flex w-full justify-center rounded-lg bg-red-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-red-500 sm:w-auto transition-colors">
                            Sí, eliminar
                        </button>
                    </form>
                    <button type="button" onclick="cerrarModalEliminarInventario()"
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
// AGREGAR
function abrirModalAgregarInventario() {
    document.getElementById('modalAgregarInventario').classList.remove('hidden');
}

function cerrarModalAgregarInventario() {
    document.getElementById('modalAgregarInventario').classList.add('hidden');
}

// EDITAR
function abrirModalEditarInventario(id, arete, nombre, raza, sexo, fecha, estado) {
    document.getElementById('formEditarInventario').action = "{{ url('inventario') }}/" + id;

    document.getElementById('editArete').value = arete;
    document.getElementById('editNombre').value = nombre;

    if (raza) document.getElementById('editRaza').value = raza;
    if (estado) document.getElementById('editEstado').value = estado;
    if (fecha) document.getElementById('editFecha').value = fecha;

    if (sexo === 'macho') {
        document.getElementById('editSexoMacho').checked = true;
    } else {
        document.getElementById('editSexoHembra').checked = true;
    }

    document.getElementById('modalEditarInventario').classList.remove('hidden');
}

function cerrarModalEditarInventario() {
    document.getElementById('modalEditarInventario').classList.add('hidden');
}

// ELIMINAR
function abrirModalEliminarInventario(id) {
    document.getElementById('formEliminarInventario').action = "{{ url('inventario') }}/" + id;
    document.getElementById('modalEliminarInventario').classList.remove('hidden');
}

function cerrarModalEliminarInventario() {
    document.getElementById('modalEliminarInventario').classList.add('hidden');
}
</script>
@endsection