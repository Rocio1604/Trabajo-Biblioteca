<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class PrestamoSeeder extends Seeder
{
    public function run(): void
    {
        $prestamos = [];
        
        // Crear 20 préstamos de ejemplo
        for ($i = 1; $i <= 20; $i++) {
            $fecha_prestamo = Carbon::now()->subDays(rand(1, 60));
            $fecha_devolucion = (clone $fecha_prestamo)->addDays(15);
            $ejemplar_id = rand(1, 18);
            
            // Obtener la biblioteca del ejemplar
            $ejemplar = DB::table('ejemplares')->where('id', $ejemplar_id)->first();
            
            $prestamos[] = [
                'socio_id' => rand(1, 7),
                'ejemplar_id' => $ejemplar_id,
                'biblioteca_id' => $ejemplar->biblioteca_id, // ← AGREGADO
                'fecha_prestamo' => $fecha_prestamo,
                'fecha_devolucion' => $fecha_devolucion,
                'multa' => rand(0, 1) ? 0 : rand(5, 30),
                'estado_id' => rand(1, 2), // Activo o Devuelto
                'created_at' => $fecha_prestamo,
                'updated_at' => now(),
            ];
        }
        
        DB::table('prestamos')->insert($prestamos);
    }
}