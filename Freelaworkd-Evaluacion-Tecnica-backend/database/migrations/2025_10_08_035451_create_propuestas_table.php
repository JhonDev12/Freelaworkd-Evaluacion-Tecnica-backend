<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('propuestas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('proyecto_id')->constrained()->onDelete('cascade');
            $table->foreignId('usuario_id')->constrained('users')->onDelete('cascade');
            $table->text('descripcion');
            $table->decimal('presupuesto', 10, 2);
            $table->integer('tiempo_estimado'); 
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('propuestas');
    }
};
