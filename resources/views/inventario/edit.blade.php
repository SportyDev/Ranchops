@extends('layouts.admin')

@section('titulo', 'Editar Animal')
@section('titulo_pagina', 'Editar Registro')
@section('subtitulo_pagina', 'Modificando los datos del animal: ' . $animal->arete)

@section('contenido')
    <div class="bg-surface-light rounded-xl shadow-sm ring-1 ring-slate-200 p-6 max-w-3xl">
        
        <form action="{{ route('inventario.update', $animal->id) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT') <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">ID Arete</label>
                    <input type="text" name="arete" value="{{ $animal->arete }}" required class="block w-full px-3 py-2 border border-slate-200 rounded-lg text-sm text-slate-900">
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Nombre o Alias</label>
                    <input type="text" name="nombre" value="{{ $animal->nombre }}" class="block w-full px-3 py-2 border border-slate-200 rounded-lg text-sm text-slate-900">
                </div>
            </div>

            <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Raza</label>
                    <select name="raza" required class="block w-full px-3 py-2 border border-slate-200 rounded-lg text-sm text-slate-900">
                        <option value="holstein" {{ $animal->raza == 'holstein' ? 'selected' : '' }}>Holstein</option>
                        <option value="jersey" {{ $animal->raza == 'jersey' ? 'selected' : '' }}>Jersey</option>
                        <option value="angus" {{ $animal->raza == 'angus' ? 'selected' : '' }}>Angus</option>
                        <option value="highland" {{ $animal->raza == 'highland' ? 'selected' : '' }}>Highland</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Fecha de Nacimiento</label>
                    <input type="date" name="fecha_nacimiento" value="{{ $animal->fecha_nacimiento }}" class="block w-full px-3 py-2 border border-slate-200 rounded-lg text-sm text-slate-900">
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">Estado</label>
                <select name="estado" required class="block w-full px-3 py-2 border border-slate-200 rounded-lg text-sm text-slate-900">
                    <option value="becerro" {{ $animal->estado == 'becerro' ? 'selected' : '' }}>Becerro/a</option>
                    <option value="novilla" {{ $animal->estado == 'novilla' ? 'selected' : '' }}>Novilla</option>
                    <option value="lactante" {{ $animal->estado == 'lactante' ? 'selected' : '' }}>Lactante</option>
                    <option value="seca" {{ $animal->estado == 'seca' ? 'selected' : '' }}>Seca</option>
                    <option value="toro" {{ $animal->estado == 'toro' ? 'selected' : '' }}>Toro</option>
                </select>
            </div>

            <input type="hidden" name="sexo" value="{{ $animal->sexo }}">

            <div class="flex items-center justify-end gap-3 mt-6 pt-4 border-t border-slate-100">
                <a href="{{ route('inventario.index') }}" class="rounded-lg px-4 py-2 text-sm font-medium text-slate-700 bg-white border border-slate-300 hover:bg-slate-50">
                    Cancelar
                </a>
                <button type="submit" class="rounded-lg bg-primary px-4 py-2 text-sm font-medium text-white hover:bg-primary-dark shadow-sm">
                    Guardar Cambios
                </button>
            </div>
        </form>
    </div>
@endsection