<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('insignias', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('curso_id')->unique()->constrained('cursos')->cascadeOnDelete();
            $table->string('nombre', 180);
            $table->text('descripcion');
            $table->string('imagen_url');
            $table->text('criterio_logro')->nullable();
            $table->timestamps();
        });

        Schema::create('insignias_usuario', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('insignia_id')->constrained('insignias')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->string('badge_url');
            $table->string('assertion_url');
            $table->timestamp('otorgada_en');
            $table->timestamps();

            $table->unique(['insignia_id', 'user_id']);
            $table->index(['user_id', 'otorgada_en']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('insignias_usuario');
        Schema::dropIfExists('insignias');
    }
};
