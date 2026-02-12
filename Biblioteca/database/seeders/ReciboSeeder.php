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
            $recibos[] = [
                'socio_id' => rand(1, 8),
                'concepto' => 'Cuota anual 2024',
                'tipo_id' => 1,
                'importe' => 30.00,
                'fecha' => Carbon::now()->subDays(rand(1, 365)),
                'estado_id' => rand(1, 2),
                'es_activo' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }
        
        DB::table('recibos')->insert($recibos);
    }
}