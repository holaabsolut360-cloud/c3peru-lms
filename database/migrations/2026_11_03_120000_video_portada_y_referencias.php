<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('configuracion_apariencia', function (Blueprint $table) {
            $table->string('video_url')->nullable();
            $table->string('video_archivo')->nullable();
        });

        Schema::table('cursos', function (Blueprint $table) {
            $table->string('portada')->nullable();
        });

        Schema::create('referencias_publicas', function (Blueprint $table) {
            $table->id();
            $table->string('tipo', 20);
            $table->string('nombre');
            $table->string('detalle')->nullable();
            $table->text('texto')->nullable();
            $table->unsignedInteger('orden')->default(0);
            $table->boolean('por_confirmar')->default(true);
            $table->boolean('publicado')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('referencias_publicas');
        Schema::table('cursos', function (Blueprint $table) {
            $table->dropColumn('portada');
        });
        Schema::table('configuracion_apariencia', function (Blueprint $table) {
            $table->dropColumn(['video_url', 'video_archivo']);
        });
    }
};
