<?php

namespace App\Http\Controllers;

use App\Models\Biblioteca;
use App\Models\Prestamo;
use App\Models\Socio;
use App\Models\Ejemplare;
use App\Models\EstadoPrestamo;
use App\Models\Recibo;
use Illuminate\Support\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PrestamoController extends Controller
{
    public function index()
    {
        Prestamo::actualizarAtrasados();
        $user = Auth::user();
        $prestamos = Prestamo::with(['socio', 'ejemplar.libro', 'biblioteca', 'estado'])
            ->orderBy('es_activo', 'desc')->latest()->get();

        if($user->id==1 || $user->rol_id==1){
            $socios = Socio::where('es_activo', true)->get();
        }else{
            $socios = Socio::where('biblioteca_id', $user->biblioteca_id)
            ->where('es_activo', true)
            ->get();
        }

        $bibliotecas=Biblioteca::where('es_activo',true)->get();

        $ejemplaresEnUsoIds = $prestamos->pluck('ejemplar_id')->toArray();

        $ejemplares = Ejemplare::with('libro')
                ->get();
        
        $estados = EstadoPrestamo::all();

        return view('prestamo', compact('prestamos', 'estados','socios','ejemplares','bibliotecas'));
    }

    public function create()
    {
        $user = Auth::user();

        // Filtrar socios y ejemplares por biblioteca del usuario
        $socios = Socio::where('biblioteca_id', $user->biblioteca_id)
            ->where('es_activo', true)
            ->get();

        $ejemplares = Ejemplare::with('libro')
            ->where('biblioteca_id', $user->biblioteca_id)
            ->where('es_activo', true)
            ->where('disponibilidad_id', 1) // 1 = disponible
            ->get();

        $estados = EstadoPrestamo::all();

        return view('prestamo-crear', compact('socios', 'ejemplares', 'estados'));
    }

    public function buscar(Request $request)
    {
        $user = Auth::user();
        $busqueda = $request->busqueda;
        $estado = $request->estado;
        $biblioteca=$request->biblioteca;

        $query = Prestamo::with(['socio', 'ejemplar.libro', 'biblioteca', 'estado'])->orderBy('es_activo', 'desc')->latest();

        if ($busqueda) {
            $idExtraido = null;
            if (preg_match('/PRES-\d{4}-(\d+)/', $busqueda, $matches)) {
                $idExtraido = (int)$matches[1];
            }

            $query->where(function($q) use ($busqueda, $idExtraido) {
                $q->whereHas('socio', function($subQ) use ($busqueda) {
                    $subQ->where('nombre', 'LIKE', "%$busqueda%");
                })
                ->orWhereHas('ejemplar.libro', function($subQ) use ($busqueda) {
                    $subQ->where('titulo', 'LIKE', "%$busqueda%");
                });

                if ($idExtraido) {
                    $q->orWhere('id', $idExtraido);
                } else {
                    $q->orWhere('id', 'LIKE', "%$busqueda%");
                }
            });
        }

        if ($estado && $estado !== 'todos') {
            $query->where('estado_id', $estado);
        }
        if ($biblioteca && $biblioteca !== 'todos') {
            $query->where('biblioteca_id', $biblioteca);
        }

        $prestamos = $query->get();

        return response()->json($prestamos);
    }

    public function store(Request $request)
    {
        $user = Auth::user();
        $socio = Socio::findOrFail($request->socio_id);
        $mensajes = [
            'socio_id.required'         => 'Debes seleccionar un socio',
            'socio_id.exists'           => 'El socio seleccionado no existe',
            'ejemplar_id.required'      => 'Debes seleccionar un ejemplar',
            'ejemplar_id.exists'        => 'El ejemplar seleccionado no existe',
            'fecha_devolucion.required' => 'La fecha de devolución es obligatoria',
            'fecha_devolucion.date'     => 'La fecha de devolución no es válida',
            'fecha_devolucion.after' => 'La fecha de devolución debe ser posterior a hoy',
        ];

        $request->validate([
            'socio_id'        => 'required|integer|exists:socios,id',
            'ejemplar_id'     => 'required|integer|exists:ejemplares,id',
            'fecha_devolucion'=> 'required|date|after:today',
        ], $mensajes);

        if ($socio->estado_cuota == 2) {
            return back()->withErrors(['socio_id' => 'El socio tiene la cuota vencida.'])->withInput();
        }

        $prestamosActivos = Prestamo::where('socio_id', $request->socio_id)
            ->whereIn('estado_id', [1, 3])
            ->count();

        if ($prestamosActivos >= 3) {
            return back()->withErrors(['socio_id' => 'El socio ya tiene el máximo de 3 libros prestados.'])->withInput();
        }

        $tieneAtrasos = Prestamo::where('socio_id', $request->socio_id)
            ->where('estado_id', 3)
            ->exists();

        if ($tieneAtrasos) {
            return back()->withErrors(['socio_id' => 'El socio tiene prestamo atrasado, no se le puede prestar más.'])->withInput();
        }

        $bibliotecaId = ($user->id == 1 || $user->rol_id == 1) 
                    ? $socio->biblioteca_id 
                    : $user->biblioteca_id;

        try {
            $ejemplar = Ejemplare::where('id', $request->ejemplar_id)
                ->firstOrFail();
            Prestamo::create([
                'socio_id'        => $request->socio_id,
                'ejemplar_id'     => $request->ejemplar_id,
                'biblioteca_id'   => $bibliotecaId,
                'fecha_prestamo'  => now()->format('Y-m-d'),
                'fecha_devolucion'=> $request->fecha_devolucion,
                'multa'           => 0,
                'estado_id'       => 1
            ]);


            $ejemplar->update(['disponibilidad_id' => 2]);

            return redirect()->route('prestamo.index')->with('success', 'Préstamo creado correctamente');
        } catch (\Exception $e) {
            return redirect()->route('prestamo.index')->with('error', 'Error al crear el préstamo: ' . $e->getMessage());
        }
    }

    public function edit($id)
    {
        $user = Auth::user();

        // Verificar que el préstamo pertenece a la biblioteca del usuario
        $prestamo = Prestamo::where('biblioteca_id', $user->biblioteca_id)
            ->with(['socio', 'ejemplar.libro', 'estado'])
            ->findOrFail($id);

        $socios = Socio::where('biblioteca_id', $user->biblioteca_id)
            ->where('es_activo', true)
            ->get();

        $ejemplares = Ejemplare::with('libro')
            ->where('biblioteca_id', $user->biblioteca_id)
            ->where('es_activo', true)
            ->get();

        $estados = EstadoPrestamo::all();

        return view('prestamo-editar', compact('prestamo', 'socios', 'ejemplares', 'estados'));
    }

    public function update(Request $request, $id)
    {

        $prestamo = Prestamo::findOrFail($id);

        $mensajes = [
            'socio_id.required'         => 'Debes seleccionar un socio',
            'ejemplar_id.required'      => 'Debes seleccionar un ejemplar',
            'fecha_nacimiento.date' => 'Debe ingresar una fecha válida',
            'fecha_devolucion.required' => 'La fecha de devolución es obligatoria',
            'fecha_devolucion.after' => 'La fecha de devolución debe ser posterior a hoy',
        ];

        $request->validate([
            'socio_id'        => 'required|integer|exists:socios,id',
            'ejemplar_id'     => 'required|integer|exists:ejemplares,id',
            'fecha_devolucion'=> 'required|date|after:today',
        ], $mensajes);

        try {
            $ejemplarAnterior = $prestamo->ejemplar_id;
            $ejemplarNuevo    = $request->ejemplar_id;

            $prestamo->update($request->all());

            // Si cambió el ejemplar, actualizar disponibilidades
            if ($ejemplarAnterior != $ejemplarNuevo) {
                // Liberar ejemplar anterior (1 = disponible)
                Ejemplare::where('id', $ejemplarAnterior)->update(['disponibilidad_id' => 1]);
                // Marcar nuevo ejemplar como prestado (2 = prestado)
                Ejemplare::where('id', $ejemplarNuevo)->update(['disponibilidad_id' => 2]);
            }

            return redirect()->route('prestamo.index')->with('success', 'Préstamo actualizado correctamente');
        } catch (\Exception $e) {
            return redirect()->route('prestamo.index')->with('error', 'Error al actualizar: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {

        $prestamo = Prestamo::findOrFail($id);

        try {

            if (($prestamo->estado_id == 1) || ($prestamo->estado_id == 2 && !$prestamo->recibos()->exists())) {
                if($prestamo->estado_id == 1) {
                    $prestamo->ejemplar()->update(['disponibilidad_id' => 1]);
                }

                $prestamo->delete();
                return back()->with('success', 'Prestamo eliminado permanentemente.');
            }
            if($prestamo->estado_id==3){
                Ejemplare::where('id', $prestamo->ejemplar_id)
                    ->update(['disponibilidad_id' => 1]);
            }
            $prestamo->update(['es_activo' => 0]);

            return redirect()->route('prestamo.index')->with('success', 'Préstamo desactivado correctamente');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'No se pudo eliminar el préstamo: ' . $e->getMessage());
        }
    }

    public function devolver(Request $request, $id)
    {
        $user = Auth::user();
        $prestamo = Prestamo::findOrFail($id);
        $monto = intval($request->monto_multa);

        $prestamo->update([
            'estado_id' => 2,
            'multa' => $monto,
            'fecha_devolucion_real' => Carbon::now(),
        ]);
        $prestamo->ejemplar->update([
            'disponibilidad_id' => 1 
        ]);
        

        if ($monto > 0) {
            Recibo::create([
                'socio_id' => $prestamo->socio_id,
                'biblioteca_id' => $user->biblioteca_id,
                'concepto' => 'Retraso en devolución de libro',
                'tipo_id' =>2,
                'importe' => $monto,
                'fecha' => Carbon::now(),
                'estado_id' => 2,
                'prestamo_id' => $prestamo->id,
                'es_activo' => 1,
            ]);

            return redirect()->route('prestamo.index')
                ->with('success', "Libro devuelto con éxito. Se generó una multa de €$monto.");
        }

        return redirect()->route('prestamo.index')
            ->with('success', 'Libro devuelto con éxito.');
    }
    public function marcarPerdido(Request $request, $id)
    {
        $user = Auth::user();
        $prestamo = Prestamo::with(['ejemplar.libro', 'socio'])->findOrFail($id);

        $vecesPerdido = Prestamo::where('socio_id', $prestamo->socio_id)
        ->where('estado_id', 4)
        ->where('id', '!=', $id)
        ->count();

        $prestamo->update([
            'estado_id' => 4,
            'multa' => $request->multa_atraso + $request->precio_libro
        ]);

        $prestamo->ejemplar->update(['disponibilidad_id' => 3]);

        Recibo::create([
            'socio_id' => $prestamo->socio_id,
            'biblioteca_id' => $user->biblioteca_id,
            'prestamo_id' => $prestamo->id,
            'concepto' => "Libro perdido",
            'tipo_id' => 2,
            'importe' => $vecesPerdido?$request->precio_libro:5,
            'fecha' => Carbon::now(),
            'estado_id' => 2,
            'es_activo' => true
        ]);

        if ($request->multa_atraso > 0) {
            Recibo::create([
                'socio_id' => $prestamo->socio_id,
                'biblioteca_id' => $user->biblioteca_id,
                'prestamo_id' => $prestamo->id,
                'concepto' => "Retraso en devolución de libro",
                'tipo_id' => 2,
                'importe' => $request->multa_atraso,
                'fecha' => Carbon::now(),
                'estado_id' => 2,
                'es_activo' => true,
            ]);
        }

        return redirect()->route('prestamo.index')->with('success', 'Se registró la pérdida y se generó los recibos.');
    }

    public function marcarEncontrado($id)
    {
        $prestamo = Prestamo::findOrFail($id);

        $prestamo->ejemplar->update(['disponibilidad_id' => 1]);

        $prestamo->update([
            'estado_id' => 2,
            'fecha_devolucion_real' =>Carbon::now(),
        ]);

        return redirect()->route('prestamo.index')->with('success', 'Libro devuelto con exito');
    }
    public function reactivar($id)
    {
        $prestamo = Prestamo::findOrFail($id);

        if ($prestamo) {
            $prestamo->es_activo = 1;
            $prestamo->save();
        }

        return redirect()->route('prestamo.index')
            ->with('success', 'Prestamo reactivado correctamente');
    }
}