@extends('layouts.admin')

@section('titulo', 'Reportes')
@section('titulo_pagina', 'Reportes y Auditoría')
@section('subtitulo_pagina', 'Generación de documentos y exportación de datos financieros.')

@section('acciones_cabecera')
<button onclick="abrirModalPersonalizado()"
    class="rounded-lg bg-primary px-4 py-2 text-sm font-medium text-white shadow-lg shadow-primary/30 hover:bg-primary-dark transition-colors flex items-center gap-2">
    <span class="material-symbols-outlined text-[20px]">tune</span>
    Reporte Personalizado
</button>
@endsection

@section('contenido')
<h3 class="text-lg font-bold text-slate-900 mb-4">Exportación Rápida</h3>
<div class="grid grid-cols-1 gap-6 sm:grid-cols-3 mb-8">
    <div
        class="flex flex-col rounded-xl bg-surface-light p-6 shadow-sm ring-1 ring-slate-200 hover:shadow-md transition-shadow">
        <div class="flex items-center gap-3 mb-4">
            <div class="bg-primary/10 p-2 rounded-lg text-primary">
                <span class="material-symbols-outlined">view_list</span>
            </div>
            <h4 class="font-bold text-slate-900">Censo de Ganado</h4>
        </div>
        <p class="text-sm text-slate-500 mb-6 flex-1">Listado completo de animales activos, edades, razas y estado
            actual.</p>
        <div class="flex items-center gap-2">
            <a href="{{ route('reportes.exportar_pdf', 'inventario') }}"
                onclick="setTimeout(() => window.location.reload(), 2000)"
                class="flex-1 rounded-md bg-red-50 px-3 py-2 text-sm font-medium text-red-700 hover:bg-red-100 ring-1 ring-inset ring-red-200 flex items-center justify-center gap-2 transition-colors">
                <span class="material-symbols-outlined text-[18px]">picture_as_pdf</span> PDF
            </a>
            <a href="{{ route('reportes.exportar', 'inventario') }}"
                onclick="setTimeout(() => window.location.reload(), 1000)"
                class="flex-1 rounded-md bg-green-50 px-3 py-2 text-sm font-medium text-green-700 hover:bg-green-100 ring-1 ring-inset ring-green-200 flex items-center justify-center gap-2 transition-colors">
                <span class="material-symbols-outlined text-[18px]">table_view</span> Excel
            </a>
        </div>
    </div>

    <div
        class="flex flex-col rounded-xl bg-surface-light p-6 shadow-sm ring-1 ring-slate-200 hover:shadow-md transition-shadow">
        <div class="flex items-center gap-3 mb-4">
            <div class="bg-blue-50 p-2 rounded-lg text-blue-600">
                <span class="material-symbols-outlined">water_drop</span>
            </div>
            <h4 class="font-bold text-slate-900">Producción Lechera</h4>
        </div>
        <p class="text-sm text-slate-500 mb-6 flex-1">Rendimiento, promedios por vaca y totales ordeñados en el rancho.
        </p>
        <div class="flex items-center gap-2">
            <a href="{{ route('reportes.exportar_pdf', 'produccion') }}"
                onclick="setTimeout(() => window.location.reload(), 2000)"
                class="flex-1 rounded-md bg-red-50 px-3 py-2 text-sm font-medium text-red-700 hover:bg-red-100 ring-1 ring-inset ring-red-200 flex items-center justify-center gap-2 transition-colors">
                <span class="material-symbols-outlined text-[18px]">picture_as_pdf</span> PDF
            </a>
            <a href="{{ route('reportes.exportar', 'produccion') }}"
                onclick="setTimeout(() => window.location.reload(), 1000)"
                class="flex-1 rounded-md bg-green-50 px-3 py-2 text-sm font-medium text-green-700 hover:bg-green-100 ring-1 ring-inset ring-green-200 flex items-center justify-center gap-2 transition-colors">
                <span class="material-symbols-outlined text-[18px]">table_view</span> Excel
            </a>
        </div>
    </div>

    <div
        class="flex flex-col rounded-xl bg-surface-light p-6 shadow-sm ring-1 ring-slate-200 hover:shadow-md transition-shadow">
        <div class="flex items-center gap-3 mb-4">
            <div class="bg-amber-50 p-2 rounded-lg text-amber-600">
                <span class="material-symbols-outlined">mixture_med</span>
            </div>
            <h4 class="font-bold text-slate-900">Historial Sanitario</h4>
        </div>
        <p class="text-sm text-slate-500 mb-6 flex-1">Tratamientos activos, vacunas y reporte de gastos médicos
            generados.</p>
        <div class="flex items-center gap-2">
            <a href="{{ route('reportes.exportar_pdf', 'salud') }}"
                onclick="setTimeout(() => window.location.reload(), 2000)"
                class="flex-1 rounded-md bg-red-50 px-3 py-2 text-sm font-medium text-red-700 hover:bg-red-100 ring-1 ring-inset ring-red-200 flex items-center justify-center gap-2 transition-colors">
                <span class="material-symbols-outlined text-[18px]">picture_as_pdf</span> PDF
            </a>
            <a href="{{ route('reportes.exportar', 'salud') }}"
                onclick="setTimeout(() => window.location.reload(), 1000)"
                class="flex-1 rounded-md bg-green-50 px-3 py-2 text-sm font-medium text-green-700 hover:bg-green-100 ring-1 ring-inset ring-green-200 flex items-center justify-center gap-2 transition-colors">
                <span class="material-symbols-outlined text-[18px]">table_view</span> Excel
            </a>
        </div>
    </div>
