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
        Schema::create('api_logs', function (Blueprint $table) {
            $table->id();
            $table->string('api_name')->index();
            $table->string('endpoint');
            $table->json('request_payload')->nullable();
            $table->json('response_payload')->nullable();
            $table->integer('status_code')->nullable();
            $table->boolean('is_success')->default(true)->index();
            $table->text('error_message')->nullable();
            $table->timestamps();
            
            // Add index for created_at
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('api_logs');
    }
};
