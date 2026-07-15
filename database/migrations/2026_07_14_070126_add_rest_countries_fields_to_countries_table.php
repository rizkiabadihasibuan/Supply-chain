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
        Schema::table('countries', function (Blueprint $table) {
            $table->string('iso2', 2)->nullable()->after('code');
            $table->string('iso3', 3)->nullable()->after('iso2');
            $table->text('flag_url')->nullable()->after('language');
            $table->string('currency_symbol', 10)->nullable()->after('currency_name');
            $table->string('subregion', 100)->nullable()->after('region');
            $table->string('capital', 100)->nullable()->after('subregion');
            $table->text('timezone')->nullable()->after('population');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('countries', function (Blueprint $table) {
            $table->dropColumn([
                'iso2',
                'iso3',
                'flag_url',
                'currency_symbol',
                'subregion',
                'capital',
                'timezone',
            ]);
        });
    }
};
