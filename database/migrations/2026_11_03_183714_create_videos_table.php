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
        Schema::create('videos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('leccion_id')->constrained('lecciones')->cascadeOnDelete();
            $table->string('titulo', 180);
            $table->string('archivo_path');
            $table->string('mime_type', 100);
            $table->unsignedInteger('duracion_segundos')->nullable();
            $table->boolean('protegido')->default(true);
            $table->timestamps();

            $table->index(['leccion_id', 'protegido']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('videos');
    }
};
