<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AutorSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('autores')->insert([
            [
                'nombre' => 'Miguel de Cervantes',
                'fecha_nacimiento' => '1547-09-29',
                'es_activo' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nombre' => 'Gabriel García Márquez',
                'fecha_nacimiento' => '1927-03-06',
                'es_activo' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nombre' => 'Isabel Allende',
                'fecha_nacimiento' => '1942-08-02',
                'es_activo' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nombre' => 'Jorge Luis Borges',
                'fecha_nacimiento' => '1899-08-24',
                'es_activo' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nombre' => 'Pablo Neruda',
                'fecha_nacimiento' => '1904-07-12',
                'es_activo' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nombre' => 'Julio Cortázar',
                'fecha_nacimiento' => '1914-08-26',
                'es_activo' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}