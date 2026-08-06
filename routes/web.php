<?php
use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SireController;
use App\Http\Controllers\RegistroMovimientoController;
use App\Http\Controllers\ConfiguracionController;
use App\Http\Controllers\OrdenPagoController;

// --- RUTAS DEL SISTEMA SIRE ---

// Rutas de Login (Públicas)
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::get('/consultar-tramite', [OrdenPagoController::class, 'consultarEstado'])->name('ordenes-pago.consulta-publica');


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


    Route::get('/configuracion', [ConfiguracionController::class, 'index'])->name('configuracion.index');
    Route::post('/configuracion/general', [ConfiguracionController::class, 'updateGeneral'])->name('configuracion.updateGeneral');
    Route::post('/configuracion/registrador', [ConfiguracionController::class, 'cambiarRegistrador'])->name('configuracion.cambiarRegistrador');


    
    // Ruta AJAX para el cálculo de aranceles
    Route::get('/ordenes-pago/calcular-ajax', [OrdenPagoController::class, 'calcularAjax'])->name('ordenes-pago.calcularAjax');
    
    // Ruta PUT para registrar la factura de Tesorería desde el Modal
    Route::put('/ordenes-pago/{id}/factura', [OrdenPagoController::class, 'registrarFactura'])->name('ordenes-pago.registrarFactura');
    
    // Rutas Resource estándar (index, create, store, show, edit, update, destroy)
    Route::resource('ordenes-pago', OrdenPagoController::class);

    Route::put('/ordenes-pago/{id}/devolver', [OrdenPagoController::class, 'devolverTramite'])->name('ordenes-pago.devolver');

// Ruta para cambiar el estado de la orden
    Route::put('/ordenes-pago/{id}/estado', [OrdenPagoController::class, 'cambiarEstado'])->name('ordenes-pago.cambiarEstado');

});

//Route::get('/registrar-movimiento', [RegistroMovimientoController::class, 'indexregistrar'])->name('sire.registrar');
//Route::get('/movimientos/registrar/{ficha}', [RegistroMovimientoController::class, 'indexregistrar'])->name('sire.registrar');



//Route::post('/registrar-movimiento', [RegistroMovimientoController::class, 'storeMovimiento'])->name('sire.guardar');


    //  SOLUCIÓN: Cambia el orden o haz las URLs distintas
Route::post('/movimiento/guardar', [RegistroMovimientoController::class, 'storeMovimiento'])->name('sire.storeMovimiento');

Route::post('/movimientos/preview', [RegistroMovimientoController::class, 'preview'])->name('movimientos.preview');

// Deja la ruta dinámica abajo, o cámbiale el prefijo
Route::get('/movimiento/registrar/{fichaNum}', [RegistroMovimientoController::class, 'indexregistrar'])->name('sire.registrar');
