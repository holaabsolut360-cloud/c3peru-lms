<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('cursos', function (Blueprint $table) {
            $table->text('beneficio')->nullable();
            $table->json('aprendizajes')->nullable();
            $table->json('temario')->nullable();
            $table->text('dirigido_a')->nullable();
            $table->text('requisitos')->nullable();
            $table->string('horario')->nullable();
            $table->string('fecha_inicio')->nullable();
            $table->string('instructor_nombre')->nullable();
            $table->string('instructor_cargo')->nullable();
            $table->string('instructor_foto')->nullable();
            $table->string('instructor_enlace')->nullable();
            $table->json('preguntas')->nullable();
            $table->json('testimonios')->nullable();
            $table->json('incluye')->nullable();
            $table->string('temario_archivo')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('cursos', function (Blueprint $table) {
            $table->dropColumn([
                'beneficio', 'aprendizajes', 'temario', 'dirigido_a', 'requisitos',
                'horario', 'fecha_inicio', 'instructor_nombre', 'instructor_cargo',
                'instructor_foto', 'instructor_enlace', 'preguntas', 'testimonios',
                'incluye', 'temario_archivo',
            ]);
        });
    }
};
