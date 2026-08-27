@extends('layouts.admin')

@section('titulo', 'Finanzas')
@section('titulo_pagina', 'Finanzas y Comercialización')
@section('subtitulo_pagina', 'Balance general, venta de productos y control de gastos operativos.')

@section('acciones_cabecera')
<div class="flex flex-wrap items-center justify-end gap-2 sm:gap-3">
    <button type="button" onclick="abrirModalFinanzas('gasto')"
        class="rounded-lg bg-red-50 text-red-600 px-3 py-2 sm:px-4 sm:py-2 text-xs sm:text-sm font-medium hover:bg-red-100 transition-colors flex items-center gap-1 sm:gap-2 ring-1 ring-inset ring-red-200 whitespace-nowrap">
        <span class="material-symbols-outlined text-[18px] sm:text-[20px]">remove</span>
        Registrar Gasto
    </button>
    <button type="button" onclick="abrirModalFinanzas('ingreso')"
        class="rounded-lg bg-green-600 text-white px-3 py-2 sm:px-4 sm:py-2 text-xs sm:text-sm font-medium shadow-lg shadow-green-600/30 hover:bg-green-700 transition-colors flex items-center gap-1 sm:gap-2 whitespace-nowrap">
        <span class="material-symbols-outlined text-[18px] sm:text-[20px]">add</span>
        Registrar Ingreso
    </button>
</div>
@endsection

@section('contenido')

<style>
.active-tab {
    border-bottom-color: #5E1B22 !important;
    /* Color primary */
    color: #5E1B22 !important;
}

.inactive-tab {
    border-bottom-color: transparent !important;
    color: #64748b !important;
    /* slate-500 */
}

.inactive-tab:hover {
    border-bottom-color: #cbd5e1 !important;
    /* slate-300 */
    color: #334155 !important;
    /* slate-700 */
}
</style>

<h3 class="text-lg font-bold text-slate-900 mb-4">Balance General (Este Mes)</h3>
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">

    <div class="bg-surface-light rounded-xl p-6 shadow-sm ring-1 ring-slate-200 border-l-4 border-l-green-500">
        <p class="text-sm font-medium text-slate-500 mb-1">Ingresos Totales</p>
        <div class="flex items-baseline gap-2">
            <h4 class="text-3xl font-bold text-slate-900">${{ number_format($ingresosTotales ?? 0, 2) }}</h4>
            <span class="text-sm text-slate-500">MXN</span>
        </div>
        <div class="mt-4 flex flex-col gap-1 text-xs text-slate-500">
            <div class="flex justify-between"><span>Leche:</span> <span
                    class="font-medium text-slate-700">${{ number_format($ingresosLeche ?? 0, 2) }}</span></div>
            <div class="flex justify-between"><span>Venta Ganado:</span> <span
                    class="font-medium text-slate-700">${{ number_format($ingresosGanado ?? 0, 2) }}</span></div>
        </div>
    </div>

    <div class="bg-surface-light rounded-xl p-6 shadow-sm ring-1 ring-slate-200 border-l-4 border-l-red-500">
        <p class="text-sm font-medium text-slate-500 mb-1">Gastos Operativos</p>
        <div class="flex items-baseline gap-2">
            <h4 class="text-3xl font-bold text-slate-900">${{ number_format($gastosTotales ?? 0, 2) }}</h4>
            <span class="text-sm text-slate-500">MXN</span>
        </div>
        <div class="mt-4 flex flex-col gap-1 text-xs text-slate-500">
            <div class="flex justify-between"><span>Alimento:</span> <span
                    class="font-medium text-slate-700">${{ number_format($gastosAlimento ?? 0, 2) }}</span></div>
            <div class="flex justify-between"><span>Veterinaria:</span> <span
                    class="font-medium text-slate-700">${{ number_format($gastosVeterinaria ?? 0, 2) }}</span></div>
            <div class="flex justify-between"><span>Nómina/Otros:</span> <span
                    class="font-medium text-slate-700">${{ number_format($gastosOtros ?? 0, 2) }}</span></div>
        </div>
    </div>

    <div
        class="{{ ($utilidad ?? 0) >= 0 ? 'bg-primary/5 ring-primary/20' : 'bg-red-50 ring-red-200' }} rounded-xl p-6 shadow-sm ring-1">
        <p class="text-sm font-bold {{ ($utilidad ?? 0) >= 0 ? 'text-primary' : 'text-red-600' }} mb-1">Utilidad Neta
            (Ganancia)</p>
        <div class="flex items-baseline gap-2">
            <h4 class="text-4xl font-extrabold {{ ($utilidad ?? 0) >= 0 ? 'text-primary' : 'text-red-600' }}">
                ${{ number_format($utilidad ?? 0, 2) }}</h4>
            <span class="text-sm font-bold {{ ($utilidad ?? 0) >= 0 ? 'text-primary/60' : 'text-red-400' }}">MXN</span>
        </div>
        <div class="mt-4 pt-4 border-t {{ ($utilidad ?? 0) >= 0 ? 'border-primary/10' : 'border-red-200' }}">
            <div class="flex justify-between items-center">
                <span class="text-xs font-medium text-slate-600">Margen de ganancia:</span>
                <span
                    class="inline-flex items-center gap-1 rounded-full bg-green-100 px-2 py-1 text-[10px] font-bold text-green-700">
                    <span class="material-symbols-outlined text-[12px]">trending_up</span> Calculando...
                </span>
            </div>
        </div>
    </div>
