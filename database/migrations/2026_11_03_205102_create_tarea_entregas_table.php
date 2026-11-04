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
        Schema::create('tarea_entregas', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('tarea_id')->constrained('tareas')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->string('archivo_path')->nullable();
            $table->string('archivo_nombre')->nullable();
            $table->string('archivo_mime_type', 120)->nullable();
            $table->text('comentario')->nullable();
            $table->timestamp('entregado_en');
            $table->unsignedInteger('puntaje_total')->nullable();
            $table->text('comentario_docente')->nullable();
            $table->timestamp('calificada_en')->nullable();
            $table->timestamps();

            $table->index(['tarea_id', 'user_id', 'entregado_en']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tarea_entregas');
    }
};
