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
            $table->boolean('es_activo');
            $table->timestamps();
        });

        Schema::create('estados_cuotas', function (Blueprint $table) {
            $table->id('id');
            $table->string('nombre');
        });

        //socios
        Schema::create('socios', function (Blueprint $table) {
            $table->id('id');
            $table->string('dni')->unique();
            $table->string('nombre');
            $table->unsignedBigInteger('biblioteca_id');
            $table->string('email');
            $table->string('telefono');
            $table->unsignedBigInteger('estado_cuota');
            $table->timestamps();

            $table->foreign('biblioteca_id')
                  ->references('id')
                  ->on('bibliotecas')
                  ->onDelete('cascade');

            $table->foreign('estado_cuota')
                  ->references('id')
                  ->on('estados_cuotas')
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
            $table->string('nombre');
        });

        //estado de libros
        Schema::create('estados_libros',function(Blueprint $table){
            $table->id();
            $table->string('nombre');

        });

        //disponibilidad libros
        Schema::create('disponibilidades_libros',function(Blueprint $table){
            $table->id();
            $table->string('nombre');

        });

        //libros
        Schema::create('libros',function(Blueprint $table){
            $table->id();
            $table->string('isbn')->unique();
            $table->string('titulo');
            $table->unsignedBigInteger('categoria_id');
            $table->decimal('precio', 8, 2);
            $table->timestamps();

            $table->foreign('categoria_id')->references('id')->on('categorias')->onDelete('cascade');
        });
        //tabla intermedia libros-autores
        Schema::create('autor_libro',function(Blueprint $table){
            $table->unsignedBigInteger('autor_id');
            $table->unsignedBigInteger('libro_id');

            $table->foreign('autor_id')->references('id')->on('autores')->onDelete('cascade');
            $table->foreign('libro_id')->references('id')->on('libros')->onDelete('cascade');
        });

        //estado de prestamos
        Schema::create('estados_prestamos',function(Blueprint $table){
            $table->id();
            $table->string('nombre');

        });

        //estado de recibos
        Schema::create('estados_recibos',function(Blueprint $table){
            $table->id();
            $table->string('nombre');

        });

        //tipo de recibos
        Schema::create('tipos_recibos',function(Blueprint $table){
            $table->id();
            $table->string('nombre');

        });

        //ejemplares
        Schema::create('ejemplares', function (Blueprint $table) {
            $table->id('id');
            $table->unsignedBigInteger('libro_id');
            $table->unsignedBigInteger('biblioteca_id');
            $table->unsignedBigInteger('estado_id');
            $table->unsignedBigInteger('disponibilidad_id');
            $table->boolean('es_activo');
            $table->timestamps();

            $table->foreign('libro_id')
                  ->references('id')
                  ->on('libros')
                  ->onDelete('cascade');

            $table->foreign('biblioteca_id')
                ->references('id')
                ->on('bibliotecas')
                ->onDelete('cascade');

            $table->foreign('estado_id')
                ->references('id')
                ->on('estados_libros')
                ->onDelete('cascade');

            $table->foreign('disponibilidad_id')
                ->references('id')
                ->on('disponibilidades_libros')
                ->onDelete('cascade');
        });

        //prestamos
        Schema::create('prestamos', function (Blueprint $table) {
            $table->id('id');
            $table->unsignedBigInteger('socio_id');
            $table->unsignedBigInteger('ejemplar_id');
            $table->unsignedBigInteger('biblioteca_id');
            $table->date('fecha_prestamo');
            $table->date('fecha_devolucion');
            $table->decimal('multa', 8, 2)->default(0);
            $table->unsignedBigInteger('estado_id');
            $table->timestamps();

            $table->foreign('socio_id')
                  ->references('id')
                  ->on('socios')
                  ->onDelete('cascade');

            $table->foreign('ejemplar_id')
                  ->references('id')
                  ->on('ejemplares')
                  ->onDelete('cascade');

            $table->foreign('estado_id')
                  ->references('id')
                  ->on('estados_prestamos')
                  ->onDelete('cascade');
        });

        //recibos
        Schema::create('recibos', function (Blueprint $table) {
            $table->id('id');
            $table->unsignedBigInteger('socio_id');
            $table->string('concepto');
            $table->unsignedBigInteger('tipo_id');
            $table->decimal('importe', 8, 2);
            $table->date('fecha');
            $table->unsignedBigInteger('estado_id');
            $table->timestamps();

            $table->foreign('socio_id')
                  ->references('id')
                  ->on('socios')
                  ->onDelete('cascade');

            $table->foreign('tipo_id')
                ->references('id')
                ->on('tipos_recibos')
                ->onDelete('cascade');

            $table->foreign('estado_id')
                ->references('id')
                ->on('estados_recibos')
                ->onDelete('cascade');
        });

        //roles
        Schema::create('roles', function (Blueprint $table) {
            $table->id('id');
            $table->string('nombre');
        });

        //usuarios
        Schema::create('usuarios', function (Blueprint $table) {
            $table->id('id');
            $table->string('nombre');
            $table->string('correo')->unique();
            $table->string('telefono');
            $table->unsignedBigInteger('rol_id');
            $table->unsignedBigInteger('biblioteca_id');
            $table->boolean('es_activo');
            $table->timestamps();

            $table->foreign('rol_id')
                ->references('id')
                ->on('roles')
                ->onDelete('cascade');

             $table->foreign('biblioteca_id')
                ->references('id')
                ->on('bibliotecas')
                ->onDelete('cascade');
        });
        // coches
        Schema::create('validaciones_sistema', function (Blueprint $table) {
            $table->unsignedBigInteger('referencia_id');
            $table->string('firma_digital');

            $table->foreign('referencia_id')
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
        Schema::dropIfExists('validaciones_sistema');
        Schema::dropIfExists('usuarios');
        Schema::dropIfExists('roles');
        Schema::dropIfExists('ejemplares');
        Schema::dropIfExists('recibos');
        Schema::dropIfExists('prestamos');
        Schema::dropIfExists('tipos_recibos');
        Schema::dropIfExists('estados_recibos');
        Schema::dropIfExists('estados_prestamos');
        Schema::dropIfExists('autor_libro');
        Schema::dropIfExists('libros');
        Schema::dropIfExists('disponibilidades_libros');
        Schema::dropIfExists('estados_libros');
        Schema::dropIfExists('categorias');
        Schema::dropIfExists('autores');
        Schema::dropIfExists('socios');
        Schema::dropIfExists('estados_cuotas');
        Schema::dropIfExists('bibliotecas');
    }
};
