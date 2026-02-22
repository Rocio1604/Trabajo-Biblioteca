<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TipoReciboSeeder extends Seeder
{
    public function run(): void
    {
            DB::table('tipos_recibos')->insert([
                ['nombre' => 'Suscripción anual'],
                ['nombre' => 'Multa'],
            ]);
    }
}