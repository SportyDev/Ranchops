@extends('layouts.admin')

@section('titulo', 'Configuración')
@section('titulo_pagina', 'Configuración del Sistema')
@section('subtitulo_pagina', 'Gestión de usuarios, permisos y preferencias de la granja.')

@section('contenido')
<div class="max-w-6xl">
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <div class="lg:col-span-2 space-y-6">

            <div class="bg-surface-light rounded-xl shadow-sm ring-1 ring-slate-200 overflow-hidden">
                <div class="px-6 py-5 border-b border-slate-100 flex items-center justify-between">
                    <div>
                        <h3 class="text-lg font-bold text-slate-900">Usuarios del Sistema</h3>
                        <p class="text-sm text-slate-500">Administra quién tiene acceso a la App Móvil y a la Web.</p>
                    </div>
                    <button onclick="abrirModalNuevoUsuario()"
                        class="rounded-lg bg-primary px-4 py-2 text-sm font-medium text-white hover:bg-primary-dark transition-colors flex items-center gap-2">
                        <span class="material-symbols-outlined text-[18px]">person_add</span> Nuevo Usuario
                    </button>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm text-slate-600">
                        <thead class="bg-slate-50 text-xs uppercase text-slate-500 border-b border-slate-100">
                            <tr>
                                <th class="px-6 py-3 font-medium">Nombre</th>
                                <th class="px-6 py-3 font-medium">Rol / Acceso</th>
                                <th class="px-6 py-3 font-medium">Estado</th>
                                <th class="px-6 py-3 font-medium text-right">Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @foreach($usuarios as $usuario)
                            <tr class="hover:bg-slate-50 transition-colors">
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div
                                            class="flex h-8 w-8 items-center justify-center rounded-full bg-primary/10 font-bold text-primary">
                                            {{ strtoupper(substr($usuario->name, 0, 1)) }}
                                        </div>
                                        <div class="flex flex-col">
                                            <span class="font-bold text-slate-900">{{ $usuario->name }}</span>
                                            <span class="text-xs text-slate-500">{{ $usuario->email }}</span>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    @if($usuario->role === 'admin')
                                    <span
                                        class="inline-flex items-center text-[10px] font-bold text-white bg-primary px-2 py-0.5 rounded-md uppercase tracking-wide">Administrador</span>
                                    @elseif($usuario->role === 'veterinario')
                                    <span
                                        class="inline-flex items-center text-[10px] font-bold text-amber-900 bg-amber-400 px-2 py-0.5 rounded-md uppercase tracking-wide">Veterinario</span>
                                    @else
                                    <span
                                        class="inline-flex items-center text-[10px] font-bold text-blue-700 bg-blue-100 px-2 py-0.5 rounded-md uppercase tracking-wide">Vaquero</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    <span
                                        class="inline-flex items-center gap-1 text-xs font-medium text-emerald-700 bg-emerald-50 px-2 py-1 rounded-md">
                                        <span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span>
                                        Activo
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-right flex justify-end gap-2">
                                    <button
                                        onclick="abrirModalEditar('{{ $usuario->id }}', '{{ $usuario->name }}', '{{ $usuario->email }}', '{{ $usuario->role }}')"
                                        class="text-slate-400 hover:text-primary transition-colors p-1">
                                        <span class="material-symbols-outlined text-[20px]">edit</span>
                                    </button>

                                    @if($usuario->role !== 'admin')
                                    <form action="{{ route('usuarios.destroy', $usuario->id) }}" method="POST"
                                        onsubmit="return confirm('¿Seguro que quieres eliminar este usuario?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            class="text-slate-400 hover:text-red-500 transition-colors">
                                            <span class="material-symbols-outlined text-[20px]">delete</span>
                                        </button>
                                    </form>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="bg-surface-light rounded-xl shadow-sm ring-1 ring-slate-200 p-6">
                <h3 class="text-lg font-bold text-slate-900 mb-4">Variables de Negocio</h3>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Precio de venta de Leche (Por
                            Litro)</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none"><span
                                    class="text-slate-500 sm:text-sm">$</span></div>
                            <input type="number" value="10.50"
                                class="block w-full pl-7 pr-3 py-2 border border-slate-200 rounded-lg text-sm focus:ring-2 focus:ring-primary/50 text-slate-900">
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Unidad de Peso</label>
                        <select
                            class="block w-full px-3 py-2 border border-slate-200 rounded-lg text-sm focus:ring-2 focus:ring-primary/50 text-slate-900">
                            <option value="kg" selected>Kilogramos (kg)</option>
                            <option value="lbs">Libras (lbs)</option>
                        </select>
                    </div>
                </div>
                <div class="mt-4 flex justify-end">
                    <button
                        class="rounded-lg bg-slate-100 px-4 py-2 text-sm font-medium text-slate-700 hover:bg-slate-200 transition-colors">Guardar
                        Cambios</button>
                </div>
            </div>

        </div>
    </div>
    <div id="modalEditarUsuario" class="hidden relative z-50">
        <div class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm"></div>
        <div class="fixed inset-0 z-10 flex items-center justify-center p-4">
            <div class="bg-white rounded-xl shadow-xl w-full max-w-md p-6">
                <h3 class="text-lg font-bold mb-4" id="tituloEditar">Editar Usuario</h3>

                <form id="formEditar" method="POST" class="space-y-4">
                    @csrf
                    @method('PUT')

                    <div>
                        <label class="block text-xs font-medium text-slate-500 mb-1">Nombre</label>
                        <input type="text" id="editNombre" name="name" required
                            class="w-full rounded-lg border-slate-200 text-sm">
                    </div>

                    <div>
                        <label class="block text-xs font-medium text-slate-500 mb-1">Correo Electrónico</label>
                        <input type="email" id="editEmail" name="email" required
                            class="w-full rounded-lg border-slate-200 text-sm">
                    </div>

                    <div>
                        <label class="block text-xs font-medium text-slate-500 mb-1">Nueva Contraseña (Opcional)</label>
                        <input type="password" id="editPassword" name="password"
                            placeholder="Dejar en blanco para no cambiar"
                            class="w-full rounded-lg border-slate-200 text-sm">
                    </div>

                    <div id="divRolEditar">
                        <label class="block text-xs font-medium text-slate-500 mb-1">Nivel de Acceso</label>
                        <select id="editRol" name="role" class="w-full rounded-lg border-slate-200 text-sm">
                            <option value="vaquero">Vaquero</option>
                            <option value="veterinario">Veterinario</option>
                            <option value="admin">Administrador</option>
                        </select>
                    </div>

                    <div class="flex justify-end gap-3 pt-4 border-t border-slate-100 mt-2">
                        <button type="button" onclick="cerrarModalEditar()"
                            class="text-sm text-slate-600 px-4 py-2 hover:bg-slate-50 rounded-lg">Cancelar</button>
                        <button type="submit"
                            class="bg-primary text-white px-4 py-2 rounded-lg text-sm font-bold shadow-sm">Guardar
                            Cambios</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div id="modalNuevoUsuario" class="hidden relative z-50">
        <div class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm"></div>
        <div class="fixed inset-0 z-10 flex items-center justify-center p-4">
            <div class="bg-white rounded-xl shadow-xl w-full max-w-md p-6">
                <h3 class="text-lg font-bold mb-4">Registrar Nuevo Usuario</h3>
                <form action="{{ route('usuarios.store') }}" method="POST" class="space-y-4">
                    @csrf
                    <input type="text" name="name" placeholder="Nombre completo" required
                        class="w-full rounded-lg border-slate-200 text-sm">
                    <input type="email" name="email" placeholder="Correo electrónico" required
                        class="w-full rounded-lg border-slate-200 text-sm">
                    <input type="password" name="password" placeholder="Contraseña (mín. 8 caracteres)" required
                        class="w-full rounded-lg border-slate-200 text-sm">
                    <select name="role" class="w-full rounded-lg border-slate-200 text-sm">
                        <option value="vaquero">Vaquero</option>
                        <option value="veterinario">Veterinario</option>
                        <option value="admin">Administrador</option>
                    </select>
                    <div class="flex justify-end gap-3 pt-4">
                        <button type="button" onclick="cerrarModalNuevo()"
                            class="text-sm text-slate-600">Cancelar</button>
                        <button type="submit" class="bg-primary text-white px-4 py-2 rounded-lg text-sm font-bold">Crear
                            Usuario</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

