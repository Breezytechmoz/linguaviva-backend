<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('translator_wallets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->unique()->constrained()->onDelete('cascade');
            $table->decimal('balance', 8, 2)->default(0); // Saldo acumulado (ex: 250.00 MT)
            $table->decimal('total_earned', 8, 2)->default(0); // Total ganho
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('translator_wallets'); }
};