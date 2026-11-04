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
        Schema::create('rubrica_calificaciones', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('rubrica_id')->constrained('rubricas')->cascadeOnDelete();
            $table->foreignId('tarea_entrega_id')->constrained('tarea_entregas')->cascadeOnDelete();
            $table->foreignId('docente_id')->constrained('users')->cascadeOnDelete();
            $table->unsignedInteger('puntaje_obtenido');
            $table->text('comentario')->nullable();
            $table->string('comentario_audio_url')->nullable();
            $table->timestamps();

            $table->unique(['rubrica_id', 'tarea_entrega_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rubrica_calificaciones');
    }
};
