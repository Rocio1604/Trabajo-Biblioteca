<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        //bibliotecas
        Schema::create('bibliotecas', function (Blueprint $table) {
            $table->id('id');
            $table->string('provincia');
            $table->string('direccion');
            $table->string('telefono');
            $table->string('correo');
            $table->timestamps();
        });

        //socios
        Schema::create('socios', function (Blueprint $table) {
            $table->id('id');
            $table->string('dni')->unique();
            $table->string('nombre');
            $table->unsignedBigInteger('biblioteca_id');
            $table->string('email');
            $table->string('telefono');
            $table->timestamps();

            $table->foreign('biblioteca_id')
                  ->references('id')
                  ->on('bibliotecas')
                  ->onDelete('cascade');
        });
        //autores
        Schema::create('autores',function(Blueprint $table){
            $table->id();
            $table->string('nombre');
            $table->date('fecha_nacimiento');
            $table->timestamps();

        });

        //categorias
        Schema::create('categorias',function(Blueprint $table){
            $table->id();
            $table->string('categoria');
            $table->timestamps();

        });
        //libros
        Schema::create('libros',function(Blueprint $table){
            $table->id();
            $table->string('isbn');
            $table->string('titulo');
            $table->string('titulo');
            $table->string('imagen')->nullable();
            $table->unsignedBigInteger('autor_id');
            $table->unsignedBigInteger('categoria_id');
            $table->unsignedBigInteger('biblioteca_id');
            $table->timestamps();

            
            $table->foreign('categoria_id')->references('id')->on('categorias')->onDelete('cascade');
            $table->foreign('biblioteca_id')->references('id')->on('biblioteca')->onDelete('cascade');
            

        });
        //tabla intermedia libros-autores
        Schema::create('libros_autores',function(Blueprint $table){
            $table->id();
            $table->unsignedBigInteger('autor_id');
            $table->unsignedBigInteger('libro_id');
            $table->timestamps();

            $table->foreign('autor_id')->references('id')->on('autores')->onDelete('cascade');
            $table->foreign('libro_id')->references('id')->on('libros')->onDelete('cascade');
            

        });
        //tabla intermedia libros-categoria
        Schema::create('libros_categoria',function(Blueprint $table){
            $table->id();
            $table->unsignedBigInteger('categoria_id');
            $table->unsignedBigInteger('libro_id');
            $table->timestamps();

            $table->foreign('categoria_id')->references('id')->on('categorias')->onDelete('cascade');
            $table->foreign('libro_id')->references('id')->on('libros')->onDelete('cascade');
            

        });

        //prestamos
        Schema::create('prestamos', function (Blueprint $table) {
            $table->id('id');
            $table->unsignedBigInteger('socio_id');
            $table->unsignedBigInteger('libro_id');
            $table->date('fecha_prestamo');
            $table->date('fecha_limite');
            $table->date('fecha_devolucion')->nullable();
            $table->timestamps();

            $table->foreign('socio_id')
                  ->references('id')
                  ->on('socios')
                  ->onDelete('cascade');

            $table->foreign('libro_id')
                  ->references('id')
                  ->on('libros')
                  ->onDelete('cascade');
        });
        //recibos
        Schema::create('recibos', function (Blueprint $table) {
            $table->id('id');
            $table->unsignedBigInteger('socio_id');
            $table->string('concepto');
            $table->string('tipo');
            $table->decimal('importe', 8, 2);
            $table->date('fecha');
            $table->timestamps();

            $table->foreign('socio_id')
                  ->references('id')
                  ->on('socios')
                  ->onDelete('cascade');
        });
        //usuarios
        Schema::create('usuarios', function (Blueprint $table) {
            $table->id('id');
            $table->string('nombre');
            $table->string('correo')->unique();
            $table->string('telefono');
            $table->string('rol');
            $table->string('biblioteca');
            $table->string('estado');
            $table->timestamps();
        });
        // coches
        Schema::create('coches', function (Blueprint $table) {
            $table->unsignedBigInteger('id_usuario');
            $table->string('pass');
            $table->timestamps();

            $table->primary('id_usuario');

            $table->foreign('id_usuario')
                  ->references('id')
                  ->on('usuarios')
                  ->onDelete('cascade');
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
