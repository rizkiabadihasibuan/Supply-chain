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
        Schema::create('country_flags', function (Blueprint $table) {
            $table->id();
            
            $table->foreignId('country_id')
                  ->unique()
                  ->constrained('countries')
                  ->cascadeOnUpdate()
                  ->cascadeOnDelete();

            $table->string('flag_url')->nullable();
            $table->string('svg_path')->nullable();
            
            $table->timestamps();

            // Explicit index
            $table->index('country_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('country_flags');
    }
};
