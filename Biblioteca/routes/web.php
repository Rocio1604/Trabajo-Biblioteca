<?php
use App\Http\Controllers\LibrosController;
use App\Http\Controllers\AutoresController;
use App\Http\Controllers\BibliotecasController;
use App\Http\Controllers\PrestamosController;
use App\Http\Controllers\RecibosController;
use App\Http\Controllers\SocioController;
use App\Http\Controllers\SociosController;
use App\Http\Controllers\UsuariosController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('index');
});

//panel de inicio
Route::get('/panelInicio',[BibliotecasController::class,'prestamosBibliotecas'])->name('panelinicio');

// Libros
Route::get('/libros', [LibrosController::class, 'index'])->name('libros.index');
Route::get('/libros/crearLibro', [LibrosController::class, 'create'])->name('libros.create');
Route::post('/libros', [LibrosController::class, 'store'])->name('libros.store');
Route::get('/libros/editar/{id}', [LibrosController::class, 'edit'])->name('libros.edit');
Route::post('/libros/editar/{id}', [LibrosController::class, 'update'])->name('libros.update');
Route::get('/libros/eliminar/{id}', [LibrosController::class, 'destroy'])->name('libros.destroy');
// autores
Route::get('/autores', [AutoresController::class, 'index'])->name('autor.index');
Route::post('/autores', [AutoresController::class, 'store'])->name('autor.store');
Route::post('/autores/editar/{id}', [AutoresController::class, 'update'])->name('autor.update');
Route::post('/autores/eliminar/{id}', [AutoresController::class, 'destroy'])->name('autor.destroy');
Route::post('/autores/reactivar/{id}', [AutoresController::class, 'reactivar'])->name('autor.reactivar');

// bibliotecas
Route::get('/biblioteca', [BibliotecasController::class, 'index'])->name('biblio.index');
Route::post('/biblioteca', [BibliotecasController::class, 'store'])->name('biblio.store');
Route::post('/biblioteca/editar/{id}', [BibliotecasController::class, 'update'])->name('biblio.update');
Route::post('/biblioteca/eliminar/{id}', [BibliotecasController::class, 'destroy'])->name('biblio.destroy');
Route::post('/biblioteca/reactivar/{id}', [BibliotecasController::class, 'reactivar'])->name('biblio.reactivar');

// prestamos
Route::get('/prestamos', [PrestamosController::class, 'index'])->name('prestamo.index');
Route::get('/prestamos/crearPrestamo', [PrestamosController::class, 'create'])->name('prestamo.create');
Route::post('/prestamos', [PrestamosController::class, 'store'])->name('prestamo.store');
Route::get('/prestamos/editar/{id}', [PrestamosController::class, 'edit'])->name('prestamo.edit');
Route::post('/prestamos/editar/{id}', [PrestamosController::class, 'update'])->name('prestamo.update');
Route::get('/prestamos/eliminar/{id}', [PrestamosController::class, 'destroy'])->name('prestamo.destroy');

// recibos
Route::get('/recibos', [RecibosController::class, 'index'])->name('recibo.index');
Route::get('/recibos/crearRecibo', [RecibosController::class, 'create'])->name('recibo.create');
Route::post('/recibos', [RecibosController::class, 'store'])->name('recibo.store');
Route::get('/recibos/editar/{id}', [RecibosController::class, 'edit'])->name('recibo.edit');
Route::post('/recibos/editar/{id}', [RecibosController::class, 'update'])->name('recibo.update');
Route::get('/recibos/eliminar/{id}', [RecibosController::class, 'destroy'])->name('recibo.destroy');

// socios
Route::get('/socios', [SociosController::class, 'index'])->name('socio.index');
Route::get('/socios/crearSocio', [SociosController::class, 'create'])->name('socio.create');
Route::post('/socios', [SociosController::class, 'store'])->name('socio.store');
//Route::get('/socios/editar/{id}', [SociosController::class, 'edit'])->name('socio.edit');
Route::post('/socios/editar/{id}', [SociosController::class, 'update'])->name('socio.update');
Route::post('/socios/eliminar/{id}', [SociosController::class, 'destroy'])->name('socio.destroy');
Route::post('/socios/reactivar/{id}', [SociosController::class, 'reactivar'])->name('socio.reactivar');
// usuarios
Route::get('/usuarios', [UsuariosController::class, 'index'])->name('usuario.index');
Route::get('/usuarios/crearUsuario', [UsuariosController::class, 'create'])->name('usuario.create');
Route::post('/usuarios', [UsuariosController::class, 'store'])->name('usuario.store');
Route::get('/usuarios/editar/{id}', [UsuariosController::class, 'edit'])->name('usuario.edit');
Route::post('/usuarios/editar/{id}', [UsuariosController::class, 'update'])->name('usuario.update');
Route::get('/usuarios/eliminar/{id}', [UsuariosController::class, 'destroy'])->name('usuario.destroy');
