<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\InventarioController;
use App\Http\Controllers\ConfiguracionController;
use App\Http\Controllers\PanelController;
use App\Http\Controllers\ProduccionController;
use App\Http\Controllers\ReproduccionController;
use App\Http\Controllers\SaludController;
use App\Http\Controllers\ReporteController;
use App\Http\Controllers\FinanzasController;

// Redirigir al login automáticamente al entrar a la raíz de la página
Route::get('/', function () {
    return redirect('/login');
});

// Grupo principal de Ranchops: Solo entras si ya iniciaste sesión
Route::middleware(['auth', 'verified'])->group(function () {
    
    // 1. Inicio / Panel General
   Route::get('/panel', [PanelController::class, 'index'])->name('panel.index');

    // 2. Inventario de Ganado
    Route::get('/inventario', [InventarioController::class, 'index'])->name('inventario.index');
    Route::post('/inventario', [App\Http\Controllers\InventarioController::class, 'store'])->name('inventario.store');
    Route::put('/inventario/{animal}', [InventarioController::class, 'update'])->name('inventario.update');
    Route::get('/inventario/{id}/editar', [\App\Http\Controllers\InventarioController::class, 'edit'])->name('inventario.edit');
    Route::delete('/inventario/{animal}', [InventarioController::class, 'destroy'])->name('inventario.destroy');


    // 3. Producción de Leche
    Route::get('/produccion', [ProduccionController::class, 'index'])->name('produccion.index');
    Route::post('/produccion', [ProduccionController::class, 'store'])->name('produccion.store');
    Route::put('/produccion/{produccion}', [ProduccionController::class, 'update'])->name('produccion.update');
    Route::delete('/produccion/{produccion}', [ProduccionController::class, 'destroy'])->name('produccion.destroy'); // <-- NUEVA

    // 4. Salud y Veterinaria
    Route::get('/salud', [SaludController::class, 'index'])->name('salud.index');
    Route::post('/salud', [SaludController::class, 'store'])->name('salud.store');
    Route::put('/salud/{salud}', [SaludController::class, 'update'])->name('salud.update'); // <-- NUEVA
    Route::delete('/salud/{salud}', [SaludController::class, 'destroy'])->name('salud.destroy');

    // 5. Reproducción y Maternidad
    Route::get('/reproduccion', [ReproduccionController::class, 'index'])->name('reproduccion.index');
    Route::post('/reproduccion', [ReproduccionController::class, 'store'])->name('reproduccion.store');
    Route::put('/reproduccion/{reproduccion}', [ReproduccionController::class, 'update'])->name('reproduccion.update');
    Route::delete('/reproduccion/{reproduccion}', [ReproduccionController::class, 'destroy'])->name('reproduccion.destroy');

   // 6. Reportes
    Route::get('/reportes', [ReporteController::class, 'index'])->name('reportes.index');
    Route::get('/reportes/exportar/{categoria}', [ReporteController::class, 'exportarCsv'])->name('reportes.exportar');
    Route::get('/reportes/exportar/pdf/{categoria}', [ReporteController::class, 'exportarPdf'])->name('reportes.exportar_pdf');
    Route::post('/reportes/personalizado', [ReporteController::class, 'personalizado'])->name('reportes.personalizado');

    // 7. Configuración del sistema
    Route::get('/configuracion', [ConfiguracionController::class, 'index'])->name('configuracion.index');
    Route::put('/configuracion/usuarios/{user}', [ConfiguracionController::class, 'update'])->name('usuarios.update');

    Route::post('/configuracion/usuarios', [ConfiguracionController::class, 'store'])->name('usuarios.store');
    Route::delete('/configuracion/usuarios/{user}', [ConfiguracionController::class, 'destroy'])->name('usuarios.destroy');

    // 8. fiananzas
    Route::get('/finanzas', [FinanzasController::class, 'index'])->name('finanzas.index');
    Route::post('/finanzas/store', [FinanzasController::class, 'store'])->name('finanzas.store');

});

// Rutas del perfil de usuario (Estas las maneja Breeze por defecto)
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Carga las rutas de seguridad (login, registro, contraseñas)
require __DIR__.'/auth.php';