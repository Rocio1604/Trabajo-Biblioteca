<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\LibrosController;
use App\Http\Controllers\AutoresController;
use App\Http\Controllers\BibliotecasController;
use App\Http\Controllers\EjemplareController;
use App\Http\Controllers\PrestamoController;
use App\Http\Controllers\RecibosController;
use App\Http\Controllers\SociosController;
use App\Http\Controllers\UsuariosController;
use Illuminate\Support\Facades\Route;

Route::get('/', [EjemplareController::class, 'home'])->name('home');
/* 
Route::get('/', function () {
        return view('index');
    }); */

Route::get('/login', function() {
        return redirect('/')->with('loginModal', true); 
    })->name('login');

Route::post('/login', [LoginController::class, 'login'])->name('login.post');

Route::middleware(['auth'])->group(function () {
    //panel de inicio
    Route::get('/panelInicio',[BibliotecasController::class,'prestamosBibliotecas'])->name('panelinicio');

    // Libros
    Route::get('/libros', [LibrosController::class, 'index'])->name('libros.index');
    Route::get('/libros/crearLibro', [LibrosController::class, 'create'])->name('libros.create');
    Route::post('/libros', [LibrosController::class, 'store'])->name('libros.store');
    Route::get('/libros/editar/{id}', [LibrosController::class, 'edit'])->name('libros.edit');
    Route::post('/libros/editar/{id}', [LibrosController::class, 'update'])->name('libros.update');
    Route::post('/libros/eliminar/{id}', [LibrosController::class, 'destroy'])->name('libros.destroy');
    Route::post('/libros/reactivar/{id}', [LibrosController::class, 'reactivar'])->name('libros.reactivar');

    // Ejemplares
    Route::get('/ejemplares', [EjemplareController::class, 'index'])->name('ejemplares.index');
    Route::get('/ejemplares/crearEjemplar', [EjemplareController::class, 'create'])->name('ejemplares.create');
    Route::post('/ejemplares', [EjemplareController::class, 'store'])->name('ejemplares.store');
    Route::get('/ejemplares/editar/{id}', [EjemplareController::class, 'edit'])->name('ejemplares.edit');
    Route::post('/ejemplares/editar/{id}', [EjemplareController::class, 'update'])->name('ejemplares.update');
    Route::post('/ejemplares/eliminar/{id}', [EjemplareController::class, 'destroy'])->name('ejemplares.destroy');
    Route::post('/ejemplares/reactivar/{id}', [EjemplareController::class, 'reactivar'])->name('ejemplares.reactivar');

    // autores
    Route::get('/autores', [AutoresController::class, 'index'])->name('autor.index');
    Route::post('/autores', [AutoresController::class, 'store'])->name('autor.store');
    Route::post('/autores/editar/{id}', [AutoresController::class, 'update'])->name('autor.update');
    Route::post('/autores/eliminar/{id}', [AutoresController::class, 'destroy'])->name('autor.destroy');
    Route::post('/autores/reactivar/{id}', [AutoresController::class, 'reactivar'])->name('autor.reactivar');
    Route::post('/autores/buscar', [AutoresController::class, 'buscar'])->name('autor.buscar');

    // bibliotecas
    Route::get('/biblioteca', [BibliotecasController::class, 'index'])->name('biblio.index');
    Route::post('/biblioteca', [BibliotecasController::class, 'store'])->name('biblio.store');
    Route::post('/biblioteca/editar/{id}', [BibliotecasController::class, 'update'])->name('biblio.update');
    Route::post('/biblioteca/eliminar/{id}', [BibliotecasController::class, 'destroy'])->name('biblio.destroy');
    Route::post('/biblioteca/reactivar/{id}', [BibliotecasController::class, 'reactivar'])->name('biblio.reactivar');
    Route::post('/biblioteca/buscar', [BibliotecasController::class, 'buscar'])->name('biblio.buscar');  

    // prestamos
    Route::get('/prestamos', [PrestamoController::class, 'index'])->name('prestamo.index');
    Route::get('/prestamos/crearPrestamo', [PrestamoController::class, 'create'])->name('prestamo.create');
    Route::post('/prestamos', [PrestamoController::class, 'store'])->name('prestamo.store');
    Route::get('/prestamos/editar/{id}', [PrestamoController::class, 'edit'])->name('prestamo.edit');
    Route::post('/prestamos/editar/{id}', [PrestamoController::class, 'update'])->name('prestamo.update');
    Route::post('/prestamos/eliminar/{id}', [PrestamoController::class, 'destroy'])->name('prestamo.destroy');
    Route::post('/prestamos/buscar', [PrestamoController::class, 'buscar'])->name('prestamo.buscar');
    
    // recibos
    Route::get('/recibos', [RecibosController::class, 'index'])->name('recibo.index');
    Route::post('/recibos', [RecibosController::class, 'store'])->name('recibo.store');
    Route::post('/recibos/editar/{id}', [RecibosController::class, 'update'])->name('recibo.update');
    Route::post('/recibos/eliminar/{id}', [RecibosController::class, 'destroy'])->name('recibo.destroy');
    Route::post('/recibos/buscar', [RecibosController::class, 'buscar'])->name('recibo.buscar');

    // socios
    Route::get('/socios', [SociosController::class, 'index'])->name('socio.index');
    Route::get('/socios/crearSocio', [SociosController::class, 'create'])->name('socio.create');
    Route::post('/socios', [SociosController::class, 'store'])->name('socio.store');
    //Route::get('/socios/editar/{id}', [SociosController::class, 'edit'])->name('socio.edit');
    Route::post('/socios/editar/{id}', [SociosController::class, 'update'])->name('socio.update');
    Route::post('/socios/eliminar/{id}', [SociosController::class, 'destroy'])->name('socio.destroy');
    Route::post('/socios/reactivar/{id}', [SociosController::class, 'reactivar'])->name('socio.reactivar');
    Route::post('/socios/buscar', [SociosController::class, 'buscar'])->name('socio.buscar');

    // usuarios
    Route::get('/usuarios', [UsuariosController::class, 'index'])->name('usuario.index')->middleware('can:admin');
    Route::get('/usuarios/crearUsuario', [UsuariosController::class, 'create'])->name('usuario.create')->middleware('can:admin');
    Route::post('/usuarios', [UsuariosController::class, 'store'])->name('usuario.store')->middleware('can:admin');
    Route::get('/usuarios/editar/{id}', [UsuariosController::class, 'edit'])->name('usuario.edit')->middleware('can:admin');
    Route::post('/usuarios/editar/{id}', [UsuariosController::class, 'update'])->name('usuario.update')->middleware('can:admin');
    Route::post('/usuarios/eliminar/{id}', [UsuariosController::class, 'destroy'])->name('usuario.destroy')->middleware('can:admin');
    Route::post('/usuarios/reactivar/{id}', [UsuariosController::class, 'reactivar'])->name('usuario.reactivar')->middleware('can:admin');
    Route::post('/usuarios/password/{id}', [UsuariosController::class, 'updatePassword'])->name('usuario.password');

    //log out
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

});
