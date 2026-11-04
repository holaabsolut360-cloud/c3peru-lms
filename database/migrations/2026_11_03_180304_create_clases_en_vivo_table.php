<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('clases_en_vivo', function (Blueprint $table) {
            $table->id();
            $table->foreignId('curso_id')->constrained('cursos')->cascadeOnDelete();
            $table->foreignId('leccion_id')->nullable()->constrained('lecciones')->nullOnDelete();
            $table->foreignId('docente_id')->constrained('users')->cascadeOnDelete();
            $table->string('titulo', 180);
            $table->text('descripcion')->nullable();
            $table->dateTime('fecha_inicio');
            $table->dateTime('fecha_fin');
            $table->text('teams_join_url')->nullable();
            $table->string('estado', 20)->default('programada');
            $table->timestamps();

            $table->index(['curso_id', 'fecha_inicio']);
            $table->index(['docente_id', 'fecha_inicio']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('clases_en_vivo');
    }
};
