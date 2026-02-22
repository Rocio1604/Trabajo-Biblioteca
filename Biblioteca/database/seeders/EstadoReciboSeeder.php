<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EstadoReciboSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('estados_recibos')->insert([
            ['nombre' => 'Pagado'],
            ['nombre' => 'Pendiente'],
            ['nombre' => 'Anulado'],
        ]);
    }
}