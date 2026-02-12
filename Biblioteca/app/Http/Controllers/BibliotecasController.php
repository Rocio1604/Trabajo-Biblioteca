<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Bibliotecas;
class BibliotecasController extends Controller
{
    public function index() {
    $Bibliotecas = Bibliotecas::withCount('libros', 'socios')->get();
    return view('listabiblioteca', compact('Bibliotecas'));
    
    }

    
    public function create() {
        $Bibliotecas = Bibliotecas::all();

        return view('crearbiblioteca', compact('Bibliotecas'));
    }

    public function store(Request $request) {

        $request->validate([
            'id_biblioteca' => 'required',
        ]);

      
        Bibliotecas::create($request->all()); 

        return redirect()->route('bibliotecas.index')->with('funciona', 'Biblioteca guardado correctamente');
    }
    
    public function destroy($id) {
    $autor = Bibliotecas::find($id);
    

    if ($autor->libros()->count() > 0) {
        return redirect()->route('Bibliotecas.index')->with('error', 'No se puede eliminar tiene libros.');
    }

    $autor->delete();
    return redirect()->route('bibliotecas.index')->with('funciona', 'biblioteca eliminada.');
}
}
