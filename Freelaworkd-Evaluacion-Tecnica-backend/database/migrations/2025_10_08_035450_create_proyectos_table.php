<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Ejecuta las migraciones.
     */
    public function up(): void
    {
        Schema::create('proyectos', function (Blueprint $table) {
            $table->id();
            $table->string('titulo', 255);
            $table->text('descripcion');
            $table->decimal('presupuesto', 10, 2)->nullable();
            $table->enum('estado', ['abierto', 'en progreso', 'finalizado'])->default('abierto');

            // Relación con usuarios (propietario del proyecto)
            $table->foreignId('usuario_id')->constrained('users')->onDelete('cascade');

            $table->timestamps();
        });
    }

    /**
     * Revierte las migraciones.
     */
    public function down(): void
    {
        Schema::dropIfExists('proyectos');
    }
};
