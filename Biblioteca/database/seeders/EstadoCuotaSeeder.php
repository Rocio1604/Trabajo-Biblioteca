<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EstadoCuotaSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('estados_cuotas')->insert([
            ['nombre' => 'Activa'],
            ['nombre' => 'Vencida'],
        ]);
    }
}