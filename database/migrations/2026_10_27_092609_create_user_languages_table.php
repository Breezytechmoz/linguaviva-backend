<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('user_languages', function (Blueprint $table) {
            $table->id();
            // Liga ao utilizador (se o utilizador for apagado, apaga estes registos)
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            // Liga à língua (se a língua for apagada, apaga estes registos)
            $table->foreignId('language_id')->constrained()->onDelete('cascade');
            
            // Tipo: 'native' (materna) ou 'learning' (a aprender)
            $table->enum('type', ['native', 'learning']);
            
            // Nível (apenas faz sentido para as 'learning', mas guardamos para todas)
            $table->enum('level', ['beginner', 'intermediate', 'advanced', 'fluent', 'native'])->default('beginner');
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_languages');
    }
};