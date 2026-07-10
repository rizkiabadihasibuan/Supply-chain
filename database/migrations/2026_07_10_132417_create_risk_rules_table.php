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
        Schema::create('risk_rules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('risk_category_id')->constrained('risk_categories')->onDelete('cascade');
            $table->string('rule_name');
            $table->string('condition_operator', 10);
            $table->decimal('condition_value', 10, 2);
            $table->decimal('risk_impact_weight', 5, 2);
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('risk_rules');
    }
};
