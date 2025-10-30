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
        Schema::table('guests', function (Blueprint $table) {
            $table->foreignId('address_id')->nullable()->change();
        });

        Schema::table('addresses', function (Blueprint $table) {
            $table->string('postal_code', 20)->nullable()->change();
            $table->foreignId('state_id')->nullable()->change();
            $table->foreignId('city_id')->nullable()->change();
            $table->string('street')->nullable()->change();
            $table->string('neighborhood', 100)->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('guests', function (Blueprint $table) {
            $table->foreignId('address_id')->nullable(false)->change();
        });

        Schema::table('addresses', function (Blueprint $table) {
            $table->string('postal_code', 20)->nullable(false)->change();
            $table->foreignId('state_id')->nullable(false)->change();
            $table->foreignId('city_id')->nullable(false)->change();
            $table->string('street')->nullable(false)->change();
            $table->string('neighborhood', 100)->nullable(false)->change();
        });
    }

};
