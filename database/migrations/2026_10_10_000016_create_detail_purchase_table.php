<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('detailPurchase', function (Blueprint $table) {
            $table->increments('idDetailPurchase');
            $table->integer('quantity');
            $table->decimal('unitCost', 10, 2);
            $table->unsignedInteger('idSneakerVariant');
            $table->unsignedInteger('idPurchase');

            $table->foreign('idSneakerVariant')->references('idSneakerVariant')->on('sneakerVariant')->restrictOnDelete()->cascadeOnUpdate();
            $table->foreign('idPurchase')->references('idPurchase')->on('purchase')->cascadeOnDelete()->cascadeOnUpdate();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('detailPurchase');
    }
};
