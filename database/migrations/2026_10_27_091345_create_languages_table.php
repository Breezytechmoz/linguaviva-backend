<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('languages', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Ex: Emakhuwa
            $table->string('alternative_name')->nullable(); // Ex: Makhuwa
            $table->string('region')->nullable(); // Ex: Norte de Moçambique
            $table->string('code', 10)->nullable(); // Ex: vmw (código ISO)
            $table->boolean('is_active')->default(true); // Para podermos ativar/desativar línguas
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('languages');
    }
};