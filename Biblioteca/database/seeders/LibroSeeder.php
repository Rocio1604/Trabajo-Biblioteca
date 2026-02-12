<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LibroSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('libros')->insert([
            [
                'isbn' => '978-84-376-0494-7',
                'titulo' => 'Don Quijote de la Mancha',
                'categoria_id' => 1,
                'precio' => 25.50,
                'es_activo' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'isbn' => '978-84-204-2469-4',
                'titulo' => 'Cien años de soledad',
                'categoria_id' => 1,
                'precio' => 22.00,
                'es_activo' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'isbn' => '978-84-204-6664-0',
                'titulo' => 'La casa de los espíritus',
                'categoria_id' => 1,
                'precio' => 20.00,
                'es_activo' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'isbn' => '978-84-206-0392-0',
                'titulo' => 'Ficciones',
                'categoria_id' => 1,
                'precio' => 18.50,
                'es_activo' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'isbn' => '978-84-376-2604-8',
                'titulo' => 'Veinte poemas de amor y una canción desesperada',
                'categoria_id' => 6,
                'precio' => 15.00,
                'es_activo' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'isbn' => '978-84-204-2473-1',
                'titulo' => 'Rayuela',
                'categoria_id' => 1,
                'precio' => 24.00,
                'es_activo' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        // Relación libros-autores
        DB::table('autor_libro')->insert([
            ['autor_id' => 1, 'libro_id' => 1],
            ['autor_id' => 2, 'libro_id' => 2],
            ['autor_id' => 3, 'libro_id' => 3],
            ['autor_id' => 4, 'libro_id' => 4],
            ['autor_id' => 5, 'libro_id' => 5],
            ['autor_id' => 6, 'libro_id' => 6],
        ]);
    }
}