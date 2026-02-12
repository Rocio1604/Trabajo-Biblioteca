<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EstadoPrestamoSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('estados_prestamos')->insert([
            ['nombre' => 'Activo'],
            ['nombre' => 'Devuelto'],
            ['nombre' => 'Atrasado'],
            ['nombre' => 'Perdido'],
        ]);
    }
}