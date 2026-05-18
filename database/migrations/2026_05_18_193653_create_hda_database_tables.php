<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Tabla USUARIO (Reemplaza a la nativa o coexiste según tu estructura)
        Schema::create('usuario', function (Blueprint $table) {
            $table->integer('ID_usuario')->autoIncrement();
            $table->string('Nombre', 100);
            $table->string('Correo_Electronico', 100)->unique();
            $table->string('password', 255);
            $table->dateTime('Fecha_Registro');
            $table->string('Rol', 50);
            $table->integer('Edad')->nullable();
            $table->string('Localidad', 150)->nullable();
        });

        // 2. Tabla TEST
        Schema::create('test', function (Blueprint $table) {
            $table->integer('ID_test')->autoIncrement();
            $table->string('Nombre', 100)->nullable();
            $table->text('Descripcion')->nullable();
            $table->string('Clasificacion', 50)->nullable();
        });

        // 3. Tabla TIPOS_DIAGNOSTICO
        Schema::create('tipos_diagnostico', function (Blueprint $table) {
            $table->integer('ID_Diagnostico')->autoIncrement();
            $table->string('Nombre', 100)->nullable();
            $table->text('Descripcion')->nullable();
            $table->string('Nivel_Riesgo', 50)->nullable();
            $table->text('Sugerencia')->nullable();
        });

        // 4. Tabla TIPOS_RESULTADOS
        Schema::create('tipos_resultados', function (Blueprint $table) {
            $table->integer('ID_T_Resultado')->autoIncrement();
            $table->string('Tipo', 50)->nullable();
            $table->text('Descripcion')->nullable();
            $table->integer('Puntaje_Asig')->nullable();
            $table->integer('ID_test')->nullable(); // No tiene FK declarada en tu SQL original
        });

        // 5. Tabla PREGUNTAS
        Schema::create('preguntas', function (Blueprint $table) {
            $table->integer('ID_pregunta')->autoIncrement();
            $table->text('Pregunta')->nullable();
            $table->string('Clasificacion', 50)->nullable();
            $table->integer('ID_test')->nullable();

            $table->foreign('ID_test')->references('ID_test')->on('test')->onDelete('set null');
        });

        // 6. Tabla EVALUACION
        Schema::create('evaluacion', function (Blueprint $table) {
            $table->integer('ID_evaluacion')->autoIncrement();
            $table->integer('ID_usuario')->nullable();
            $table->integer('ID_test')->nullable();
            $table->date('Fecha')->nullable();
            $table->integer('Puntaje_Total')->nullable();
            $table->string('Nivel_Riesgo', 20)->nullable();
            $table->integer('ID_Diagnostico')->nullable();

            $table->foreign('ID_usuario')->references('ID_usuario')->on('usuario')->onDelete('cascade');
            $table->foreign('ID_test')->references('ID_test')->on('test')->onDelete('set null');
            $table->foreign('ID_Diagnostico')->references('ID_Diagnostico')->on('tipos_diagnostico')->onDelete('set null');
        });

        // 7. Tabla RESPUESTAS_EVALUACION
        Schema::create('respuestas_evaluacion', function (Blueprint $table) {
            $table->integer('ID_respuesta')->autoIncrement();
            $table->integer('ID_evaluacion')->nullable();
            $table->integer('ID_pregunta')->nullable();
            $table->text('Respuesta')->nullable();
            $table->integer('Puntaje')->nullable();

            $table->foreign('ID_evaluacion')->references('ID_evaluacion')->on('evaluacion')->onDelete('cascade');
            $table->foreign('ID_pregunta')->references('ID_pregunta')->on('preguntas')->onDelete('cascade');
        });

        // 8. Tabla Pivot USUARIO_TEST
        Schema::create('usuario_test', function (Blueprint $table) {
            $table->integer('ID_usuario');
            $table->integer('ID_test');
            
            $table->primary(['ID_usuario', 'ID_test']);
            $table->foreign('ID_usuario')->references('ID_usuario')->on('usuario')->onDelete('cascade');
            $table->foreign('ID_test')->references('ID_test')->on('test')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('usuario_test');
        Schema::dropIfExists('respuestas_evaluacion');
        Schema::dropIfExists('evaluacion');
        Schema::dropIfExists('preguntas');
        Schema::dropIfExists('tipos_resultados');
        Schema::dropIfExists('tipos_diagnostico');
        Schema::dropIfExists('test');
        Schema::dropIfExists('usuario');
    }
};