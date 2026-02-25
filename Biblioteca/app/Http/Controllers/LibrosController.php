<?php

namespace App\Http\Controllers;

use App\Models\Autor;
use Illuminate\Http\Request;
use App\Models\Libro;
use App\Models\Biblioteca;
use App\Models\Categoria;

class LibrosController extends Controller
{
    public function index(){
        $libros = Libro::with([ 'autores','categoria'])->orderBy('es_activo', 'desc')->latest()->get();
        $categorias = Categoria::all();
        $autores = Autor::all();
        return view('libro.index', compact('libros', 'categorias', 'autores'));
    }

    public function buscar(Request $request)
    {
        $query = Libro::with(['categoria', 'autores']);

        if ($request->filled('buscar')) {
            $buscar = $request->buscar;
            $query->where(function($q) use ($buscar) {
                $q->where('titulo', 'LIKE', "%$buscar%")
                ->orWhere('isbn', 'LIKE', "%$buscar%")
                ->orWhereHas('autores', function($attr) use ($buscar) {
                    $attr->where('nombre', 'LIKE', "%$buscar%");
                });
            });
        }

        if ($request->filled('categoria') && $request->categoria !== 'todas') {
            $query->where('categoria_id', $request->categoria);
        }

        if ($request->filled('activo') && $request->activo !== 'todas') {
            $query->where('es_activo', $request->activo);
        }

        $libros = $query->get();

        return response()->json($libros);
    }

    public function store(Request $request)
    {

        $mensajes = [
            
            'isbn.required' => 'El ISBN es obligatorio',
            'isbn.min' => 'El ISBN debe tener mínimo 10 dígitos',
            'isbn.unique' => 'El ISBN ya existe en los registros',
            'isbn.regex' => 'El código solo puede contener letras, números y guiones (sin espacios ni otros símbolos)',
            'isbn.string'=>'El código debe ser tipo string',

            'titulo.required' => 'El titulo es obligatorio',
            'titulo.string' => 'El titulo debe ser tipo string',
            'titulo.min' => 'El título debe tener al menos 3 carácteres',
            'titulo.max' => 'El título debe tener menos de 100 carácteres',
            
            'categoria_id.required' => 'La categoría es necesaria',
            'categoria_id.integer' => 'La categoría tiene que ser tipo número',
            'categoria_id.exists' => 'La categoría seleccionado no existe',

            'precio.required' => 'El precio es necesario',
            'precio.numeric' => 'El precio debe ser un número válido',

            'autores.required' => 'Debe seleccionar al menos un autor',
            'autores.exists' => 'Debe seleccionar al menos un autor',
        ];

        $request->validate([
            'isbn' => 'required|min:10|unique:libros,isbn|string|regex:/^[a-zA-Z0-9-]+$/',
            'titulo' => 'required|string|min:3|max:100|',
            'categoria_id' => 'required|integer|exists:categorias,id',
            'autores' => 'required|array',
            'precio' => 'required|numeric|min:0|max:999999.99',
            'autores.*'=> 'exists:autores,id',

        ],$mensajes);

        try {
            $libro=Libro::create([
                'isbn' => $request->isbn,
                'titulo' => $request->titulo,
                'categoria_id' => $request->categoria_id,
                'precio' => $request->precio,
                'es_activo' => 1
            ]);
            $libro->autores()->attach($request->autores);
            return redirect()->route('libros.index')->with('success', 'libro guardado');
        } catch (\Exception $e) {
            return redirect()->route('libros.index')->with('error', 'Error de base de datos: ' . $e->getMessage());
        }
    }
   
    public function update(Request $request, $id)
    {
        $libro =Libro::findOrFail($id);

        $mensajes = [
            'titulo.required' => 'El titulo es obligatorio',
            'isbn.required' => 'El ISBN es obligatorio',
            'isbn.size' => 'El ISBN debe tener 17 caracteres',
            'titulo.min' => 'El título debe tener al menos 3 caracteres',
            'categoria_id.required' => 'La categoria es necesaria',
            'precio.required' => 'El precio es necesario',
            'autores.required' => 'Debe seleccionar al menos un autor',
            'autores.exists' => 'Debe seleccionar al menos un autor',
        ];

         $request->validate([
            'isbn' => 'required|string|size:17|unique:libros,isbn,'.$id,
            'titulo' => 'required|string|min:3|max:100',
            'categoria_id' => 'required|integer|exists:categorias,id',
            'precio' => 'required|numeric|min:0|max:999999.99',
            'autores' => 'required|array',
            'autores.*' => 'exists:autores,id',
        ],$mensajes);

        try {
            $libro->update($request->all());
            $libro->autores()->sync($request->autores);
            return redirect()->route('libros.index')->with('success', 'libro actualizado correctamente');

        } catch (\Exception $e) {
            return redirect()->route('libros.index')->with('error', 'Error al actualizar: ' . $e->getMessage());
        }
    }
    public function destroy($id)
    {
        $libro = Libro::findOrFail($id);

        try {
            $libro->update(['es_activo' => 0]);
            $libro->ejemplares()->update(['es_activo' => 0]);
            
            return redirect()->route('libros.index')->with('success', 'Libro desactivado correctamente');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'No se pudo desactivar el libro: ' . $e->getMessage());
        }
    }
    public function reactivar($id) {
        $libro = Libro::findOrFail($id);
        try{
            $libro->update(['es_activo' => 1]);
            return redirect()->back()->with('success', 'Libro reactivado correctamente');
        }catch (\Exception $e) {
            return redirect()->back()->with('error', 'No se pudo reactivar el libro: ' . $e->getMessage());
        }
    }
}
