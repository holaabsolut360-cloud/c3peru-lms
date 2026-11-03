<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('instructores', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->string('cargo');
            $table->text('bio');
            $table->string('iniciales', 4);
            $table->string('linkedin')->nullable();
            $table->string('foto')->nullable();
            $table->unsignedInteger('orden')->default(0);
            $table->boolean('publicado')->default(true);
            $table->timestamps();
        });

        Schema::create('cifras_comunidad', function (Blueprint $table) {
            $table->id();
            $table->string('etiqueta');
            $table->string('valor');
            $table->unsignedInteger('orden')->default(0);
            $table->boolean('publicado')->default(true);
            $table->timestamps();
        });

        Schema::create('compromisos', function (Blueprint $table) {
            $table->id();
            $table->string('titulo');
            $table->text('texto');
            $table->boolean('propuesto')->default(true);
            $table->unsignedInteger('orden')->default(0);
            $table->boolean('publicado')->default(true);
            $table->timestamps();
        });

        Schema::table('referencias_publicas', function (Blueprint $table) {
            $table->string('logo')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('referencias_publicas', function (Blueprint $table) {
            $table->dropColumn('logo');
        });
        Schema::dropIfExists('compromisos');
        Schema::dropIfExists('cifras_comunidad');
        Schema::dropIfExists('instructores');
    }
};
