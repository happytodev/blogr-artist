<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('artwork_translations', function (Blueprint $table) {
            $table->string('status')->default('open')->after('is_available');
        });
    }

    public function down(): void
    {
        Schema::table('artwork_translations', function (Blueprint $table) {
            $table->dropColumn('status');
        });
    }
};
