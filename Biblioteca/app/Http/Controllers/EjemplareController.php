<?php

namespace App\Http\Controllers;

use App\Models\Biblioteca;
use App\Models\Categoria;
use App\Models\DisponibilidadLibro;
use App\Models\Ejemplare;
use App\Models\EstadoLibro;
use App\Models\Libro;
use Illuminate\Http\Request;

class EjemplareController extends Controller
{
    public function index(){
        $ejemplares = Ejemplare::with(['libro', 'biblioteca', 'estado', 'disponibilidad'])->latest()->get();
        $libros = Libro::where('es_activo', 1)->get();
        $bibliotecas = Biblioteca::where('es_activo', 1)->get();
        $estados = EstadoLibro::all();
        $disponibilidades = DisponibilidadLibro::all();
        $categorias = Categoria::all();
        return view('ejemplar.index', compact('ejemplares', 'libros', 'bibliotecas', 'estados', 'disponibilidades', 'categorias'));
    }
    public function store(Request $request)
    {

        $mensajes = [
            'libro_id.required' => 'Debe seleccionar un libro.',
            'biblioteca_id.required'=> 'La biblioteca es obligatoria.',
            'estado_id.required' => 'El estado del ejemplar es necesario.',
        ];

        $request->validate([
            'libro_id'          => 'required|exists:libros,id',
            'biblioteca_id'     => 'required|exists:bibliotecas,id',
            'estado_id'         => 'required|exists:estados_libros,id',

        ],$mensajes);

        try {
            Ejemplare::create([
                'libro_id' => $request->libro_id,
                'biblioteca_id' => $request->biblioteca_id,
                'estado_id' => $request->estado_id,
                'disponibilidad_id' => 1,
                'es_activo' => 1
            ]);

            return redirect()->route('ejemplares.index')->with('success', 'Ejemplar creado correctamente');
        } catch (\Exception $e) {
            return redirect()->route('ejemplares.index')->with('error', 'Error de base de datos: ' . $e->getMessage());
        }
    }
   
 public function update(Request $request, $id)
    {
        $ejemplar =Ejemplare::findOrFail($id);

        $mensajes = [
            'libro_id.required' => 'Debe seleccionar un libro.',
            'biblioteca_id.required'=> 'La biblioteca es obligatoria.',
            'estado_id.required' => 'El estado del ejemplar es necesario.',
        ];

         $request->validate([
            'libro_id' => 'required|exists:libros,id',
            'biblioteca_id' => 'required|exists:bibliotecas,id',
            'estado_id' => 'required|exists:estados_libros,id',
        ],$mensajes);

        try {
            $ejemplar->update($request->all());
            return redirect()->route('ejemplares.index')->with('success', 'Ejemplar actualizado correctamente');

        } catch (\Exception $e) {
            return redirect()->route('ejemplares.index')->with('error', 'Error al actualizar: ' . $e->getMessage());
        }
    }
    public function destroy($id)
    {
        $ejemplar = Ejemplare::findOrFail($id);

        try {
            $ejemplar->update(['es_activo' => 0]);
            return redirect()->route('ejemplares.index')->with('success', 'Ejemplar desactivado correctamente');

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'No se pudo desactivar el ejemplar: ' . $e->getMessage());
        }
    }
    public function reactivar($id) {
        $ejemplar = Ejemplare::findOrFail($id);
        try{
            $ejemplar->update(['es_activo' => 1]);
            return redirect()->back()->with('success', 'Ejemplar reactivado correctamente');
        }catch (\Exception $e) {
            return redirect()->back()->with('error', 'No se pudo reactivar el ejemplar: ' . $e->getMessage());
        }
    }
}