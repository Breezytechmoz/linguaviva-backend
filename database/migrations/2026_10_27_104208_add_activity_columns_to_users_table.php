<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Guarda a data/hora do último acesso
            $table->timestamp('last_seen_at')->nullable();
            // Guarda se o utilizador está a escrever neste momento
            $table->boolean('is_typing')->default(false);
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['last_seen_at', 'is_typing']);
        });
    }
};