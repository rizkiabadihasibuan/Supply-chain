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
        Schema::create('risk_classifications', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique()->index(); // Very Low, Low, Medium, High, Critical
            $table->decimal('min_score', 5, 2)->default(0.00);
            $table->decimal('max_score', 5, 2)->default(100.00);
            $table->char('color_code', 7)->default('#FFFFFF'); // Hex code
            $table->string('description')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('risk_classifications');
    }
};
