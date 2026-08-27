<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>@yield('titulo') - Ranchops</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap"
        rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap"
        rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <script id="tailwind-config">
    tailwind.config = {
        darkMode: "class",
        theme: {
            extend: {
                colors: {
                    "primary": "#5E1B22",
                    "primary-dark": "#451218",
                    "background-light": "#f6f8f6",
                    "background-dark": "#102213",
                    "surface-light": "#ffffff",
                    "surface-dark": "#1a331d",
                    "text-main": "#1e293b",
                    "text-muted": "#64748b",
                },
                fontFamily: {
                    "display": ["Inter", "sans-serif"]
                }
            },
        },
    }
    </script>
    <style>
    body {
        font-family: 'Inter', sans-serif;
    }

    ::-webkit-scrollbar {
        width: 8px;
        height: 8px;
    }

    ::-webkit-scrollbar-track {
        background: transparent;
    }

    ::-webkit-scrollbar-thumb {
        background: #cbd5e1;
        border-radius: 4px;
    }

    ::-webkit-scrollbar-thumb:hover {
        background: #94a3b8;
    }

    /* Magia CSS para colapsar sin que nada se empalme */
    #sidebar {
        transition: width 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .collapsed-sidebar {
        width: 72px !important;
    }

    .collapsed-sidebar .nav-text {
        display: none !important;
    }

    .collapsed-sidebar .box-container {
        justify-content: center !important;
        padding-left: 0 !important;
        padding-right: 0 !important;
    }

    .collapsed-sidebar .action-btn {
        position: static !important;
        margin: 0 auto !important;
    }

    .collapsed-sidebar .logout-btn {
        display: none !important;
    }

    /* Ocultamos el logout si está colapsada */
    .collapsed-sidebar .avatar-container {
        margin: 0 auto !important;
    }
    </style>
</head>

