<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('mensajes_contacto', function (Blueprint $table) {
            $table->string('empresa', 160)->nullable()->after('nombre');
        });
    }

    public function down(): void
    {
        Schema::table('mensajes_contacto', function (Blueprint $table) {
            $table->dropColumn('empresa');
        });
    }
};
