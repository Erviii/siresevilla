<?php
use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SireController;
use App\Http\Controllers\RegistroMovimientoController;

// --- RUTAS DEL SISTEMA SIRE ---

// Rutas de Login (Públicas)
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');


Route::middleware('auth')->group(function () {
    
    // Ruta para cerrar sesión
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');


Route::get('/dashboard', [SireController::class, 'indexDashboard'])->name('sire.dashboard');

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

//Route::get('/registrar-movimiento', [RegistroMovimientoController::class, 'indexregistrar'])->name('sire.registrar');
//Route::get('/movimientos/registrar/{ficha}', [RegistroMovimientoController::class, 'indexregistrar'])->name('sire.registrar');



//Route::post('/registrar-movimiento', [RegistroMovimientoController::class, 'storeMovimiento'])->name('sire.guardar');


    //  SOLUCIÓN: Cambia el orden o haz las URLs distintas
Route::post('/movimiento/guardar', [RegistroMovimientoController::class, 'storeMovimiento'])->name('sire.storeMovimiento');

// Deja la ruta dinámica abajo, o cámbiale el prefijo
Route::get('/movimiento/registrar/{fichaNum}', [RegistroMovimientoController::class, 'indexregistrar'])->name('sire.registrar');