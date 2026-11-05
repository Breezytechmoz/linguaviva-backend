<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('translations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('translator_id')->constrained('users')->onDelete('cascade'); // O tradutor
            $table->foreignId('language_id')->constrained('languages')->onDelete('cascade'); // Ex: Xichangana
            $table->string('source_word'); // Palavra em Português
            $table->string('translated_word'); // Tradução
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('translations'); }
};