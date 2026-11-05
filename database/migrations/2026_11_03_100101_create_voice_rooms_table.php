<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('voice_rooms', function (Blueprint $table) {
            $table->id();
            $table->string('title'); // Ex: Praticar Xichangana
            $table->foreignId('host_id')->constrained('users')->onDelete('cascade'); // Quem criou
            $table->foreignId('language_id')->constrained('languages')->onDelete('cascade'); // Língua da sala
            $table->boolean('is_active')->default(true); // Para fechar a sala quando o host sair
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('voice_rooms');
    }
};