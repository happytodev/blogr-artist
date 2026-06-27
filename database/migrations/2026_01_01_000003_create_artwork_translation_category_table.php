<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('artwork_translation_category', function (Blueprint $table) {
            $table->id();
            $table->foreignId('artwork_translation_id')->constrained()->cascadeOnDelete();
            $table->foreignId('category_id')->constrained()->cascadeOnDelete();
            $table->timestamps();

            $table->unique(['artwork_translation_id', 'category_id'], 'atc_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('artwork_translation_category');
    }
};
