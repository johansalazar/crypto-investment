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
        Schema::create('cryptocurrencies', function (Blueprint $table) {
            $table->id();
            $table->string('symbol');
            $table->string('name');
            $table->decimal('price', 18, 8);
            $table->decimal('percent_change_24h', 10, 2);
            $table->decimal('market_cap', 20, 2)->nullable();
            $table->decimal('volume_24h', 20, 2)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cryptocurrencies');
    }
};