<body class="bg-background-light text-text-main antialiased selection:bg-primary selection:text-white overflow-hidden">

    <div id="mobile-overlay" onclick="toggleMobileSidebar()"
        class="fixed inset-0 bg-slate-900/50 z-40 hidden lg:hidden backdrop-blur-sm transition-opacity duration-300 opacity-0">
    </div>

    <div class="relative flex h-screen w-full flex-row overflow-hidden">

        <aside id="sidebar"
            class="fixed inset-y-0 left-0 z-50 flex h-full w-64 flex-col border-r border-slate-200 bg-surface-light transition-all duration-300 ease-in-out transform -translate-x-full lg:relative lg:translate-x-0 flex-shrink-0 overflow-x-hidden w-64">
            <div class="flex h-full flex-col justify-between p-4 relative">

                <div class="flex flex-col gap-6">

                    <div class="flex items-center px-2 py-2 relative box-container min-h-[48px]">
                        <div class="flex items-center gap-3 nav-text">
                            <div
                                class="bg-primary/10 flex h-10 w-10 shrink-0 items-center justify-center rounded-lg text-primary">
                                <span class="material-symbols-outlined text-2xl">agriculture</span>
                            </div>
                            <div class="flex flex-col whitespace-nowrap">
                                <h1 class="text-base font-bold leading-tight text-slate-900">Ranchops</h1>
                                <p class="text-[10px] font-medium text-slate-500">Panel de Control</p>
                            </div>
                        </div>

                        <button type="button" onclick="toggleDesktopSidebar()"
                            class="hidden lg:flex absolute right-0 text-slate-400 hover:text-primary transition-colors p-1 bg-surface-light rounded-md hover:bg-slate-50 z-10 action-btn"
                            title="Expandir/Contraer">
                            <span class="material-symbols-outlined text-[20px]" id="minimize-icon">menu_open</span>
                        </button>

                        <button type="button" onclick="toggleMobileSidebar()"
                            class="lg:hidden absolute right-0 text-slate-400 hover:text-red-500 transition-colors p-1 z-10 nav-text">
                            <span class="material-symbols-outlined">close</span>
                        </button>
                    </div>

                    <nav class="flex flex-col gap-1">
                        <a class="group flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-medium transition-colors {{ request()->is('panel') ? 'bg-primary text-white shadow-md' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}"
                            href="/panel">
                            <span
                                class="material-symbols-outlined shrink-0 {{ request()->is('panel') ? 'filled' : '' }}">bar_chart_4_bars</span>
                            <span class="nav-text whitespace-nowrap">Inicio</span>
                        </a>

                        <a class="group flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-medium transition-colors {{ request()->is('inventario*') ? 'bg-primary text-white shadow-md' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}"
                            href="/inventario">
                            <span
                                class="material-symbols-outlined shrink-0 {{ request()->is('inventario*') ? 'filled' : '' }}">view_list</span>
                            <span class="nav-text whitespace-nowrap">Inventario de Ganado</span>
                        </a>

                        <a class="group flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-medium transition-colors {{ request()->is('produccion*') ? 'bg-primary text-white shadow-md' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}"
                            href="/produccion">
                            <span
                                class="material-symbols-outlined shrink-0 {{ request()->is('produccion*') ? 'filled' : '' }}">water_drop</span>
                            <span class="nav-text whitespace-nowrap">Producción de Leche</span>
                        </a>

                        @if(Auth::user()->role == 'admin' || Auth::user()->role == 'veterinario')
                        <a class="group flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-medium transition-colors {{ request()->is('salud*') ? 'bg-primary text-white shadow-md' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}"
                            href="/salud">
                            <span
                                class="material-symbols-outlined shrink-0 {{ request()->is('salud*') ? 'filled' : '' }}">mixture_med</span>
                            <span class="nav-text whitespace-nowrap">Salud y Veterinaria</span>
                        </a>
                        @endif

                        <a class="group flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-medium transition-colors {{ request()->is('reproduccion*') ? 'bg-primary text-white shadow-md' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}"
                            href="/reproduccion">
                            <span
                                class="material-symbols-outlined shrink-0 {{ request()->is('reproduccion*') ? 'filled' : '' }}">event</span>
                            <span class="nav-text whitespace-nowrap">Reproducción</span>
                        </a>

                        <a class="group flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-medium transition-colors {{ request()->is('finanzas*') ? 'bg-primary text-white shadow-md' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}"
                            href="/finanzas">
                            <span
                                class="material-symbols-outlined shrink-0 {{ request()->is('finanzas*') ? 'filled' : '' }}">payments</span>
                            <span class="nav-text whitespace-nowrap">Finanzas</span>
                        </a>

                        <a class="group flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-medium transition-colors {{ request()->is('reportes*') ? 'bg-primary text-white shadow-md' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}"
                            href="/reportes">
                            <span
                                class="material-symbols-outlined shrink-0 {{ request()->is('reportes*') ? 'filled' : '' }}">description</span>
                            <span class="nav-text whitespace-nowrap">Reportes</span>
                        </a>

                        @if(Auth::user()->role == 'admin')
                        <a class="group flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-medium transition-colors {{ request()->is('configuracion*') ? 'bg-primary text-white shadow-md' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}"
                            href="/configuracion">
                            <span
                                class="material-symbols-outlined shrink-0 {{ request()->is('configuracion*') ? 'filled' : '' }}">settings</span>
                            <span class="nav-text whitespace-nowrap">Configuración</span>
                        </a>
                        @endif
                    </nav>
                </div>

                <div class="mt-auto border-t border-slate-100 pt-4 overflow-hidden relative">
                    <div class="flex items-center px-2 py-2 relative box-container min-h-[48px]">

                        <div
                            class="flex h-9 w-9 items-center justify-center rounded-full bg-primary text-white font-bold shadow-sm shrink-0 avatar-container">
                            {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                        </div>

                        <div class="nav-text flex flex-col overflow-hidden whitespace-nowrap pl-3">
                            <span
                                class="text-sm font-bold text-slate-900 truncate leading-tight">{{ Auth::user()->name }}</span>
                            <span class="text-[11px] text-slate-500 truncate mb-1">{{ Auth::user()->email }}</span>
                            <div>
                                @if(Auth::user()->role == 'admin')
                                <span
                                    class="inline-flex items-center text-[10px] font-bold text-white bg-primary px-2 py-0.5 rounded-md uppercase tracking-wide">Administrador</span>
                                @elseif(Auth::user()->role == 'veterinario')
                                <span
                                    class="inline-flex items-center text-[10px] font-bold text-amber-900 bg-amber-400 px-2 py-0.5 rounded-md uppercase tracking-wide">Veterinario</span>
                                @else
                                <span
                                    class="inline-flex items-center text-[10px] font-bold text-blue-700 bg-blue-100 px-2 py-0.5 rounded-md uppercase tracking-wide">Vaquero</span>
                                @endif
                            </div>
                        </div>

                        <button type="button" onclick="abrirModalLogout()"
                            class="absolute right-0 text-slate-400 hover:text-red-600 transition-colors p-1 bg-surface-light rounded-md z-10 shrink-0 logout-btn"
                            title="Cerrar Sesión">
                            <span class="material-symbols-outlined text-[20px]">logout</span>
                        </button>

                        <form id="logout-form" method="POST" action="{{ route('logout') }}" class="hidden">
                            @csrf
                        </form>
                    </div>
                </div>

            </div>
        </aside>

        <script>
        if (localStorage.getItem('sidebarState') === 'collapsed') {
            document.getElementById('sidebar').classList.add('collapsed-sidebar');
            document.getElementById('minimize-icon').innerText = 'menu';
        }
        </script>

        <main class="flex-1 flex flex-col h-screen overflow-hidden bg-background-light">
            <header
                class="sticky top-0 z-10 flex items-center justify-between bg-surface-light/80 px-4 sm:px-8 py-4 backdrop-blur-md border-b border-slate-200/60">
                <div class="flex items-center gap-4">
                    <button type="button" onclick="toggleMobileSidebar()"
                        class="lg:hidden flex items-center justify-center rounded-md p-2 text-slate-500 hover:text-primary hover:bg-slate-50 transition-colors">
                        <span class="material-symbols-outlined">menu</span>
                    </button>
                    <div>
                        <h2 class="text-xl sm:text-2xl font-bold text-slate-900">@yield('titulo_pagina')</h2>
                        <p class="hidden sm:block text-sm text-slate-500">@yield('subtitulo_pagina')</p>
                    </div>
                </div>
                <div class="flex items-center gap-4">
                    @yield('acciones_cabecera')
                </div>
            </header>

            <div class="flex-1 overflow-y-auto p-4 sm:p-8">
                @yield('contenido')
            </div>
        </main>
    </div>

    <div id="modalLogout" class="hidden relative z-[100]" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm transition-opacity"></div>
        <div class="fixed inset-0 z-10 w-screen overflow-y-auto">
            <div class="flex min-h-full items-center justify-center p-4 text-center sm:p-0">
                <div
                    class="relative transform overflow-hidden rounded-xl bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-sm ring-1 ring-slate-200">
                    <div class="p-6">
                        <div
                            class="mx-auto flex h-12 w-12 items-center justify-center rounded-full bg-red-50 text-red-600 mb-4">
                            <span class="material-symbols-outlined text-2xl">logout</span>
                        </div>
                        <div class="text-center">
                            <h3 class="text-lg font-bold text-slate-900" id="modal-title">¿Cerrar Sesión?</h3>
                            <p class="mt-2 text-sm text-slate-500">¿Estás seguro de que deseas salir de Ranchops?
                                Tendrás que volver a ingresar tus credenciales.</p>
                        </div>
                    </div>
                    <div class="bg-slate-50 px-6 py-4 flex flex-col sm:flex-row-reverse gap-3">
                        <button type="button" onclick="document.getElementById('logout-form').submit();"
                            class="inline-flex w-full justify-center rounded-lg bg-red-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-red-500 sm:w-auto transition-colors">
                            Cerrar Sesión
                        </button>
                        <button type="button" onclick="cerrarModalLogout()"
                            class="inline-flex w-full justify-center rounded-lg bg-white px-4 py-2 text-sm font-semibold text-slate-700 shadow-sm ring-1 ring-inset ring-slate-300 hover:bg-slate-50 sm:w-auto transition-colors">
                            Cancelar
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @yield('modales')
    @yield('scripts')

    <script>
    // Modal de Logout
    function abrirModalLogout() {
        const modal = document.getElementById('modalLogout');
        if (modal) modal.classList.remove('hidden');
    }

    function cerrarModalLogout() {
        const modal = document.getElementById('modalLogout');
        if (modal) modal.classList.add('hidden');
    }

    // Lógica de Sidebar
    function toggleDesktopSidebar() {
        const sidebar = document.getElementById('sidebar');
        const minIcon = document.getElementById('minimize-icon');

        sidebar.classList.toggle('collapsed-sidebar');

        if (sidebar.classList.contains('collapsed-sidebar')) {
            localStorage.setItem('sidebarState', 'collapsed');
            minIcon.innerText = 'menu';
        } else {
            localStorage.setItem('sidebarState', 'expanded');
            minIcon.innerText = 'menu_open';
        }
    }

    function toggleMobileSidebar() {
        const sidebar = document.getElementById('sidebar');
        const overlay = document.getElementById('mobile-overlay');

        if (sidebar.classList.contains('-translate-x-full')) {
            sidebar.classList.remove('-translate-x-full');
            overlay.classList.remove('hidden');
            setTimeout(() => overlay.classList.remove('opacity-0'), 10);
        } else {
            sidebar.classList.add('-translate-x-full');
            overlay.classList.add('opacity-0');
            setTimeout(() => overlay.classList.add('hidden'), 300);
        }
    }
    </script>
</body>

</html>