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
        Schema::create('actividades_alumno', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('curso_id')->constrained()->cascadeOnDelete();
            $table->foreignId('leccion_id')->nullable()->constrained('lecciones')->nullOnDelete();
            $table->string('tipo', 40);
            $table->unsignedInteger('duracion_segundos')->nullable();
            $table->unsignedTinyInteger('porcentaje_completado')->default(0);
            $table->timestamps();

            $table->index(['user_id', 'curso_id', 'tipo', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('actividades_alumno');
    }
};
