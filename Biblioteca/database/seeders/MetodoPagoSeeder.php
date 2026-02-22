<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MetodoPagoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('metodos_pago')->insert([
                ['nombre' => 'Efectivo'],
                ['nombre' => 'Tarjeta'],
            ]);
    }
}
