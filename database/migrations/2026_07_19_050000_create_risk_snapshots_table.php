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
        Schema::create('risk_snapshots', function (Blueprint $table) {
            $table->id();
            $table->foreignId('country_id')
                  ->constrained('countries')
                  ->cascadeOnUpdate()
                  ->cascadeOnDelete();
            $table->json('weather_data')->nullable();
            $table->json('economic_data')->nullable();
            $table->json('news_data')->nullable();
            $table->json('port_data')->nullable();
            $table->string('overall_status', 50)->default('Normal');
            $table->timestamp('snapshot_time')->useCurrent()->index();
            $table->timestamps();

            $table->index('country_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('risk_snapshots');
    }
};
