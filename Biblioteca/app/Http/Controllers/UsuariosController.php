<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\usuarios;
class UsuariosController extends Controller
{
     
    public function create() {
        $usuario = usuarios::all();

        return view('crearcategoria', compact('Categorias'));
    }

    public function store(Request $request) {

        $request->validate([
            'id_usuario' => 'required',
        ]);

      
        usuarios::create($request->all()); 

        return redirect()->route('usuarios.index')->with('funciona', 'Usuario guardado correctamente');
    }
    
    public function destroy($id) {
    $usuario = usuarios::find($id);
    

    if ($usuario->libros()->count() > 0) {
        return redirect()->route('usuarios.index')->with('error', 'No se puede eliminar.');
    }

    $usuario->delete();
    return redirect()->route('usuarios.index')->with('funciona', 'Usuario eliminado.');
}
}
