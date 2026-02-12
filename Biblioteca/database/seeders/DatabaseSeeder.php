<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            BibliotecaSeeder::class,
            EstadoCuotaSeeder::class,
            SocioSeeder::class,
            AutorSeeder::class,
            CategoriaSeeder::class,
            EstadoLibroSeeder::class,
            DisponibilidadLibroSeeder::class,
            LibroSeeder::class,
            EstadoPrestamoSeeder::class,
            EstadoReciboSeeder::class,
            TipoReciboSeeder::class,
            EjemplarSeeder::class,
            PrestamoSeeder::class,
            ReciboSeeder::class,
            RolSeeder::class,
            UsuarioSeeder::class,
        ]);
    }
}