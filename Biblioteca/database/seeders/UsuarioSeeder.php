<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UsuarioSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('usuarios')->insert([
            [
                'nombre' => 'Admin Principal',
                'correo' => 'admin@biblioteca.com',
                'telefono' => '911111111',
                'rol_id' => 1,
                'biblioteca_id' => null,
                'es_activo' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nombre' => 'Bibliotecario Madrid',
                'correo' => 'bibliotecario.madrid@biblioteca.com',
                'telefono' => '912222222',
                'rol_id' => 2,
                'biblioteca_id' => 1,
                'es_activo' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nombre' => 'Bibliotecario Barcelona',
                'correo' => 'bibliotecario.bcn@biblioteca.com',
                'telefono' => '933333333',
                'rol_id' => 2,
                'biblioteca_id' => 2,
                'es_activo' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}