</div>

<div class="mb-6 border-b border-slate-200 overflow-x-auto">
    <nav class="-mb-px flex gap-6" aria-label="Tabs" id="nav-tabs">
        <button type="button" onclick="filtrarTabla(this, 'todos')"
            class="tab-btn active-tab whitespace-nowrap border-b-2 py-4 px-1 text-sm font-bold flex items-center gap-2 transition-colors">
            <span class="material-symbols-outlined text-[18px]">receipt_long</span> Últimos Movimientos
        </button>
        <button type="button" onclick="filtrarTabla(this, 'leche')"
            class="tab-btn inactive-tab whitespace-nowrap border-b-2 py-4 px-1 text-sm font-medium flex items-center gap-2 transition-colors">
            <span class="material-symbols-outlined text-[18px]">water_drop</span> Ventas de Leche
        </button>
        <button type="button" onclick="filtrarTabla(this, 'ganado')"
            class="tab-btn inactive-tab whitespace-nowrap border-b-2 py-4 px-1 text-sm font-medium flex items-center gap-2 transition-colors">
            <span class="material-symbols-outlined text-[18px]">shopping_cart</span> Venta de Ganado
        </button>
        <button type="button" onclick="filtrarTabla(this, 'gastos')"
            class="tab-btn inactive-tab whitespace-nowrap border-b-2 py-4 px-1 text-sm font-medium flex items-center gap-2 transition-colors">
            <span class="material-symbols-outlined text-[18px]">payments</span> Gastos Operativos
        </button>
    </nav>
</div>

