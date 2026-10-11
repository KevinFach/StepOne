<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cart', function (Blueprint $table) {
            $table->increments('idCart');
            $table->enum('status', ['active', 'completed', 'expired'])->default('active')->index();
            $table->timestamp('createdAt')->nullable()->useCurrent();
            $table->dateTime('expiresAt')->index();
            $table->unsignedInteger('idCostumer');

            $table->foreign('idCostumer')->references('idCostumer')->on('costumer')->cascadeOnDelete()->cascadeOnUpdate();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cart');
    }
};
