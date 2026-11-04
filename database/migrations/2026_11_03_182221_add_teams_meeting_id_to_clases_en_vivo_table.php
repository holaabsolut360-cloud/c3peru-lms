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
        Schema::table('clases_en_vivo', function (Blueprint $table) {
            $table->string('teams_meeting_id')->nullable()->after('fecha_fin');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('clases_en_vivo', function (Blueprint $table) {
            $table->dropColumn('teams_meeting_id');
        });
    }
};
