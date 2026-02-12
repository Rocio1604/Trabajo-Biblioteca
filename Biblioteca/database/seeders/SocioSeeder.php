<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SocioSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('socios')->insert([
            [
                'dni' => '12345678A',
                'nombre' => 'Juan García Pérez',
                'biblioteca_id' => 1,
                'email' => 'juan.garcia@email.com',
                'telefono' => '600123456',
                'estado_cuota' => 1,
                'es_activo' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'dni' => '23456789B',
                'nombre' => 'María López Martínez',
                'biblioteca_id' => 1,
                'email' => 'maria.lopez@email.com',
                'telefono' => '600234567',
                'estado_cuota' => 1,
                'es_activo' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'dni' => '34567890C',
                'nombre' => 'Carlos Rodríguez Sánchez',
                'biblioteca_id' => 2,
                'email' => 'carlos.rodriguez@email.com',
                'telefono' => '600345678',
                'estado_cuota' => 2,
                'es_activo' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'dni' => '45678901D',
                'nombre' => 'Ana Fernández Gómez',
                'biblioteca_id' => 2,
                'email' => 'ana.fernandez@email.com',
                'telefono' => '600456789',
                'estado_cuota' => 1,
                'es_activo' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'dni' => '56789012E',
                'nombre' => 'Pedro Martín Ruiz',
                'biblioteca_id' => 3,
                'email' => 'pedro.martin@email.com',
                'telefono' => '600567890',
                'estado_cuota' => 1,
                'es_activo' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'dni' => '67890123F',
                'nombre' => 'Laura Jiménez Díaz',
                'biblioteca_id' => 3,
                'email' => 'laura.jimenez@email.com',
                'telefono' => '600678901',
                'estado_cuota' => 3,
                'es_activo' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'dni' => '78901234G',
                'nombre' => 'Miguel Álvarez Torres',
                'biblioteca_id' => 4,
                'email' => 'miguel.alvarez@email.com',
                'telefono' => '600789012',
                'estado_cuota' => 1,
                'es_activo' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'dni' => '89012345H',
                'nombre' => 'Isabel Moreno Castro',
                'biblioteca_id' => 5,
                'email' => 'isabel.moreno@email.com',
                'telefono' => '600890123',
                'estado_cuota' => 1,
                'es_activo' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}