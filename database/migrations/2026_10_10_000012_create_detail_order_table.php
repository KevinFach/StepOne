<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('detailOrder', function (Blueprint $table) {
            $table->increments('idDetailOrder');
            $table->integer('quantity');
            $table->decimal('unitPrice', 10, 2);
            $table->unsignedInteger('idSneakerVariant');
            $table->unsignedInteger('idOrder');

            $table->foreign('idSneakerVariant')->references('idSneakerVariant')->on('sneakerVariant')->restrictOnDelete()->cascadeOnUpdate();
            $table->foreign('idOrder')->references('idOrder')->on('orders')->cascadeOnDelete()->cascadeOnUpdate();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('detailOrder');
    }
};
