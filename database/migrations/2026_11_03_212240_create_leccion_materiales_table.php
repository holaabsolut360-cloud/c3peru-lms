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
        Schema::create('leccion_materiales', function (Blueprint $table) {
            $table->id();
            $table->foreignId('leccion_id')->constrained('lecciones')->cascadeOnDelete();
            $table->string('nombre_original', 255);
            $table->string('archivo_path');
            $table->string('mime_type', 100);
            $table->unsignedBigInteger('tamano_bytes');
            $table->timestamps();

            $table->index('leccion_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('leccion_materiales');
    }
};
