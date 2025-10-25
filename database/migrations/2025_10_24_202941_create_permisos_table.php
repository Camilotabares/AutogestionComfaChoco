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
        Schema::create('permisos', function (Blueprint $table) {
            $table->id();
            $table->enum('tipo_permiso',['ausentismo','licencia']);
            $table->enum('tipo_de_Ausentismo',['citas_medicas','permiso_personal','liciencia_luto','maternidad','paternidad'])->nullable();
            $table->date('fecha_inicio');
            $table->date('fecha_final');
            $table->string('soporte')->nullable();
            $table->enum('estado', ['aprobado', 'no_aprobado'])->default('no_aprobado');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('permisos');
    }
};