</div>

<form method="GET" action="{{ route('reportes.index') }}" class="flex items-center justify-between mb-4">
    <h3 class="text-lg font-bold text-slate-900">Archivo Histórico</h3>
    <div class="relative w-full sm:w-80 flex gap-2">
        <div class="relative w-full">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <span class="material-symbols-outlined text-slate-400 text-[20px]">search</span>
            </div>
            <input name="buscar" value="{{ request('buscar') }}"
                class="block w-full pl-10 pr-3 py-2 border-none bg-white rounded-lg text-sm shadow-sm ring-1 ring-slate-200 placeholder-slate-500 focus:ring-2 focus:ring-primary/50 text-slate-900"
                placeholder="Buscar reporte..." type="text" />
        </div>
        <button type="submit" class="bg-primary text-white px-3 rounded-lg text-sm font-medium">Buscar</button>
        @if(request('buscar'))
        <a href="{{ route('reportes.index') }}"
            class="bg-slate-200 text-slate-700 px-3 py-2 rounded-lg text-sm font-medium">Limpiar</a>
        @endif
    </div>
</form>

<div class="bg-surface-light rounded-xl shadow-sm ring-1 ring-slate-200 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left text-sm text-slate-600">
            <thead class="border-b border-slate-100 bg-slate-50 text-xs uppercase text-slate-500">
                <tr>
                    <th class="px-6 py-4 font-medium">Fecha de Generación</th>
                    <th class="px-6 py-4 font-medium">Nombre del Documento</th>
                    <th class="px-6 py-4 font-medium">Categoría</th>
                    <th class="px-6 py-4 font-medium">Formato</th>
                    <th class="px-6 py-4 font-medium">Creado por</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($historial as $rep)
                <tr class="hover:bg-slate-50/50 transition-colors">
                    <td class="px-6 py-3 whitespace-nowrap text-slate-900">
                        {{ $rep->created_at->format('d M Y, h:i A') }}</td>
                    <td class="px-6 py-3 whitespace-nowrap font-medium text-slate-900">{{ $rep->nombre_documento }}</td>
                    <td class="px-6 py-3 whitespace-nowrap">{{ $rep->categoria }}</td>
                    <td class="px-6 py-3 whitespace-nowrap">
                        <span
                            class="inline-flex items-center gap-1 text-xs font-medium text-green-700 bg-green-50 px-2 py-1 rounded-md">
                            {{ $rep->formato }}
                        </span>
                    </td>
                    <td class="px-6 py-3 whitespace-nowrap">{{ $rep->user->name ?? 'Sistema' }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-6 py-8 text-center text-slate-500">
                        <span class="material-symbols-outlined text-4xl mb-2 opacity-50">folder_open</span>
                        <p>Aún no has generado ningún reporte.</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection

@section('modales')
<div id="modalReportePersonalizado" class="hidden relative z-50">
    <div class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm transition-opacity"></div>
    <div class="fixed inset-0 z-10 flex items-center justify-center p-4 overflow-y-auto">
        <div class="bg-white rounded-xl shadow-xl w-full max-w-2xl p-6 ring-1 ring-slate-200 my-8">
            <div class="flex items-center justify-between mb-5 border-b border-slate-100 pb-4">
                <div>
                    <h3 class="text-lg font-bold text-slate-900 flex items-center gap-2">
                        <span class="material-symbols-outlined text-primary">analytics</span> Constructor de Reportes
                    </h3>
                    <p class="text-xs text-slate-500 mt-1">Configura los filtros exactos para tu exportación de datos.
                    </p>
                </div>
                <button type="button" onclick="cerrarModalPersonalizado()"
                    class="text-slate-400 hover:text-red-500 transition-colors">
                    <span class="material-symbols-outlined">close</span>
                </button>
            </div>

            <form action="{{ route('reportes.personalizado') }}" method="POST" class="space-y-6">
                @csrf

                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">1. Selecciona el Módulo Base</label>
                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
                        <label class="cursor-pointer">
                            <input type="radio" name="modulo" value="inventario" class="peer sr-only" checked>
                            <div
                                class="rounded-lg border border-slate-200 px-3 py-2 text-center hover:bg-slate-50 peer-checked:border-primary peer-checked:bg-primary/5 peer-checked:text-primary transition-all">
                                <span class="material-symbols-outlined block mb-1 text-2xl">view_list</span>
                                <span class="text-xs font-medium">Inventario</span>
                            </div>
                        </label>
                        <label class="cursor-pointer">
                            <input type="radio" name="modulo" value="produccion" class="peer sr-only">
                            <div
                                class="rounded-lg border border-slate-200 px-3 py-2 text-center hover:bg-slate-50 peer-checked:border-blue-600 peer-checked:bg-blue-50 peer-checked:text-blue-700 transition-all">
                                <span class="material-symbols-outlined block mb-1 text-2xl">water_drop</span>
                                <span class="text-xs font-medium">Producción</span>
                            </div>
                        </label>
                        <label class="cursor-pointer">
                            <input type="radio" name="modulo" value="salud" class="peer sr-only">
                            <div
                                class="rounded-lg border border-slate-200 px-3 py-2 text-center hover:bg-slate-50 peer-checked:border-amber-500 peer-checked:bg-amber-50 peer-checked:text-amber-700 transition-all">
                                <span class="material-symbols-outlined block mb-1 text-2xl">mixture_med</span>
                                <span class="text-xs font-medium">Salud</span>
                            </div>
                        </label>
                        <label class="cursor-pointer">
                            <input type="radio" name="modulo" value="reproduccion" class="peer sr-only">
                            <div
                                class="rounded-lg border border-slate-200 px-3 py-2 text-center hover:bg-slate-50 peer-checked:border-purple-600 peer-checked:bg-purple-50 peer-checked:text-purple-700 transition-all">
                                <span class="material-symbols-outlined block mb-1 text-2xl">event</span>
                                <span class="text-xs font-medium">Reproducción</span>
                            </div>
                        </label>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 bg-slate-50 p-4 rounded-lg border border-slate-100">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Fecha Desde:</label>
                        <input type="date" name="fecha_inicio"
                            class="w-full rounded-md border-slate-200 text-sm focus:ring-primary">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Fecha Hasta:</label>
                        <input type="date" name="fecha_fin"
                            class="w-full rounded-md border-slate-200 text-sm focus:ring-primary">
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">2. Filtros Adicionales (Opcional)</label>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <select name="estado_animal"
                                class="w-full rounded-lg border-slate-200 text-sm focus:ring-primary text-slate-600">
                                <option value="">Estado del Animal: Cualquiera</option>
                                <option value="En Ordeña">En Ordeña</option>
                                <option value="Seca">Seca</option>
                            </select>
                        </div>
                        <div>
                            <input type="text" name="animal_id" placeholder="Filtrar por ID o Arete..."
                                class="w-full rounded-lg border-slate-200 text-sm focus:ring-primary text-slate-600">
                        </div>
                    </div>
                </div>

                <div class="border-t border-slate-100 pt-4">
                    <label class="block text-sm font-bold text-slate-700 mb-2">3. Formato de Salida</label>
                    <div class="flex gap-4">
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="radio" name="formato" value="pdf" class="text-red-600 focus:ring-red-600"
                                checked>
                            <span class="text-sm font-medium text-slate-700 flex items-center gap-1"><span
                                    class="material-symbols-outlined text-[18px] text-red-500">picture_as_pdf</span>
                                Documento PDF</span>
                        </label>
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="radio" name="formato" value="excel"
                                class="text-green-600 focus:ring-green-600">
                            <span class="text-sm font-medium text-slate-700 flex items-center gap-1"><span
                                    class="material-symbols-outlined text-[18px] text-green-600">table_view</span> Hoja
                                de Excel (CSV)</span>
                        </label>
                    </div>
                </div>

                <div class="pt-4 flex justify-end gap-3 border-t border-slate-200">
                    <button type="button" onclick="cerrarModalPersonalizado()"
                        class="px-4 py-2 text-sm text-slate-600 hover:bg-slate-50 rounded-lg font-medium transition-colors">Cancelar</button>
                    <button type="submit"
                        onclick="setTimeout(() => window.location.reload(), 2000); cerrarModalPersonalizado();"
                        class="bg-primary text-white px-6 py-2 rounded-lg text-sm font-bold shadow-sm hover:bg-primary-dark transition-colors flex items-center gap-2">
                        <span class="material-symbols-outlined text-[18px]">download</span> Generar y Descargar
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection


@section('scripts')
<script>
function abrirModalPersonalizado() {
    document.getElementById('modalReportePersonalizado').classList.remove('hidden');
}

function cerrarModalPersonalizado() {
    document.getElementById('modalReportePersonalizado').classList.add('hidden');
}
</script>
@endsection