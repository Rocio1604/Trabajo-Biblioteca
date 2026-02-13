<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Biblioteca;
use App\Models\Prestamos;
use Illuminate\Support\Facades\DB;

class BibliotecasController extends Controller
{
    public function index() {
        $bibliotecas = Biblioteca::query()->latest()->orderBy('es_activo', 'desc')->get();

        return view('bibliotecas', compact('bibliotecas'));
    }

    public function buscar(Request $request)
    {
        $provincia = $request->provincia;

        $bibliotecas = Biblioteca::where('provincia', 'LIKE', "%$provincia%")
            ->get();

        return response()->json($bibliotecas);
    }
        
    public function create() {
        
    }

    public function store(Request $request){
       
        $mensajes = [
            'nombre.required' => 'El nombre es obligatorio',
            'nombre.min' => 'El nombre debe tener al menos 3 caracteres',

            'correo.required' => 'El correo electrónico es obligatorio',
            'correo.email' => 'Ingresa un formato de correo válido',
            'correo.unique' => 'Este correo ya está registrado',

            'telefono.required' => 'El teléfono es obligatorio',
            'telefono.regex' => 'El teléfono debe tener 9 dígitos y empezar por 6, 7, 8 o 9',

            'provincia.required' => 'La provincia es obligatoria',
            'provincia.min' => 'La provincia debe tener al menos 3 caracteres',

            'direccion.required' => 'La direccion es obligatoria',
            'direccion.min' => 'La direccion debe tener al menos 5 caracteres',
        ];

        $request->validate([
            'nombre' => 'required|string|min:3|max:100',
            'provincia' => 'required|string|min:3|max:100',
            'correo' => 'required|email|unique:bibliotecas,correo|max:255',
            'direccion' => 'required|min:5|max:255',
            'telefono' => ['required', 'regex:/^[6789]\d{8}$/'],
        ], $mensajes);

        try {

             Biblioteca::create([
                'nombre' => $request->nombre,
                'provincia' => $request->provincia,
                'direccion' => $request->direccion,
                'correo' => $request->correo,
                'telefono' => $request->telefono,
                'es_activo' => 1, 
            ]);

            return redirect()
                ->route('biblio.index')
                ->with('success', 'Biblioteca guardada correctamente');

        } catch (\Exception $e) {

            return redirect()
                ->route('biblio.index')
                ->with('error', 'Error de base de datos: ' . $e->getMessage());
        }
       
    }

    public function update(Request $request, $id){
        $mensajes = [
            'nombre.required' => 'El nombre es obligatorio',
            'nombre.min' => 'El nombre debe tener al menos 3 caracteres',

            'correo.required' => 'El correo electrónico es obligatorio',
            'correo.email' => 'Ingresa un formato de correo válido',
            'correo.unique' => 'Este correo ya está registrado',

            'telefono.required' => 'El teléfono es obligatorio',
            'telefono.regex' => 'El teléfono debe tener 9 dígitos y empezar por 6, 7, 8 o 9',

            'provincia.required' => 'La provincia es obligatoria',
            'provincia.min' => 'La provincia debe tener al menos 3 caracteres',

            'direccion.required' => 'La direccion es obligatoria',
            'direccion.min' => 'La direccion debe tener al menos 5 caracteres',
        ];

        $request->validate([
            'nombre' => 'required|min:3|max:100',
            'provincia' => 'required|min:3|max:100',
            'direccion' => 'required|min:5|max:255',
            'correo' => 'required|email|max:255',
            'telefono' => ['required','regex:/^[6789]\d{8}$/'],
        ], $mensajes);

        $biblioteca = Biblioteca::findOrFail($id);
        try {
            $biblioteca->update($request->all());
            return redirect()->route('biblio.index')
                        ->with('success','Biblioteca actualizada correctamente');
        } catch (\Exception $e) {
            return redirect()->route('biblio.index')->with('error', 'Error al actualizar: ' . $e->getMessage());
        }
        
    }


    public function destroy($id) {
        $biblioteca = Biblioteca::find($id);

        if ($biblioteca) {
            $biblioteca->es_activo = 0;
            $biblioteca->save();
        }

        return redirect()->route('biblio.index')
            ->with('success', 'Biblioteca desactivada correctamente');
    }
    public function reactivar($id)
    {
        $biblioteca = Biblioteca::find($id);

        if ($biblioteca) {
            $biblioteca->es_activo = 1;
            $biblioteca->save();
        }

        return redirect()->route('biblio.index')
            ->with('success', 'Biblioteca reactivada correctamente');
    }

    public function prestamosBibliotecas(){

       $bibliotecasPrestamos = DB::table('bibliotecas')
            ->leftJoin('ejemplares', 'bibliotecas.id', '=', 'ejemplares.biblioteca_id')
            ->leftJoin('prestamos', 'ejemplares.id', '=', 'prestamos.ejemplar_id')
            ->leftJoin('socios', 'bibliotecas.id', '=', 'socios.biblioteca_id')
            ->select(
                'bibliotecas.id',
                'bibliotecas.nombre',
                'bibliotecas.provincia',
                DB::raw('COUNT(DISTINCT prestamos.id) as prestamos_count'),
                DB::raw('COUNT(DISTINCT socios.id) as socios_count')
            )
            ->groupBy('bibliotecas.id', 'bibliotecas.nombre', 'bibliotecas.provincia')
            ->orderBy('prestamos_count', 'desc')
            ->limit(5)
            ->get();

        $prestamosRecientes = DB::table('prestamos')
            ->join('socios', 'prestamos.socio_id', '=', 'socios.id')
            ->join('ejemplares', 'prestamos.ejemplar_id', '=', 'ejemplares.id')
            ->join('libros', 'ejemplares.libro_id', '=', 'libros.id')
            ->select(
                'prestamos.id',
                'prestamos.fecha_prestamo',
                'socios.nombre as socio_nombre',
                'libros.titulo as libro_titulo'
            )
            ->orderBy('prestamos.fecha_prestamo', 'desc')
            ->limit(5)
            ->get();
        
            return view('panelInicio',compact('bibliotecasPrestamos','prestamosRecientes'));
    }
}
