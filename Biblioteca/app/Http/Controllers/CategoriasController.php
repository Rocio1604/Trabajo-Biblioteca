<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Categoria;
class CategoriasController extends Controller
{

    
    public function create() {
        $Categorias = Categoria::all();

        return view('crearcategoria', compact('Categorias'));
    }

    public function store(Request $request) {

        $request->validate([
            'id_categoria' => 'required',
        ]);

      
        Categoria::create($request->all()); 

        return redirect()->route('categorias.index')->with('funciona', 'Categoria guardado correctamente');
    }
    
    public function destroy($id) {
    $autor = Categoria::find($id);
    

    if ($autor->libros()->count() > 0) {
        return redirect()->route('Categorias.index')->with('error', 'No se puede eliminar.');
    }

    $autor->delete();
    return redirect()->route('Categorias.index')->with('funciona', 'Categoria eliminado.');
}
}
