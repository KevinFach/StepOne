<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('detailCart', function (Blueprint $table) {
            $table->increments('idDetailCart');
            $table->integer('quantity');
            $table->decimal('priceAtAddition', 10, 2);
            $table->unsignedInteger('idSneakerVariant');
            $table->unsignedInteger('idCart');

            $table->foreign('idSneakerVariant')->references('idSneakerVariant')->on('sneakerVariant')->restrictOnDelete()->cascadeOnUpdate();
            $table->foreign('idCart')->references('idCart')->on('cart')->cascadeOnDelete()->cascadeOnUpdate();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('detailCart');
    }
};
