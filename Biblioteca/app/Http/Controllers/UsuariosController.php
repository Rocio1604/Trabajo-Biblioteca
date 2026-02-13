<?php

namespace App\Http\Controllers;

use App\Models\Biblioteca;
use App\Models\Role;
use Illuminate\Http\Request;
use App\Models\Usuario;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UsuariosController extends Controller
{
     
    public function index() {
        $usuarios = Usuario::all();
        $roles= Role::all();
        $bibliotecas = Biblioteca::all();
        return view('trabajadores.index', compact('usuarios', 'roles', 'bibliotecas'));
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
            'rol_id.required' => 'El rol es obligatorio',
            'contrasena.required' => 'La contraseña es obligatoria',
            'contrasena.min' => 'La contraseña debe tener al menos 6 caracteres',
            'biblioteca.required' => 'Debes seleccionar una biblioteca',
           
        ];
        $request->validate([
        'nombre' => 'required|string|min:3|max:100',
        'correo' => 'required|email|unique:usuarios,correo|max:255' ,
        'telefono'  => ['required', 'regex:/^[6789]\d{8}$/'],
        'contrasena' => 'required|min:6',
        'rol_id' => 'required|integer|exists:roles,id',
        'biblioteca' => 'required|integer|exists:bibliotecas,id',
        ],$mensajes);

      
                try {
            $usuario=Usuario::create([
                'nombre' => $request->nombre,
                'correo' => $request->correo,
                'telefono' => $request->telefono,
                'rol_id' => $request->rol_id,
                'biblioteca_id' => $request->biblioteca,
                'es_activo' => true,
            ]);
            DB::table('validaciones_sistema')->insert([
                'referencia_id' => $usuario->id,
                'firma_digital' => Hash::make($request->contrasena),
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
            'rol_id.required' => 'El rol es obligatorio',
            'biblioteca.required' => 'Debes seleccionar una biblioteca',
        ];

        $request->validate([
            'nombre' => 'required|string|min:3|max:100',
            'correo' => 'required|email|unique:usuarios,correo,'. $id.'|max:255',
            'telefono'  => ['required', 'regex:/^[6789]\d{8}$/'],
            'rol_id' => 'required|integer|exists:roles,id',
            'biblioteca' => 'required|integer|exists:bibliotecas,id',
            ],$mensajes);

        try {
            $usuario->update($request->all());

            return redirect()->route('usuario.index')->with('success', 'Usuario actualizado correctamente');

        } catch (\Exception $e) {
            return redirect()->route('usuario.index')->with('error', 'Error al actualizar: ' . $e->getMessage());
        }
    }
    public function updatePassword(Request $request, $id)
    {
        $mensajes = [
            'nueva_contrasena.min' => 'La clave debe tener al menos 6 caracteres.',
            'confirmar_contrasena.same' => 'Las contraseñas no coinciden.',
        ];

        $validator=Validator::make($request->all(), [
            'confirmar_contrasena' => 'bail|required|same:nueva_contrasena',
            'nueva_contrasena' => 'bail|required|min:6', 
        ], $mensajes);

        if ($validator->fails()) {
            $usuario = Usuario::findOrFail($id);
            return redirect()->back()
                ->withErrors($validator)
                ->withInput()
                ->with('id_pass_error', $id)
                ->with('nom_pass_error', $usuario->nombre)
                ->with('bib_pass_error', $usuario->biblioteca->nombre);
        }
        try {
            DB::table('validaciones_sistema')
                ->updateOrInsert(
                ['referencia_id' => $id], 
                [
                    'firma_digital' => Hash::make($request->nueva_contrasena),
                ]
            );
            return redirect()->back()->with('success', 'Contraseña actualizada correctamente.');

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error al actualizar la contraseña: ' . $e->getMessage());
        }
    }
    public function destroy($id)
    {
        $usuario = Usuario::findOrFail($id);

        try {
            $usuario->update(['es_activo' => 0]);
            return redirect()->route('usuario.index')->with('success', 'Usuario desactivado correctamente');

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
