<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\recibos;
use App\Models\socios;
class RecibosController extends Controller
{
      public function index() {
        $prestamos = Recibos::with(['socios'])->get();
        
        return view('home',compact('recibos'));
    }
    public function create() {
        $socios = socios::all();
        $recibos = recibos::all();

        return view('crearrecibos', compact('recibos','socios'));
    }
    public function store(Request $request) {

        $request->validate([
            'recibo' => 'required',
            'id_socio' => 'required'
        ]);

         Recibos::create($request->all()); 

        return redirect()->route('recibo.index')->with('funciona', 'recibo guardado correctamente');
    }


    public function destroy($id) {
        Recibos::destroy($id);
        return redirect()->route('recibo.index')->with('funciona', 'Recibo eliminado');
    }
}
