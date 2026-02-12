<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Prestamo;
use App\Models\Socio;
use App\Models\Libro;
class PrestamosController extends Controller
{
    public function index() {
        $prestamos = Prestamo::with(['socios','libros'])->get();
        
        return view('home',compact('prestamos'));
    }
    public function create() {
        $socios = Socio::all();
        $libros = Libro::all();
        $prestamos = prestamo::all();
        return view('crearprestamos', compact('prestamos','libros','socios'));
    }
    public function store(Request $request) {

        $request->validate([
            'id_prestamo' => 'required',
            'ISBN' => 'required',
            'id_socio' => 'required'
        ]);

         Prestamo::create($request->all()); 

        return redirect()->route('prestamo.index')->with('funciona', 'prestamo guardado correctamente');
    }


    public function destroy($id) {
        Prestamo::destroy($id);
        return redirect()->route('prestamo.index')->with('funciona', 'prestamo eliminado');
    }
}
