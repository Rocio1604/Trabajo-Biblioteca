<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategoriaSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('categorias')->insert([
            ['nombre' => 'Ficción'],
            ['nombre' => 'No Ficción'],
            ['nombre' => 'Ciencia'],
            ['nombre' => 'Historia'],
            ['nombre' => 'Biografía'],
            ['nombre' => 'Poesía'],
            ['nombre' => 'Teatro'],
            ['nombre' => 'Ensayo'],
        ]);
    }
}