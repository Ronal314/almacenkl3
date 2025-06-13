<?php

use App\Http\Controllers\CategoriaController;
use App\Http\Controllers\IngresoController;
use App\Http\Controllers\ProductoController;
use App\Http\Controllers\ProveedorController;
use App\Http\Controllers\SalidaController;
use App\Http\Controllers\UnidadController;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ReporteController;

Route::get('/', function () {
    return redirect('/login');
});

// Categorias
Route::resource('almacen/categorias', CategoriaController::class)->except(['destroy', 'show'])->middleware('auth');
Route::post('/categorias/{id}/toggle', [CategoriaController::class, 'toggleEstado'])->name('categorias.toggle');

// Productos
Route::resource('almacen/productos', ProductoController::class)->except(['destroy', 'show'])->middleware('auth');
Route::post('/productos/{id}/toggle', [ProductoController::class, 'toggleEstado'])->name('productos.toggle');
Route::get('/productos/{idProducto}/lotes', [SalidaController::class, 'getLotesDisponibles']);
Route::get('/almacen/productos/generar-codigo/{id_categoria}', [ProductoController::class, 'generarCodigo'])->name('productos.generar-codigo');

// Proveedores
Route::resource('almacen/proveedores', ProveedorController::class)->except(['destroy', 'show'])->middleware('auth');
Route::post('/proveedores/{id}/toggle', [ProveedorController::class, 'toggleEstado'])->name('proveedores.toggle');

// Unidades
Route::resource('almacen/unidades', UnidadController::class)->except(['destroy', 'show'])->middleware('auth');
Route::post('/unidades/{id}/toggle', [UnidadController::class, 'toggleEstado'])->name('unidades.toggle');

// Ingresos
Route::resource('almacen/ingresos', IngresoController::class)->except(['destroy', 'update', 'edit'])->middleware('auth');

// Salidas
Route::resource('almacen/salidas', SalidaController::class)->except(['destroy', 'update', 'edit'])->middleware('auth');
Route::get('/reporte-salida/{id}', [SalidaController::class, 'imprimirSalidaPDF'])->name('reporte-salida')->middleware('auth');

// Usuarios
// Route::resource('almacen/usuarios', UsuarioController::class)->middleware('auth');
Route::get('/usuarios/change-password', [UsuarioController::class, 'showChangePasswordForm'])->name('usuarios.change-password')->middleware('auth');
Route::post('/usuarios/update-password', [UsuarioController::class, 'updatePassword'])->name('usuarios.update-password')->middleware('auth');

// Dashboard
Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard')->middleware('auth');

// Reportes
Route::get('/movimientos', [ReporteController::class, 'movimientoAlmacen'])->name('movimientos')->middleware('auth');
Route::get('/movimientos/imprimir', [ReporteController::class, 'imprimirMovimientoPDF'])->name('movimientos.imprimir')->middleware('auth');
Route::get('/saldo-almacen', [ReporteController::class, 'saldoAlmacen'])->name('saldo')->middleware('auth');
Route::get('/saldo-almacen/imprimir', [ReporteController::class, 'imprimirSaldoPDF'])->name('saldo.imprimir')->middleware('auth');

Auth::routes(['register' => false], ['verify' => false], ['reset' => false]);
