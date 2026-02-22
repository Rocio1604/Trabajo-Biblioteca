<?php

namespace App\Http\Controllers;

use App\Models\Biblioteca;
use App\Models\EstadoCuota;
use App\Models\Recibo;
use App\Models\Socio;
use Illuminate\Support\Carbon;
use Illuminate\Http\Request;

class SociosController extends Controller
{
    public function index(){
        Socio::actualizarCuotasVencidas();
        $socios = Socio::with(['estado', 'biblioteca'])->orderBy('es_activo', 'desc')->latest()->get();
        $estados = EstadoCuota::all();
        $bibliotecas = Biblioteca::all();
        return view('socio.index', compact('socios', 'estados', 'bibliotecas'));
    }

    public function buscar(Request $request)
    {
        $busqueda = $request->nombre;
        $cuota = $request->cuota;
        $estado = $request->estado;

        $query = Socio::with(['estado', 'biblioteca']);
        
        if ($busqueda) {
            $query->where(function($q) use ($busqueda) {
                $q->where('nombre', 'LIKE', "%$busqueda%")
                ->orWhere('dni', 'LIKE', "%$busqueda%")
                ->orWhere('email', 'LIKE', "%$busqueda%");
            });
        }

        if ($cuota && $cuota !== 'todas') {
            $query->where('estado_cuota', $cuota);
        }

        if ($estado !== null && $estado !== '' && $estado !== 'todas') {
            $esActivo = (int)$estado;
            $query->where('es_activo', $esActivo);
            
        }

        $socios = $query->get();

        return response()->json($socios);
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
            'biblioteca_id.required' => 'Debes seleccionar una biblioteca',
            'telefono.required' => 'El teléfono es obligatorio',
            'telefono.regex' => 'El teléfono debe tener 9 dígitos y empezar por 6, 7, 8 o 9'
        ];

        $request->validate([
            'dni' => 'required|string|unique:socios,dni',
            'nombre' => 'required|string|min:3|max:100',
            'biblioteca_id' => 'required|integer|exists:bibliotecas,id',
            'email' => 'required|email|unique:socios,email|max:255',
            'telefono' => ['required', 'regex:/^[6789]\d{8}$/'],
        ],$mensajes);

        try {
            $socio=Socio::create([
                'dni' => $request->dni,
                'nombre' => $request->nombre,
                'biblioteca_id' => $request->biblioteca_id,
                'email' => $request->email,
                'telefono' => $request->telefono,
                'estado_cuota' => 3,
                'fecha_vencimiento' => Carbon::now()->addYear(),
                'es_activo' => true,
            ]);

            Recibo::create([
                'socio_id' => $socio->id,
                'biblioteca_id' => $socio->biblioteca_id,
                'concepto' => 'Cuota anual '.Carbon::now()->year,
                'tipo_id' => 1,//suscripcion anual
                'importe' =>10,
                'fecha' => now()->format('Y-m-d'),
                'estado_id' => 2, //Pendiente
                'es_activo' => true
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
            'biblioteca_id.required' => 'Debes seleccionar una biblioteca',
            'telefono.required' => 'El teléfono es obligatorio',
            'telefono.regex' => 'El teléfono debe tener 9 dígitos y empezar por 6, 7, 8 o 9'
        ];

        $request->validate([
            'dni' => 'required|string|unique:socios,dni,' . $id,
            'nombre' => 'required|string|min:3|max:100',
            'biblioteca_id' => 'required|integer|exists:bibliotecas,id',
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
