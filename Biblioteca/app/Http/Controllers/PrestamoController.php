<?php

namespace App\Http\Controllers;

use App\Models\Prestamo;
use App\Models\Socio;
use App\Models\Ejemplare;
use App\Models\EstadoPrestamo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PrestamoController extends Controller
{
    public function index()
    {
        
        $user = Auth::user();

        // Filtrar préstamos por biblioteca del usuario
        $prestamos = Prestamo::with(['socio', 'ejemplar.libro', 'biblioteca', 'estado'])
            ->where('biblioteca_id', $user->biblioteca_id)
            ->latest()
            ->get();

        $estados = EstadoPrestamo::all();

        return view('prestamo', compact('prestamos', 'estados'));
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

        $query = Prestamo::with(['socio', 'ejemplar.libro', 'biblioteca', 'estado'])
            ->where('biblioteca_id', $user->biblioteca_id);

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

        $prestamos = $query->get();

        return response()->json($prestamos);
    }

    public function store(Request $request)
    {
        $user = Auth::user();

        $mensajes = [
            'socio_id.required'         => 'Debes seleccionar un socio',
            'socio_id.exists'           => 'El socio seleccionado no existe',
            'ejemplar_id.required'      => 'Debes seleccionar un ejemplar',
            'ejemplar_id.exists'        => 'El ejemplar seleccionado no existe',
            'fecha_prestamo.required'   => 'La fecha de préstamo es obligatoria',
            'fecha_prestamo.date'       => 'La fecha de préstamo no es válida',
            'fecha_devolucion.required' => 'La fecha de devolución es obligatoria',
            'fecha_devolucion.date'     => 'La fecha de devolución no es válida',
            'fecha_devolucion.after'    => 'La fecha de devolución debe ser posterior a la fecha de préstamo',
            'estado_id.required'        => 'Debes seleccionar un estado'
        ];

        $request->validate([
            'socio_id'        => 'required|integer|exists:socios,id',
            'ejemplar_id'     => 'required|integer|exists:ejemplar,id',
            'fecha_prestamo'  => 'required|date',
            'fecha_devolucion'=> 'required|date|after:fecha_prestamo',
            'estado_id'       => 'required|integer|exists:estados_prestamos,id',
            'multa'           => 'nullable|numeric|min:0'
        ], $mensajes);

        try {
            // Verificar que el ejemplar pertenece a la biblioteca del usuario
            $ejemplar = Ejemplare::where('id', $request->ejemplar_id)
                ->where('biblioteca_id', $user->biblioteca_id)
                ->firstOrFail();

            // Verificar que el socio pertenece a la biblioteca del usuario
            $socio = Socio::where('id', $request->socio_id)
                ->where('biblioteca_id', $user->biblioteca_id)
                ->firstOrFail();

            $prestamos = Prestamo::create([
                'socio_id'        => $request->socio_id,
                'ejemplar_id'     => $request->ejemplar_id,
                'biblioteca_id'   => $user->biblioteca_id,
                'fecha_prestamo'  => $request->fecha_prestamo,
                'fecha_devolucion'=> $request->fecha_devolucion,
                'multa'           => $request->multa ?? 0,
                'estado_id'       => $request->estado_id
            ]);

            // Marcar ejemplar como prestado (2 = prestado)
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
        $user = Auth::user();

        // Verificar que el préstamo pertenece a la biblioteca del usuario
        $prestamo = Prestamo::where('biblioteca_id', $user->biblioteca_id)
            ->findOrFail($id);

        $mensajes = [
            'socio_id.required'         => 'Debes seleccionar un socio',
            'ejemplar_id.required'      => 'Debes seleccionar un ejemplar',
            'fecha_prestamo.required'   => 'La fecha de préstamo es obligatoria',
            'fecha_devolucion.required' => 'La fecha de devolución es obligatoria',
            'fecha_devolucion.after'    => 'La fecha de devolución debe ser posterior a la fecha de préstamo',
            'estado_id.required'        => 'Debes seleccionar un estado'
        ];

        $request->validate([
            'socio_id'        => 'required|integer|exists:socios,id',
            'ejemplar_id'     => 'required|integer|exists:ejemplar,id',
            'fecha_prestamo'  => 'required|date',
            'fecha_devolucion'=> 'required|date|after:fecha_prestamo',
            'estado_id'       => 'required|integer|exists:estados_prestamos,id',
            'multa'           => 'nullable|numeric|min:0'
        ], $mensajes);

        try {
            $ejemplarAnterior = $prestamo->ejemplar_id;
            $ejemplarNuevo    = $request->ejemplar_id;
            $estadoNuevo      = $request->estado_id;

            $prestamo->update([
                'socio_id'        => $request->socio_id,
                'ejemplar_id'     => $request->ejemplar_id,
                'fecha_prestamo'  => $request->fecha_prestamo,
                'fecha_devolucion'=> $request->fecha_devolucion,
                'multa'           => $request->multa ?? 0,
                'estado_id'       => $request->estado_id
            ]);

            // Si cambió el ejemplar, actualizar disponibilidades
            if ($ejemplarAnterior != $ejemplarNuevo) {
                // Liberar ejemplar anterior (1 = disponible)
                Ejemplare::where('id', $ejemplarAnterior)->update(['disponibilidad_id' => 1]);
                // Marcar nuevo ejemplar como prestado (2 = prestado)
                Ejemplare::where('id', $ejemplarNuevo)->update(['disponibilidad_id' => 2]);
            }

            // Si el préstamo se devuelve, liberar el ejemplar (estado 2 = devuelto)
            if ($estadoNuevo == 2) {
                Ejemplare::where('id', $ejemplarNuevo)->update(['disponibilidad_id' => 1]);
            }

            return redirect()->route('prestamo.index')->with('success', 'Préstamo actualizado correctamente');
        } catch (\Exception $e) {
            return redirect()->route('prestamo.index')->with('error', 'Error al actualizar: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        $user = Auth::user();

        // Verificar que el préstamo pertenece a la biblioteca del usuario
        $prestamo = Prestamo::where('biblioteca_id', $user->biblioteca_id)
            ->findOrFail($id);

        try {
            // Liberar el ejemplar antes de eliminar (1 = disponible)
            Ejemplare::where('id', $prestamo->ejemplar_id)
                ->update(['disponibilidad_id' => 1]);

            $prestamo->delete();

            return redirect()->route('prestamo.index')->with('success', 'Préstamo eliminado correctamente');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'No se pudo eliminar el préstamo: ' . $e->getMessage());
        }
    }
}