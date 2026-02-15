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
    public function index(Request $request){
        $ejemplares = $this->aplicarFiltros($request)->get();
        /* $ejemplares = Ejemplare::with(['libro', 'biblioteca', 'estado', 'disponibilidad'])->where('es_activo', 1)->latest()->get(); */
        if ($request->ajax()) {
            return view('ejemplar.partials.tabla', compact('ejemplares'))->render();
        }
        $libros = Libro::where('es_activo', 1)->get();
        $bibliotecas = Biblioteca::where('es_activo', 1)->get();
        $estados = EstadoLibro::all();
        $disponibilidades = DisponibilidadLibro::all();
        $categorias = Categoria::all();
        return view('ejemplar.index', compact('ejemplares', 'libros', 'bibliotecas', 'estados', 'disponibilidades', 'categorias'));
    }
    public function home(Request $request) {
        $ejemplares = $this->aplicarFiltros($request)->where('es_activo', 1)->latest()->get();
        /* $ejemplares = Ejemplare::with(['libro', 'biblioteca', 'estado', 'disponibilidad'])->where('es_activo', 1)->latest()->get(); */
        if ($request->ajax()) {
            return view('partials.tabla', compact('ejemplares'))->render();
        }
        $bibliotecas = Biblioteca::where('es_activo', 1)->get();
        $categorias = Categoria::all();
        $totalejemplares = Ejemplare::where('es_activo', 1)->count();
        return view('index', compact('ejemplares', 'totalejemplares', 'bibliotecas', 'categorias'));
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
            return redirect()->route('ejemplares.index')->with('error', 'No se pudo desactivar el ejemplar: ' . $e->getMessage());
        }
    }
    public function reactivar($id) {
        $ejemplar = Ejemplare::findOrFail($id);
        try{
            $ejemplar->update(['es_activo' => 1]);
            return redirect()->route('ejemplares.index')->with('success', 'Ejemplar reactivado correctamente');
        }catch (\Exception $e) {
            return redirect()->route('ejemplares.index')->with('error', 'No se pudo reactivar el ejemplar: ' . $e->getMessage());
        }
    }
    private function aplicarFiltros(Request $request)
    {
        $query = Ejemplare::with(['libro.autores', 'biblioteca', 'estado', 'disponibilidad']);
        if ($request->filled('buscar')) {
            $buscar = $request->buscar;
            $query->whereHas('libro', function($q) use ($buscar) {
                $q->where('titulo', 'LIKE', "%$buscar%")
                ->orWhere('isbn', 'LIKE', "%$buscar%")
                ->orWhereHas('autores', function($q2) use ($buscar) {
                    $q2->where('nombre', 'LIKE', "%$buscar%");
                });
            });
        }
        if ($request->filled('biblioteca_id') && $request->biblioteca_id != 'todas') {
            $query->where('biblioteca_id', $request->biblioteca_id);
        }
        if ($request->filled('categoria_id') && $request->categoria_id != 'todas') {
            $query->whereHas('libro', function($q) use ($request) {
                $q->where('categoria_id', $request->categoria_id);
            });
        }
        if ($request->filled('estado_id') && $request->estado_id != 'todas') { // Asegúrate que diga != 'todas'
            $query->where('estado_id', $request->estado_id);
        }
        if ($request->filled('activo') && $request->activo != 'todas') {
            $query->where('es_activo', $request->activo);
        }
        if ($request->filled('disponibilidad')) {
            if ($request->disponibilidad === '1') $query->where('disponibilidad_id', 1);
            if ($request->disponibilidad === '0') $query->where('disponibilidad_id', '!=', 1);
        }
        /* if ($request->filled('disponibilidad') && $request->disponibilidad != 'todos') {
            $query->where('disponibilidad_id', $request->disponibilidad);
        } */
        
        return $query->orderBy('es_activo', 'desc')->latest();
    }
}