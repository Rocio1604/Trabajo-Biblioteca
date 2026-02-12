<?php

namespace App\Http\Controllers;

use App\Models\Recibo;
use App\Models\Socio;
use App\Models\TipoRecibo;
use App\Models\EstadoRecibo;
use Illuminate\Http\Request;

class RecibosController extends Controller
{
    public function index()
    {
        $recibos = Recibo::with(['socio', 'tipo', 'estado'])
            ->latest()
            ->get();
        
        $socios = Socio::all();
        $tipos = TipoRecibo::all();
        $estados = EstadoRecibo::all();
        
        return view('recibo', compact('recibos', 'socios', 'tipos', 'estados'));
    }

    public function store(Request $request)
    {
        $mensajes = [
            'socio_id.required' => 'Debes seleccionar un socio',
            'socio_id.exists' => 'El socio seleccionado no existe',
            'concepto.required' => 'El concepto es obligatorio',
            'concepto.min' => 'El concepto debe tener al menos 5 caracteres',
            'tipo_id.required' => 'Debes seleccionar un tipo de recibo',
            'importe.required' => 'El importe es obligatorio',
            'importe.numeric' => 'El importe debe ser un número',
            'importe.min' => 'El importe debe ser mayor a 0',
            'fecha.required' => 'La fecha es obligatoria',
            'fecha.date' => 'La fecha no es válida',
            'estado_id.required' => 'Debes seleccionar un estado'
        ];

        $request->validate([
            'socio_id' => 'required|integer|exists:socios,id',
            'concepto' => 'required|string|min:5|max:255',
            'tipo_id' => 'required|integer|exists:tipos_recibos,id',
            'importe' => 'required|numeric|min:0.01',
            'fecha' => 'required|date',
            'estado_id' => 'required|integer|exists:estados_recibos,id'
        ], $mensajes);

        try {
            Recibo::create([
                'socio_id' => $request->socio_id,
                'concepto' => $request->concepto,
                'tipo_id' => $request->tipo_id,
                'importe' => $request->importe,
                'fecha' => $request->fecha,
                'estado_id' => $request->estado_id,
                'es_activo' => true
            ]);

            return redirect()->route('recibo.index')->with('success', 'Recibo creado correctamente');
        } catch (\Exception $e) {
            return redirect()->route('recibo.index')->with('error', 'Error al crear el recibo: ' . $e->getMessage());
        }
    }

    public function update(Request $request, $id)
    {
        $recibo = Recibo::findOrFail($id);

        $mensajes = [
            'socio_id.required' => 'Debes seleccionar un socio',
            'concepto.required' => 'El concepto es obligatorio',
            'tipo_id.required' => 'Debes seleccionar un tipo de recibo',
            'importe.required' => 'El importe es obligatorio',
            'fecha.required' => 'La fecha es obligatoria',
            'estado_id.required' => 'Debes seleccionar un estado'
        ];

        $request->validate([
            'socio_id' => 'required|integer|exists:socios,id',
            'concepto' => 'required|string|min:5|max:255',
            'tipo_id' => 'required|integer|exists:tipos_recibos,id',
            'importe' => 'required|numeric|min:0.01',
            'fecha' => 'required|date',
            'estado_id' => 'required|integer|exists:estados_recibos,id'
        ], $mensajes);

        try {
            $recibo->update([
                'socio_id' => $request->socio_id,
                'concepto' => $request->concepto,
                'tipo_id' => $request->tipo_id,
                'importe' => $request->importe,
                'fecha' => $request->fecha,
                'estado_id' => $request->estado_id
            ]);
            
            return redirect()->route('recibo.index')->with('success', 'Recibo actualizado correctamente');
        } catch (\Exception $e) {
            return redirect()->route('recibo.index')->with('error', 'Error al actualizar: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        $recibo = Recibo::findOrFail($id);

        try {
            $recibo->update(['es_activo' => false]);
            return redirect()->route('recibo.index')->with('success', 'Recibo desactivado correctamente');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'No se pudo desactivar el recibo: ' . $e->getMessage());
        }
    }
}