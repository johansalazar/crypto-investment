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
        Schema::create('price_snapshots', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tracked_coin_id')->constrained('tracked_coins')->cascadeOnDelete();
            $table->dateTime('timestamp_utc')->index();
            $table->decimal('price_usd',18,8);
            $table->decimal('percent_change_24h',9,4)->nullable();
            $table->decimal('volume_24h',22,4)->nullable();
            $table->decimal('market_cap',22,4)->nullable();
            $table->json('raw')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('price_snapshots');
    }
};
