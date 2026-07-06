<?php
use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SireController;

// --- RUTAS DEL SISTEMA SIRE ---

// Rutas de Login (Públicas)
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');


Route::middleware('auth')->group(function () {
    
    // Ruta para cerrar sesión
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// 1. Mostrar el buscador
Route::get('/sire', [SireController::class, 'index'])->name('sire.index');

// 2. Procesar búsqueda por cédula
Route::get('/sire/buscar/cedula', [SireController::class, 'buscarPorCedula'])->name('sire.buscar.cedula');

// 3. Procesar búsqueda por ficha (¡ESTA ES LA QUE CAUSA EL ERROR!)
Route::get('/sire/buscar/ficha', [SireController::class, 'buscarPorFicha'])->name('sire.buscar.ficha');

// 4. Ruta para imprimir el PDF
Route::get('/sire/reporte/ficha/{ficha}', [SireController::class, 'imprimirReporteFicha'])->name('sire.imprimir.ficha');

Route::get('/sire/buscar-nombre', [SireController::class, 'buscarNombre'])->name('sire.buscar.nombre');

});