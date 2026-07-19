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
        Schema::create('notification_preferences', function (Blueprint $table) {
            $table->id();
            
            $table->foreignId('user_id')
                  ->unique()
                  ->constrained('users')
                  ->cascadeOnUpdate()
                  ->cascadeOnDelete();

            $table->boolean('email')->default(true);
            $table->boolean('in_app')->default(true);
            $table->boolean('risk_alert')->default(true);
            $table->boolean('weather_alert')->default(true);
            $table->boolean('currency_alert')->default(true);
            $table->boolean('news_alert')->default(true);
            $table->timestamps();

            $table->index('user_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notification_preferences');
    }
};
