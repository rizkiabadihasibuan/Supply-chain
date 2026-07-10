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
        Schema::create('alert_resolutions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('risk_alert_id')->unique()->constrained('risk_alerts')->onDelete('cascade');
            $table->foreignId('resolved_by_user_id')->constrained('users')->onDelete('restrict');
            $table->text('resolution_action');
            $table->text('notes')->nullable();
            $table->timestamp('resolved_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('alert_resolutions');
    }
};
