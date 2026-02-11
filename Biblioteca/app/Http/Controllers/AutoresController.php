<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\autores;

class AutoresController extends Controller
{
   public function index() {
    $autores = autores::withCount('libroAutor')->get();
    return view('listaautor', compact('autores'));
    }

    
    public function create() {
        $autores = autores::all();

        return view('crearautor', compact('autores'));
    }

    public function store(Request $request) {

        $request->validate([
            'id_autor' => 'required',
        ]);

      
        autores::create($request->all()); 

        return redirect()->route('autore.index')->with('funciona', 'Autor guardado correctamente');
    }
    
    public function destroy($id) {
    $autor = autores::find($id);
    

    if ($autor->libros()->count() > 0) {
        return redirect()->route('autores.index')->with('error', 'No se puede eliminar tiene libros.');
    }

    $autor->delete();
    return redirect()->route('autor.index')->with('funciona', 'Autor eliminado.');
}
}
