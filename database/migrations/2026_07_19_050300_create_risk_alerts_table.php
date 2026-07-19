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
        Schema::create('risk_alerts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('country_id')
                  ->constrained('countries')
                  ->cascadeOnUpdate()
                  ->cascadeOnDelete();
            $table->foreignId('risk_score_id')
                  ->constrained('risk_scores')
                  ->cascadeOnUpdate()
                  ->cascadeOnDelete();
            $table->string('alert_type', 100);
            $table->string('severity', 50)->default('Medium')->index(); // Low, Medium, High, Critical
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('status', 50)->default('Active')->index(); // Active, Resolved
            $table->timestamp('resolved_at')->nullable();
            $table->timestamps();

            $table->index('country_id');
            $table->index('risk_score_id');
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('risk_alerts');
    }
};
