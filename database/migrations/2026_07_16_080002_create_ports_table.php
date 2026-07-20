<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ports', function (Blueprint $table) {
            $table->id();
            $table->string('code', 10)->unique();
            $table->string('name');
            $table->foreignId('country_id')->constrained('countries')->onDelete('cascade');
            $table->double('latitude');
            $table->double('longitude');
            $table->string('size', 50)->nullable();
            $table->string('type', 50)->nullable();
            $table->string('harbor_type')->nullable();
            $table->json('facilities')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ports');
    }
};