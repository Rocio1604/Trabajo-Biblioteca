<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DisponibilidadLibroSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('disponibilidades_libros')->insert([
            ['nombre' => 'Disponible'],
            ['nombre' => 'Prestado'],
        ]);
    }
}