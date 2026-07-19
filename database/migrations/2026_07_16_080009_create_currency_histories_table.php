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
        Schema::create('currency_histories', function (Blueprint $table) {
            $table->id();
            
            $table->foreignId('currency_id')
                  ->constrained('currencies')
                  ->cascadeOnUpdate()
                  ->cascadeOnDelete();

            $table->decimal('rate_vs_usd', 15, 6)->default(1.000000);
            $table->date('recorded_date')->index();
            $table->timestamps();

            // Explicit index
            $table->index('currency_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('currency_histories');
    }
};
