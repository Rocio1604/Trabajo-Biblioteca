<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EjemplarSeeder extends Seeder
{
    public function run(): void
    {
        $ejemplares = [];
        
        // Crear 3 ejemplares de cada libro distribuidos en diferentes bibliotecas
        for ($libro_id = 1; $libro_id <= 6; $libro_id++) {
            for ($i = 1; $i <= 3; $i++) {
                $biblioteca_id = (($libro_id + $i - 2) % 5) + 1;
                $ejemplares[] = [
                    'libro_id' => $libro_id,
                    'biblioteca_id' => $biblioteca_id,
                    'estado_id' => rand(1, 2), // Nuevo o Bueno
                    'disponibilidad_id' => rand(1, 2), // Disponible o Prestado
                    'es_activo' => true,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
        }
        
        DB::table('ejemplares')->insert($ejemplares);
    }
}