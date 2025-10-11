<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('user_habilidad', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('habilidad_id')->constrained('habilidades')->onDelete('cascade');
            $table->timestamps();
            $table->unique(['user_id', 'habilidad_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_habilidad');
    }
};
