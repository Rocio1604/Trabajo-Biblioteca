<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ReciboSeeder extends Seeder
{
    public function run(): void
    {
        $recibos = [];
        
        for ($i = 1; $i <= 15; $i++) {
            $tipoId = rand(1, 4); 
            
            $conceptos = [
                1 => 'Cuota anual 2024',
                2 => 'Retraso en devolución de libro',
                3 => 'Libro extraviado - ISBN...',
                4 => 'Reserva de sala de estudio'
            ];
            
            $importes = [
                1 => 30.00,  
                2 => 15.00,  
                3 => 25.00, 
                4 => 10.00  
            ];
            
            $recibos[] = [
                'socio_id' => rand(1, 7),
                'concepto' => $conceptos[$tipoId],
                'tipo_id' => $tipoId,
                'importe' => $importes[$tipoId],
                'fecha' => Carbon::now()->subDays(rand(1, 365)),
                'estado_id' => rand(1, 2),
                'es_activo' => rand(0, 10) > 1, 
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }
        
        DB::table('recibos')->insert($recibos);
    }
}