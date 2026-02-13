<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EstadoLibroSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('estados_libros')->insert([
            ['nombre' => 'Nuevo'],
            ['nombre' => 'Bueno'],
            ['nombre' => 'Desgastado'],
            ['nombre' => 'Roto'],
        ]);
    }
}