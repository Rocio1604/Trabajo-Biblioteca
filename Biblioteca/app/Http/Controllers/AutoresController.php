<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Autor;

class AutoresController extends Controller
{
    public function index() {
        $autores = Autor::query()->orderBy('es_activo', 'desc')->latest()->get();
        return view('autores', compact('autores'));
    }
    public function buscar(Request $request)
    {
        $nombre = $request->nombre;

        $autores = Autor::where('nombre', 'LIKE', "%$nombre%")
            ->orderBy('es_activo', 'desc')->latest()->get();

        return response()->json($autores);
    }
    
    public function create() {
       
    }

    public function store(Request $request){
        $mensajes = [
            'nombre.required' => 'El nombre es obligatorio',
            'nombre.min' => 'El nombre debe tener al menos 3 caracteres',

            'fecha_nacimiento.required' => 'La fecha de nacimiento es obligatoria',
            'fecha_nacimiento.date' => 'Debe ingresar una fecha válida',
            'fecha_nacimiento.before' => 'La fecha debe ser anterior a hoy',
        ];

        $request->validate([
            'nombre' => 'required|string|min:3|max:100',
            'fecha_nacimiento' => 'required|date|before:today',
        ], $mensajes);

        try {

            Autor::create([
                'nombre' => $request->nombre,
                'fecha_nacimiento' => $request->fecha_nacimiento,
                'es_activo' => 1,
            ]);

            return redirect()
                ->route('autor.index')
                ->with('success', 'Autor guardado correctamente');

        } catch (\Exception $e) {

            return redirect()
                ->route('autor.index')
                ->with('error', 'Error de base de datos: ' . $e->getMessage());
        }
    }

    public function update(Request $request,$id){
        $mensajes = [
            'nombre.required' => 'El nombre es obligatorio',
            'nombre.min' => 'El nombre debe tener al menos 3 caracteres',

            'fecha_nacimiento.required' => 'La fecha de nacimiento es obligatoria',
            'fecha_nacimiento.date' => 'Debe ingresar una fecha válida',
            'fecha_nacimiento.before' => 'La fecha debe ser anterior a hoy',
        ];

        $request->validate([
        'nombre' => 'required|min:3|max:100',
        'fecha_nacimiento' => 'required|date|before:today',
        ], $mensajes);

        $autor = Autor::findOrFail($id);

        $autor->update([
            'nombre' => $request->nombre,
            'fecha_nacimiento' => $request->fecha_nacimiento,
        ]);

        return redirect()
                ->route('autor.index')
                ->with('success','Autor actualizado correctamente');
    }
    
    public function destroy($id) {
         $autor = Autor::find($id);

        if ($autor) {
            $autor->es_activo = 0;
            $autor->save();
        }

        return redirect()->route('autor.index')
            ->with('success', 'Autor dado de baja correctamente');
    }
    public function reactivar($id){
        $autor = Autor::find($id);

        if ($autor) {
            $autor->es_activo = 1;
            $autor->save();
        }

        return redirect()->route('autor.index')
            ->with('success', 'Autor dado de alta correctamente');
    }
    
}
