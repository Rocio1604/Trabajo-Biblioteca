<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BibliotecaSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('bibliotecas')->insert([
            [
                'nombre' => 'Biblioteca Central de Madrid',
                'provincia' => 'Madrid',
                'direccion' => 'Calle Mayor, 1',
                'telefono' => '911234567',
                'correo' => 'central@biblioteca.madrid',
                'es_activo' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nombre' => 'Biblioteca Municipal de Barcelona',
                'provincia' => 'Barcelona',
                'direccion' => 'Paseo de Gracia, 50',
                'telefono' => '931234567',
                'correo' => 'municipal@biblioteca.barcelona',
                'es_activo' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nombre' => 'Biblioteca Pública de Valencia',
                'provincia' => 'Valencia',
                'direccion' => 'Avenida del Puerto, 23',
                'telefono' => '961234567',
                'correo' => 'publica@biblioteca.valencia',
                'es_activo' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nombre' => 'Biblioteca de Sevilla',
                'provincia' => 'Sevilla',
                'direccion' => 'Plaza Nueva, 8',
                'telefono' => '951234567',
                'correo' => 'info@biblioteca.sevilla',
                'es_activo' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nombre' => 'Biblioteca Provincial de Zaragoza',
                'provincia' => 'Zaragoza',
                'direccion' => 'Calle Alfonso I, 12',
                'telefono' => '976234567',
                'correo' => 'provincial@biblioteca.zaragoza',
                'es_activo' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}