</div>
@endsection

@section('scripts')
<script>
function abrirModalEditar(id, nombre, email, rol) {
    // 1. Llenamos los datos
    document.getElementById('editNombre').value = nombre;
    document.getElementById('editEmail').value = email;
    document.getElementById('editPassword').value = ''; // Siempre limpio por seguridad

    // 2. Apuntamos el formulario al usuario correcto
    document.getElementById('formEditar').action = "/configuracion/usuarios/" + id;

    // 3. Lógica para ocultar el rol si es Admin
    const divRol = document.getElementById('divRolEditar');
    const selectRol = document.getElementById('editRol');

    if (rol === 'admin') {
        divRol.classList.add('hidden'); // Ocultamos la caja
        selectRol.disabled = true; // Lo deshabilitamos para que no se envíe en el formulario
    } else {
        divRol.classList.remove('hidden'); // Lo mostramos
        selectRol.disabled = false; // Lo habilitamos
        selectRol.value = rol; // Seleccionamos su rol actual
    }

    // 4. Mostramos el modal
    document.getElementById('modalEditarUsuario').classList.remove('hidden');
}

function cerrarModalEditar() {
    document.getElementById('modalEditarUsuario').classList.add('hidden');
}

function abrirModalNuevoUsuario() {
    document.getElementById('modalNuevoUsuario').classList.remove('hidden');
}

function cerrarModalNuevo() {
    document.getElementById('modalNuevoUsuario').classList.add('hidden');
}

function cerrarModalRol() {
    document.getElementById('modalCambiarRol').classList.add('hidden');
}
</script>
@endsection