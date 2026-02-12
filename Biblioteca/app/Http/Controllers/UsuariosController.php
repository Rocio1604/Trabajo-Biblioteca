<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Usuario;
class UsuariosController extends Controller
{
     
    public function create() {
        $usuario = Usuario::all();

        return view('crearcategoria', compact('Categorias'));
    }

    public function store(Request $request) {
    $mensajes = [
            'nombre.required' => 'El nombre es obligatorio',
            'nombre.min' => 'El nombre debe tener al menos 3 caracteres',
            'correo.required' => 'El correo electrónico es necesario',
            'correo.email' => 'Ingresa un formato de correo válido',
            'correo.unique' => 'Este correo ya está registrado',
            'telefono.required' => 'El teléfono es obligatorio',
            'telefono.regex' => 'El teléfono debe tener 9 dígitos y empezar por 6, 7, 8 o 9',
            'rol.required' => 'El rol es obligatorio',
            'rol.min' => 'El rol debe tener al menos 3 caracteres',
            'biblioteca.required' => 'Debes seleccionar una biblioteca',
           
        ];
        $request->validate([
        'nombre' => 'required|string|min:3|max:100',
        'correo' => 'required|email|unique:Usuarios,email|max:255' ,
        'telefono'  => ['required', 'regex:/^[6789]\d{8}$/'],
        'rol' => 'required|string|min:3|max:100',
        'biblioteca' => 'required|integer|exists:bibliotecas,id',
        ],$mensajes);

      
                try {
            Usuario::create([
                'nombre' => $request->nombre,
                'correo' => $request->email,
                'telefono' => $request->telefono,
                'rol' => $request->rol,
                'biblioteca_id' => $request->biblioteca,
                'es_activo' => true,
            ]);
            return redirect()->route('usuario.index')->with('success', 'usuario guardado');
        } catch (\Exception $e) {
            return redirect()->route('usuario.index')->with('error', 'Error de base de datos: ' . $e->getMessage());
        }
    }
    
 public function update(Request $request, $id)
    {
        $usuario =Usuario::findOrFail($id);

        $mensajes = [
             'nombre.required' => 'El nombre es obligatorio',
            'nombre.min' => 'El nombre debe tener al menos 3 caracteres',
            'correo.required' => 'El correo electrónico es necesario',
            'correo.email' => 'Ingresa un formato de correo válido',
            'correo.unique' => 'Este correo ya está registrado',
            'telefono.required' => 'El teléfono es obligatorio',
            'telefono.regex' => 'El teléfono debe tener 9 dígitos y empezar por 6, 7, 8 o 9',
            'rol.required' => 'El rol es obligatorio',
            'rol.min' => 'El rol debe tener al menos 3 caracteres',
            'biblioteca.required' => 'Debes seleccionar una biblioteca',
        ];

        $request->validate([
            'nombre' => 'required|string|min:3|max:100',
        'correo' => 'required|email|unique:Usuarios,email|max:255' .$id,
        'telefono'  => ['required', 'regex:/^[6789]\d{8}$/'],
        'rol' => 'required|string|min:3|max:100',
        'biblioteca' => 'required|integer|exists:bibliotecas,id',
        ],$mensajes);

        try {
            $usuario->update($request->all());

            return redirect()->route('Usuario.index')->with('success', 'Usuario actualizado correctamente');

        } catch (\Exception $e) {
            return redirect()->route('Usuario.index')->with('error', 'Error al actualizar: ' . $e->getMessage());
        }
    }
    public function destroy($id)
    {
        $usuario = Usuario::findOrFail($id);

        try {
            $usuario->update(['es_activo' => 0]);
            return redirect()->route('Usuario.index')->with('success', 'Usuario desactivado correctamente');

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'No se pudo desactivar el Usuario: ' . $e->getMessage());
        }
    }
    public function reactivar($id) {
        $usuario = Usuario::findOrFail($id);
        try{
            $usuario->update(['es_activo' => 1]);
            return redirect()->back()->with('success', 'Usuario reactivado correctamente');
        }catch (\Exception $e) {
            return redirect()->back()->with('error', 'No se pudo reactivar el Usuario:. ' . $e->getMessage());
        }
    }
}
