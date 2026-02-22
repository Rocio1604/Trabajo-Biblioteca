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
            $tipoId = rand(1, 2); 
            
            $conceptos = [
                1 => 'Cuota anual 2024',
                2 => 'Retraso en devolución de libro'
            ];
            
            $importes = [
                1 => 30.00,  
                2 => 15.00,  
                3 => 25.00, 
                4 => 10.00  
            ];
            
            $recibos[] = [
                'socio_id' => rand(1, 7),
                'biblioteca_id' => rand(1, 5),
                'concepto' => $conceptos[$tipoId],
                'tipo_id' => $tipoId,
                'importe' => $importes[$tipoId],
                'fecha' => Carbon::now()->subDays(rand(1, 365)),
                'estado_id' => rand(1, 3),
                'es_activo' => 1, 
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }
        
        DB::table('recibos')->insert($recibos);
    }
}