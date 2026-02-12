<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Libro;
use App\Models\Biblioteca;
class LibrosController extends Controller
{
   public function index(){
        $libro = Libro::with([ 'biblioteca'])->latest()->get();
        $bibliotecas = Biblioteca::all();
        return view('libro.index', compact('libros', 'bibliotecas'));
    }

 public function store(Request $request)
    {

        $mensajes = [
            'titulo.required' => 'El titulo es obligatorio',
            'titulo.min' => 'El tituo debe tener al menos 3 caracteres',
            'estado.required' => 'El estado es obligatorio',
            'estado.min' => 'El estado debe tener al menos 3 caracteres',
            'categoria.required' => 'La categoria es necesaria',
            'categoria.min' => 'La categoria tiene que tener al menos 3 caracteres',
            'precio.required' => 'El precio es necesario',
        ];

        $request->validate([
            'titulo' => 'required|string|min:3|max:100',
            'estado' => 'required|string|min:3|max:100',
            'categoria' => 'required|string|min:3|max:100',
            'precio' => 'required|string|max:100',

        ],$mensajes);

        try {
            Libro::create([
                'ISBN' => $request->ISBN,
                'titulo' => $request->titulo,
                'categoria' => $request->categoria,
                'precio' => $request->precio,
            ]);
            return redirect()->route('libro.index')->with('success', 'libro guardado');
        } catch (\Exception $e) {
            return redirect()->route('libro.index')->with('error', 'Error de base de datos: ' . $e->getMessage());
        }
    }
   
 public function update(Request $request, $id)
    {
        $libro =Libro::findOrFail($id);

        $mensajes = [
    'titulo.required' => 'El titulo es obligatorio',
            'titulo.min' => 'El tituo debe tener al menos 3 caracteres',
            'estado.required' => 'El estado es obligatorio',
            'estado.min' => 'El estado debe tener al menos 3 caracteres',
            'categoria.required' => 'La categoria es necesaria',
            'categoria.min' => 'La categoria tiene que tener al menos 3 caracteres',
            'precio.required' => 'El precio es necesario',
        ];

        $request->validate([
            ' ISBN' => $request->ISBN,
            'titulo' => $request->titulo,
            'categoria' => $request->categoria,
            'precio' => $request->precio,
        ],$mensajes);

        try {
            $libro->update($request->all());

            return redirect()->route('libro.index')->with('success', 'libro actualizado correctamente');

        } catch (\Exception $e) {
            return redirect()->route('libro.index')->with('error', 'Error al actualizar: ' . $e->getMessage());
        }
    }
    public function destroy($id)
    {
        $libro = Libro::findOrFail($id);

        try {
            $libro->update(['es_activo' => 0]);
            return redirect()->route('libro.index')->with('success', 'libro desactivado correctamente');

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'No se pudo desactivar el libro: ' . $e->getMessage());
        }
    }
}
