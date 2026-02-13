<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; 
use Illuminate\Support\Facades\Hash; 
use App\Models\Usuario;

class LoginController extends Controller
{
    public function login(Request $request)
    {
        $mensajes = [
            'correo.required' => 'vacio',
            'password.required' => 'vacio',
            'correo.email' => 'formato', 
        ];
        $request->validate([
            'correo' => 'required|email',
            'password' => 'required',
        ], $mensajes);
        $usuario = Usuario::where('correo', $request['correo'])->first();

        if ($usuario->es_activo == 0) {
            return back()->withErrors(['login_error' => 'Cuenta desactivada, contacta con el administrador.'])->withInput();
        }
        if ($usuario && $usuario->validacion) {
            if (Hash::check($request['password'], $usuario->validacion->firma_digital)) {
                Auth::login($usuario);
                return redirect()->route('panelinicio');
            }
        }

        return back()->withErrors([
            'login_error' => 'Las credenciales no son válidas.',
        ])->withInput();
    }
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
