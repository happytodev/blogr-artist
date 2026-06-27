<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('artwork_translation_tag', function (Blueprint $table) {
            $table->id();
            $table->foreignId('artwork_translation_id')->constrained()->cascadeOnDelete();
            $table->foreignId('tag_id')->constrained()->cascadeOnDelete();
            $table->timestamps();

            $table->unique(['artwork_translation_id', 'tag_id'], 'att_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('artwork_translation_tag');
    }
};
