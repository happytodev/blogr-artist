<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('artworks', function (Blueprint $table) {
            $table->float('crop_x')->default(50)->after('category_id');
            $table->float('crop_y')->default(50)->after('crop_x');
            $table->boolean('show_in_portfolio')->default(true)->after('crop_y');
            $table->boolean('show_in_commissions')->default(false)->after('show_in_portfolio');
        });
    }

    public function down(): void
    {
        Schema::table('artworks', function (Blueprint $table) {
            $table->dropColumn(['crop_x', 'crop_y', 'show_in_portfolio', 'show_in_commissions']);
        });
    }
};
