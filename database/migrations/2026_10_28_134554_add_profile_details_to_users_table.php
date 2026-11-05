<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->integer('age')->nullable();
            $table->text('bio')->nullable();
            $table->string('location')->nullable();
            $table->string('profile_photo')->nullable(); // Foto principal
            $table->json('extra_photos')->nullable(); // Array com as outras 3 fotos
            $table->integer('profile_completion')->default(20); // Começa com 20% (por ter nome e email)
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['age', 'bio', 'location', 'profile_photo', 'extra_photos', 'profile_completion']);
        });
    }
};