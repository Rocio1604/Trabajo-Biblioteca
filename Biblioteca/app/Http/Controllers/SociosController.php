<?php

namespace App\Http\Controllers;

use App\Models\bibliotecas;
use App\Models\EstadoCuota;
use App\Models\Socio;
use Illuminate\Http\Request;

class SociosController extends Controller
{
    public function index(){
        $socios = Socio::with(['estado', 'biblioteca'])->latest()->get();
        $estados = EstadoCuota::all();
        $bibliotecas = bibliotecas::all();
        return view('socio.index', compact('socios', 'estados', 'bibliotecas'));
    }

    public function store(Request $request)
    {

        $mensajes = [
            'dni.required' => 'El DNI es obligatorio',
            'dni.unique' => 'Este DNI ya pertenece a otro socio',
            'nombre.required' => 'El nombre es obligatorio',
            'nombre.min' => 'El nombre debe tener al menos 3 caracteres',
            'email.required' => 'El correo electrónico es necesario',
            'email.email' => 'Ingresa un formato de correo válido',
            'email.unique' => 'Este correo ya está registrado',
            'biblioteca.required' => 'Debes seleccionar una biblioteca',
            'telefono.required' => 'El teléfono es obligatorio',
            'telefono.regex' => 'El teléfono debe tener 9 dígitos y empezar por 6, 7, 8 o 9'
        ];

        $request->validate([
            'dni' => 'required|string|unique:socios,dni',
            'nombre' => 'required|string|min:3|max:100',
            'biblioteca' => 'required|integer|exists:bibliotecas,id',
            'email' => 'required|email|unique:socios,email|max:255',
            'telefono' => ['required', 'regex:/^[6789]\d{8}$/'],
        ],$mensajes);

        try {
            Socio::create([
                'dni' => $request->dni,
                'nombre' => $request->nombre,
                'biblioteca_id' => $request->biblioteca,
                'email' => $request->email,
                'telefono' => $request->telefono,
                'estado_cuota' => 1,
                'es_activo' => true,
            ]);
            return redirect()->route('socio.index')->with('success', 'Socio guardado');
        } catch (\Exception $e) {
            return redirect()->route('socio.index')->with('error', 'Error de base de datos: ' . $e->getMessage());
        }
    }
    public function update(Request $request, $id)
    {
        $socio =Socio::findOrFail($id);

        $mensajes = [
            'dni.required' => 'El DNI es obligatorio',
            'dni.unique' => 'Este DNI ya pertenece a otro socio',
            'nombre.required' => 'El nombre es obligatorio',
            'nombre.min' => 'El nombre debe tener al menos 3 caracteres',
            'email.required' => 'El correo electrónico es necesario',
            'email.email' => 'Ingresa un formato de correo válido',
            'email.unique' => 'Este correo ya está registrado',
            'biblioteca.required' => 'Debes seleccionar una biblioteca',
            'telefono.required' => 'El teléfono es obligatorio',
            'telefono.regex' => 'El teléfono debe tener 9 dígitos y empezar por 6, 7, 8 o 9'
        ];

        $request->validate([
            'dni' => 'required|string|unique:socios,dni,' . $id,
            'nombre' => 'required|string|min:3|max:100',
            'biblioteca' => 'required|integer|exists:bibliotecas,id',
            'email' => 'required|email|unique:socios,email,' . $id,
            'telefono' => ['required', 'regex:/^[6789]\d{8}$/'],
        ],$mensajes);

        try {
            $socio->update($request->all());

            return redirect()->route('socio.index')->with('success', 'Socio actualizado correctamente');

        } catch (\Exception $e) {
            return redirect()->route('socio.index')->with('error', 'Error al actualizar: ' . $e->getMessage());
        }
    }
    public function destroy($id)
    {
        $socio = Socio::findOrFail($id);

        try {
            $socio->update(['es_activo' => 0]);
            return redirect()->route('socio.index')->with('success', 'Socio desactivado correctamente');

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'No se pudo desactivar el socio: ' . $e->getMessage());
        }
    }
    public function reactivar($id) {
        $socio = Socio::findOrFail($id);
        try{
            $socio->update(['es_activo' => 1]);
            return redirect()->back()->with('success', 'Socio reactivado correctamente');
        }catch (\Exception $e) {
            return redirect()->back()->with('error', 'No se pudo reactivar el socio:. ' . $e->getMessage());
        }
    }
}
