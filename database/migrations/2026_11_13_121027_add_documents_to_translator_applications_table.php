<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('translator_applications', function (Blueprint $table) {
            $table->string('cv_path')->nullable()->after('video_path');
            $table->string('cover_letter_path')->nullable()->after('cv_path');
            $table->boolean('wants_interview')->default(false)->after('cover_letter_path');
        });
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('translator_applications', function (Blueprint $table) {
            //
        });
    }
};
