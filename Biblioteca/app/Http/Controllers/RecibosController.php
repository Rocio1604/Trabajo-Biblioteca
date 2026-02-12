<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Recibo;
use App\Models\Socio;
class RecibosController extends Controller
{
      public function index(){
        $recibo = Recibo::with(['estado', 'biblioteca'])->latest()->get();
        $socio = Socio::all();

        return view('recibo.index', compact('recibo', 'socio'));
    }

    public function store(Request $request)
    {

        $mensajes = [
             'concepto.required' => 'El concepto es obligatorio',
            'concepto.min' => 'El concepto debe tener al menos 3 caracteres',
           'tipo.required' => 'El tipo es obligatorio',
            'tipo.min' => 'El tipo debe tener al menos 3 caracteres',
            'importe.required' => 'El importe es obligatorio',
            'importe.min' => 'El importe debe tener al menos 3 caracteres',
            'fecha.required' => 'La fecha es obligatorio',
            'fecha.min' => 'La fecha es obligatoria',
        ];

        $request->validate([
        'concepto' => 'required|string|min:3|max:100',
        'tipo' => 'required|string|min:3|max:100',
        'importe' => 'required|string|min:3|max:100',
        'fecha' => 'required|string|min:3|max:100',
        ],$mensajes);

        try {
            Recibo::create([
                'concepto' => $request->concepto,
                'tipo' => $request->tipo,
                'importe' => $request->importe,
                'fecha' => $request->fecha,
            ]);
            return redirect()->route('recibo.index')->with('success', 'Recibo guardado');
        } catch (\Exception $e) {
            return redirect()->route('recibo.index')->with('error', 'Error de base de datos: ' . $e->getMessage());
        }
    }
     public function update(Request $request, $id)
    {
        $recibo =Recibo::findOrFail($id);

        $mensajes = [
            'concepto.required' => 'El concepto es obligatorio',
            'concepto.min' => 'El concepto debe tener al menos 3 caracteres',
           'tipo.required' => 'El tipo es obligatorio',
            'tipo.min' => 'El tipo debe tener al menos 3 caracteres',
            'importe.required' => 'El importe es obligatorio',
            'importe.min' => 'El importe debe tener al menos 3 caracteres',
            'fecha.required' => 'La fecha es obligatorio',
            'fecha.min' => 'La fecha es obligatoria',
        ];

        $request->validate([
            'concepto' => 'required|string|min:3|max:100',
            'tipo' => 'required|string|min:3|max:100',
            'importe' => 'required|string|min:3|max:100',
            'fecha' => 'required|date'],$mensajes);

        try {
            $recibo->update($request->all());

            return redirect()->route('recibo.index')->with('success', 'Socio actualizado correctamente');

        } catch (\Exception $e) {
            return redirect()->route('recibo.index')->with('error', 'Error al actualizar: ' . $e->getMessage());
        }
    }

}
