<?php

namespace App\Http\Controllers;

use App\Models\Recibo;
use App\Models\Socio;
use App\Models\TipoRecibo;
use App\Models\EstadoRecibo;
use App\Models\MetodoPago;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class RecibosController extends Controller
{
    public function index()
    {
        $recibos = Recibo::with(['socio', 'tipo', 'estado'])->orderBy('es_activo', 'desc')->orderBy('id', 'desc')
            ->latest()
            ->get();
        
        $socios = Socio::where('es_activo', 1)->get();;
        $tipos = TipoRecibo::all();
        $estados = EstadoRecibo::all();
        $metodos = MetodoPago::all()->pluck('nombre', 'id');
        
        return view('recibo', compact('recibos', 'socios', 'tipos', 'estados','metodos'));
    }

    public function buscar(Request $request)
    {
        $busqueda = $request->busqueda;
        $tipo = $request->tipo;
        $estado = $request->estado;

        $query = Recibo::with(['socio', 'tipo', 'estado'])->orderBy('es_activo', 'desc')->orderBy('id', 'desc')
            ->latest();

        if ($busqueda) {
            $idExtraido = null;
            if (preg_match('/REC-\d{4}-(\d+)/', $busqueda, $matches)) {
                $idExtraido = (int)$matches[1];
            }

            $query->where(function($q) use ($busqueda, $idExtraido) {
                $q->whereHas('socio', function($subQ) use ($busqueda) {
                $subQ->where('nombre', 'LIKE', "%$busqueda%");
            })
            ->orWhere('concepto', 'LIKE', "%$busqueda%");
            
                if ($idExtraido) {
                    $q->orWhere('id', $idExtraido);
                } else {
                    $q->orWhere('id', 'LIKE', "%$busqueda%");
                }
            });
        }

        if ($tipo && $tipo !== 'todos') {
            $query->where('tipo_id', $tipo);
        }

        if ($estado && $estado !== 'todos') {
            $query->where('estado_id', $estado);
        }

        $recibos = $query->get();

        return response()->json($recibos);
    }

    public function store(Request $request)
    {
        $user = Auth::user();
        $mensajes = [
            'socio_id.required' => 'Debes seleccionar un socio',
            'socio_id.exists' => 'El socio seleccionado no existe',
            'concepto.required' => 'El concepto es obligatorio',
            'concepto.min' => 'El concepto debe tener al menos 5 caracteres',
            'tipo_id.required' => 'Debes seleccionar un tipo de recibo',
            'importe.required' => 'El importe es obligatorio',
            'importe.numeric' => 'El importe debe ser un número',
            'importe.min' => 'El importe debe ser mayor a 0',
        ];

        $request->validate([
            'socio_id' => 'required|integer|exists:socios,id',
            'concepto' => 'required|string|min:5|max:255',
            'tipo_id' => 'required|integer|exists:tipos_recibos,id',
            'importe' => 'required|numeric|min:0.01',
        ], $mensajes);

        try {
            Recibo::create([
                'socio_id' => $request->socio_id,
                'biblioteca_id' => $user->biblioteca_id,
                'concepto' => $request->concepto,
                'tipo_id' => $request->tipo_id,
                'importe' => $request->importe,
                'fecha' => now()->format('Y-m-d'),
                'estado_id' => 2, // Pendiente
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
        ];

        $request->validate([
            'socio_id' => 'required|integer|exists:socios,id',
            'concepto' => 'required|string|min:5|max:255',
            'tipo_id' => 'required|integer|exists:tipos_recibos,id',
            'importe' => 'required|numeric|min:0.01',
        ], $mensajes);

        try {
            $recibo->update([
                'socio_id' => $request->socio_id,
                'concepto' => $request->concepto,
                'tipo_id' => $request->tipo_id,
                'importe' => $request->importe,
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
            $recibo->update(['estado_id' => 3]);
            return redirect()->route('recibo.index')->with('success', 'Recibo anulado correctamente');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'No se pudo anular el recibo: ' . $e->getMessage());
        }
    }
    public function pagarRecibo(Request $request,$id)
    {
        $recibo = Recibo::findOrFail($id);
        
        $recibo->update([
            'estado_id' => 1,
            'metodo_id' => $request->metodo_id,
            'fecha_pago' => Carbon::now(),
        ]);

        if ($recibo->tipo_id==1) {
            $recibo->socio->update([
                'estado_cuota' => 1,
                'fecha_vencimiento' => now()->addYear()
            ]);
        }

        return redirect()->back()->with('success', 'Pago registrado correctamente.');
    }
}