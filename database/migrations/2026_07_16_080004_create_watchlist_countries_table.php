<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('watchlist_countries', function (Blueprint $table) {
            $table->id();
            
            $table->foreignId('watchlist_id')
                  ->constrained('watchlists')
                  ->cascadeOnUpdate()
                  ->cascadeOnDelete();

            $table->foreignId('country_id')
                  ->constrained('countries')
                  ->cascadeOnUpdate()
                  ->cascadeOnDelete();

            $table->timestamps();

            $table->index('watchlist_id');
            $table->index('country_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('watchlist_countries');
    }
};
