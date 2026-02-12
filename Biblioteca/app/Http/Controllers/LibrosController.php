<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\libros;
use App\Models\biblioteca;
class LibrosController extends Controller
{
    public function index() {
        $libro = Libros::with(['bibliotecas'])->get();
        
        return view('home',compact('libros'));
    }
public function create() {
        $biblioteca = biblioteca::all();
        $Libros = Libros::all();

        return view('crearlibros', compact('libros','bibliotecas'));
    }
    public function store(Request $request) {

        $request->validate([
            'ISBN' => 'required',
            'id_biblioteca' => 'required'
        ]);

         Libros::create($request->all()); 

        return redirect()->route('libros.index')->with('funciona', 'Libro guardado correctamente');
    }


    public function destroy($id) {
        Libros::destroy($id);
        return redirect()->route('libros.index')->with('funciona', 'Libro eliminado');
    }
}
