<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('examenes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('curso_id')->constrained('cursos')->cascadeOnDelete();
            $table->string('titulo', 180);
            $table->text('instrucciones')->nullable();
            $table->unsignedSmallInteger('duracion_minutos')->default(30);
            $table->decimal('puntaje_aprobatorio', 5, 2)->default(14);
            $table->unsignedTinyInteger('intentos_permitidos')->default(1);
            $table->boolean('disponible')->default(false);
            $table->timestamp('fecha_inicio')->nullable();
            $table->timestamp('fecha_fin')->nullable();
            $table->timestamps();

            $table->index(['curso_id', 'disponible']);
        });

        Schema::create('preguntas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('examen_id')->constrained('examenes')->cascadeOnDelete();
            $table->foreignId('leccion_id')->nullable()->constrained('lecciones')->nullOnDelete();
            $table->text('enunciado');
            $table->json('opciones');
            $table->string('respuesta_correcta', 8);
            $table->unsignedSmallInteger('puntaje')->default(1);
            $table->text('explicacion')->nullable();
            $table->string('dificultad', 16)->default('medio');
            $table->unsignedInteger('orden')->default(0);
            $table->timestamps();

            $table->index(['examen_id', 'orden']);
        });

        Schema::create('examen_intentos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('examen_id')->constrained('examenes')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->timestamp('iniciado_en');
            $table->timestamp('expira_en');
            $table->timestamp('finalizado_en')->nullable();
            $table->unsignedInteger('puntaje_total')->default(0);
            $table->decimal('nota_final', 5, 2)->nullable();
            $table->unsignedTinyInteger('porcentaje')->nullable();
            $table->boolean('aprobado')->nullable();
            $table->timestamps();

            $table->index(['examen_id', 'user_id', 'finalizado_en']);
        });

        Schema::create('examen_respuestas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('examen_intento_id')->constrained('examen_intentos')->cascadeOnDelete();
            $table->foreignId('pregunta_id')->constrained('preguntas')->cascadeOnDelete();
            $table->string('respuesta_seleccionada', 8)->nullable();
            $table->boolean('es_correcta')->nullable();
            $table->unsignedSmallInteger('puntaje_obtenido')->default(0);
            $table->timestamps();

            $table->unique(['examen_intento_id', 'pregunta_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('examen_respuestas');
        Schema::dropIfExists('examen_intentos');
        Schema::dropIfExists('preguntas');
        Schema::dropIfExists('examenes');
    }
};
