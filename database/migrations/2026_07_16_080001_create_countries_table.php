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
        Schema::create('countries', function (Blueprint $table) {
            $table->id();
            
            // Geopolitical regions relation
            $table->foreignId('region_id')
                  ->constrained('regions')
                  ->cascadeOnUpdate()
                  ->restrictOnDelete();

            // Currency relation
            $table->foreignId('currency_id')
                  ->constrained('currencies')
                  ->cascadeOnUpdate()
                  ->restrictOnDelete();

            $table->char('code', 2)->unique()->index();
            $table->string('name')->unique()->index();
            $table->string('subregion')->nullable();
            $table->bigInteger('population')->nullable();
            $table->double('area')->nullable();
            $table->string('timezone')->nullable();
            $table->timestamps();

            // Explicit index
            $table->index('region_id');
            $table->index('currency_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('countries');
    }
};
