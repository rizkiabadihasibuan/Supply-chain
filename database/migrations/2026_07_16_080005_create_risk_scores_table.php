<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('risk_scores', function (Blueprint $table) {
            $table->id();
            $table->foreignId('country_id')->unique()->constrained('countries')->onDelete('cascade');
            $table->foreignId('classification_id')->nullable()->constrained('risk_classifications')->onDelete('set null');
            $table->decimal('final_risk_score', 5, 2)->default(0.00);
            $table->string('risk_level', 20)->default('Low');
            
            // Merged columns
            $table->json('components')->nullable(); // cuaca, inflasi, forex, dll.
            $table->json('history')->nullable();    // riwayat skor 30 hari terakhir
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('risk_scores');
    }
};