<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\bibliotecas;
use App\Models\Prestamos;
use Illuminate\Support\Facades\DB;
class BibliotecasController extends Controller
{
    public function index() {
    $Bibliotecas = Bibliotecas::withCount('libros', 'socios')->get();
    return view('listabiblioteca', compact('Bibliotecas'));
    
    }

    
    public function create() {
        $Bibliotecas = bibliotecas::all();

        return view('crearbiblioteca', compact('Bibliotecas'));
    }

    public function store(Request $request) {

        $request->validate([
            'id_biblioteca' => 'required',
        ]);

      
        bibliotecas::create($request->all()); 

        return redirect()->route('bibliotecas.index')->with('funciona', 'Biblioteca guardado correctamente');
    }
    
    public function destroy($id) {
        $autor = bibliotecas::find($id);
        

        if ($autor->libros()->count() > 0) {
            return redirect()->route('Bibliotecas.index')->with('error', 'No se puede eliminar tiene libros.');
        }

        $autor->delete();
        return redirect()->route('bibliotecas.index')->with('funciona', 'biblioteca eliminada.');
    }

    public function prestamosBibliotecas(){

       $bibliotecasPrestamos = DB::table('bibliotecas')
            ->leftJoin('ejemplares', 'bibliotecas.id', '=', 'ejemplares.biblioteca_id')
            ->leftJoin('prestamos', 'ejemplares.id', '=', 'prestamos.ejemplar_id')
            ->leftJoin('socios', 'bibliotecas.id', '=', 'socios.biblioteca_id')
            ->select(
                'bibliotecas.id',
                'bibliotecas.nombre',
                'bibliotecas.provincia',
                DB::raw('COUNT(DISTINCT prestamos.id) as prestamos_count'),
                DB::raw('COUNT(DISTINCT socios.id) as socios_count')
            )
            ->groupBy('bibliotecas.id', 'bibliotecas.nombre', 'bibliotecas.provincia')
            ->orderBy('prestamos_count', 'desc')
            ->limit(5)
            ->get();

        $prestamosRecientes = DB::table('prestamos')
            ->join('socios', 'prestamos.socio_id', '=', 'socios.id')
            ->join('ejemplares', 'prestamos.ejemplar_id', '=', 'ejemplares.id')
            ->join('libros', 'ejemplares.libro_id', '=', 'libros.id')
            ->select(
                'prestamos.id',
                'prestamos.fecha_prestamo',
                'socios.nombre as socio_nombre',
                'libros.titulo as libro_titulo'
            )
            ->orderBy('prestamos.fecha_prestamo', 'desc')
            ->limit(5)
            ->get();
        
            return view('panelInicio',compact('bibliotecasPrestamos','prestamosRecientes'));
    }
}
