<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Prestamos;
use App\Models\socios;
use App\Models\libros;
class PrestamosController extends Controller
{
    public function index() {
        $prestamos = Prestamos::with(['socios','libros'])->get();
        
        return view('home',compact('prestamos'));
    }
    public function create() {
        $socios = socios::all();
        $libros = libros::all();
        $prestamos = prestamos::all();
        return view('crearprestamos', compact('prestamos','libros','socios'));
    }
    public function store(Request $request) {

        $request->validate([
            'id_prestamo' => 'required',
            'ISBN' => 'required',
            'id_socio' => 'required'
        ]);

         Prestamos::create($request->all()); 

        return redirect()->route('prestamo.index')->with('funciona', 'prestamo guardado correctamente');
    }


    public function destroy($id) {
        Prestamos::destroy($id);
        return redirect()->route('prestamo.index')->with('funciona', 'prestamo eliminado');
    }
}