<div class="bg-surface-light rounded-xl shadow-sm ring-1 ring-slate-200 overflow-hidden">

    <div class="p-4 border-b border-slate-100 flex justify-between items-center bg-slate-50/50">
        <h4 class="font-bold text-slate-800 hidden sm:block">Transacciones Recientes</h4>
        <div class="flex gap-2 w-full sm:w-auto">
            <select
                class="flex-1 sm:w-auto rounded-md border-slate-200 text-xs py-1.5 pl-2 pr-8 text-slate-600 focus:ring-primary">
                <option>Todas las categorías</option>
                <option>Solo Ingresos</option>
                <option>Solo Gastos</option>
            </select>
            <button type="button"
                class="rounded-md bg-white px-3 py-1.5 text-xs font-medium text-slate-600 ring-1 ring-inset ring-slate-200 hover:bg-slate-50 transition-colors flex items-center gap-1">
                <span class="material-symbols-outlined text-[16px]">filter_list</span> Filtros
            </button>
        </div>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-left text-sm text-slate-600">
            <thead class="border-b border-slate-100 bg-slate-50 text-xs uppercase text-slate-500">
                <tr>
                    <th class="px-6 py-3 font-medium">Fecha</th>
                    <th class="px-6 py-3 font-medium">Tipo</th>
                    <th class="px-6 py-3 font-medium">Concepto / Categoría</th>
                    <th class="px-6 py-3 font-medium">Registrado por</th>
                    <th class="px-6 py-3 font-medium text-right">Monto</th>
                </tr>
            </thead>

            <tbody class="divide-y divide-slate-100">
                @forelse($movimientos ?? [] as $mov)

                @php
                $claseFiltro = 'todos';
                if($mov->categoria == 'Leche') { $claseFiltro .= ' leche'; }
                elseif($mov->categoria == 'Venta Ganado') { $claseFiltro .= ' ganado'; }
                elseif($mov->tipo == 'gasto') { $claseFiltro .= ' gastos'; }
                @endphp

                <tr class="fila-movimiento {{ $claseFiltro }} hover:bg-slate-50/50 transition-colors">
                    <td class="px-6 py-4 whitespace-nowrap text-slate-900">
                        {{ \Carbon\Carbon::parse($mov->fecha)->format('d M Y') }}</td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        @if($mov->tipo == 'ingreso')
                        <span
                            class="inline-flex items-center gap-1 rounded-md bg-green-50 px-2 py-1 text-xs font-medium text-green-700 ring-1 ring-inset ring-green-600/20">
                            <span class="material-symbols-outlined text-[14px]">arrow_downward</span> Ingreso
                        </span>
                        @else
                        <span
                            class="inline-flex items-center gap-1 rounded-md bg-red-50 px-2 py-1 text-xs font-medium text-red-700 ring-1 ring-inset ring-red-600/10">
                            <span class="material-symbols-outlined text-[14px]">arrow_upward</span> Gasto
                        </span>
                        @endif
                    </td>
                    <td class="px-6 py-4">
                        <div class="font-medium text-slate-900">{{ $mov->descripcion }}</div>
                        <div class="text-xs text-slate-500">Categoría: {{ $mov->categoria }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-slate-500">{{ $mov->user->name ?? 'Sistema' }}</td>
                    <td
                        class="px-6 py-4 whitespace-nowrap text-right font-bold {{ $mov->tipo == 'ingreso' ? 'text-green-600' : 'text-slate-900' }}">
                        {{ $mov->tipo == 'ingreso' ? '+' : '-' }}${{ number_format($mov->monto, 2) }}
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-6 py-8 text-center text-slate-500">
                        <span class="material-symbols-outlined text-4xl mb-2 opacity-50">account_balance_wallet</span>
                        <p>Aún no hay transacciones registradas este mes.</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if(count($movimientos ?? []) > 0)
    <div class="p-4 border-t border-slate-100 flex items-center justify-center bg-slate-50/50">
        <button type="button" class="text-sm font-medium text-primary hover:text-primary-dark transition-colors">Cargar
            más movimientos...</button>
    </div>
    @endif
</div>

@endsection

@section('modales')
<div id="modalFinanzas" class="hidden relative z-[100]" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm transition-opacity"></div>
    <div class="fixed inset-0 z-10 w-screen overflow-y-auto">
        <div class="flex min-h-full items-center justify-center p-4 text-center sm:p-0">
            <div
                class="relative transform overflow-hidden rounded-xl bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-md ring-1 ring-slate-200 p-6">

                <div class="flex items-center justify-between mb-5 border-b border-slate-100 pb-4">
                    <h3 class="text-lg font-bold text-slate-900" id="tituloModal">Registrar Movimiento</h3>
                    <button type="button" onclick="cerrarModalFinanzas()"
                        class="text-slate-400 hover:text-red-500 transition-colors p-1">
                        <span class="material-symbols-outlined">close</span>
                    </button>
                </div>

                <form action="{{ route('finanzas.store') }}" method="POST" class="space-y-4 text-left">
                    @csrf
                    <input type="hidden" name="tipo" id="tipoMovimiento" value="">

                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-1">Categoría</label>
                        <select name="categoria" id="selectCategoria"
                            class="w-full rounded-lg border-slate-200 text-sm focus:ring-primary" required>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-1">Concepto / Descripción</label>
                        <input type="text" name="descripcion" placeholder="Ej. Pago Liconsa Quincena 1"
                            class="w-full rounded-lg border-slate-200 text-sm focus:ring-primary" required>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-1">Monto ($)</label>
                            <input type="number" step="0.01" name="monto"
                                class="w-full rounded-lg border-slate-200 text-sm focus:ring-primary" required>
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-1">Fecha</label>
                            <input type="date" name="fecha" value="{{ date('Y-m-d') }}"
                                class="w-full rounded-lg border-slate-200 text-sm focus:ring-primary" required>
                        </div>
                    </div>

                    <div class="pt-4 flex flex-col sm:flex-row-reverse justify-start gap-3 border-t border-slate-200">
                        <button type="submit"
                            class="w-full sm:w-auto bg-primary text-white px-6 py-2 rounded-lg text-sm font-bold hover:bg-primary-dark shadow-sm">Guardar</button>
                        <button type="button" onclick="cerrarModalFinanzas()"
                            class="w-full sm:w-auto px-4 py-2 text-sm text-slate-600 hover:bg-slate-50 rounded-lg font-medium ring-1 ring-inset ring-slate-300">Cancelar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
// FUNCIÓN PARA FILTRAR PESTAÑAS DE LA TABLA
function filtrarTabla(botonPresionado, filtro) {
    // 1. Quitar el color activo de todas las pestañas
    const todasLasPestanas = document.querySelectorAll('.tab-btn');
    todasLasPestanas.forEach(tab => {
        tab.classList.remove('active-tab', 'font-bold');
        tab.classList.add('inactive-tab', 'font-medium');
    });

    // 2. Poner color activo solo a la que se presionó
    botonPresionado.classList.remove('inactive-tab', 'font-medium');
    botonPresionado.classList.add('active-tab', 'font-bold');

    // 3. Ocultar o mostrar las filas
    const todasLasFilas = document.querySelectorAll('.fila-movimiento');
    todasLasFilas.forEach(fila => {
        if (filtro === 'todos' || fila.classList.contains(filtro)) {
            fila.style.display = ''; // Mostrar
        } else {
            fila.style.display = 'none'; // Ocultar
        }
    });
}

// FUNCIONES DEL MODAL
function abrirModalFinanzas(tipo) {
    document.getElementById('modalFinanzas').classList.remove('hidden');
    document.getElementById('tipoMovimiento').value = tipo;

    let select = document.getElementById('selectCategoria');
    select.innerHTML = '';

    if (tipo === 'ingreso') {
        document.getElementById('tituloModal').innerText = 'Registrar Nuevo Ingreso';
        select.innerHTML = `
                <option value="Leche">Venta de Leche</option>
                <option value="Venta Ganado">Venta de Ganado</option>
                <option value="Otros">Otros Ingresos</option>
            `;
    } else {
        document.getElementById('tituloModal').innerText = 'Registrar Nuevo Gasto';
        select.innerHTML = `
                <option value="Alimento">Alimento / Pastura</option>
                <option value="Veterinaria">Medicamentos y Veterinaria</option>
                <option value="Nómina">Sueldos / Nómina</option>
                <option value="Mantenimiento">Mantenimiento (Tractor, Instalaciones)</option>
                <option value="Otros">Otros Gastos</option>
            `;
    }
}

function cerrarModalFinanzas() {
    document.getElementById('modalFinanzas').classList.add('hidden');
}
</script>
@endsection