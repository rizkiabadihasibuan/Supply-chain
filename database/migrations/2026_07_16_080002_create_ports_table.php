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
        Schema::create('ports', function (Blueprint $table) {
            $table->id();
            
            $table->foreignId('country_id')
                  ->constrained('countries')
                  ->cascadeOnUpdate()
                  ->cascadeOnDelete();

            $table->foreignId('category_id')
                  ->constrained('port_categories')
                  ->cascadeOnUpdate()
                  ->restrictOnDelete();

            $table->string('code', 10)->unique()->index();
            $table->string('name')->index();
            $table->decimal('latitude', 10, 8);
            $table->decimal('longitude', 11, 8);
            $table->string('size')->nullable();
            $table->string('type')->nullable();
            $table->string('harbor_type')->nullable();
            $table->timestamps();

            // Explicit index
            $table->index('country_id');
            $table->index('category_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ports');
    }
};
