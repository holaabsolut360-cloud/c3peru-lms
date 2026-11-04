<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('matriculas', function (Blueprint $table): void {
            $table->string('empresa', 160)->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('matriculas', function (Blueprint $table): void {
            $table->dropColumn('empresa');
        });
    }
};
