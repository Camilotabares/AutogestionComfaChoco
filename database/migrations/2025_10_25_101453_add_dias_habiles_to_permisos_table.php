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
        Schema::table('permisos', function (Blueprint $table) {
            $table->unsignedTinyInteger('dias_habiles')->default(0)->after('fecha_final');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('permisos', function (Blueprint $table) {
            $table->dropColumn('dias_habiles');
        });
    }
};
