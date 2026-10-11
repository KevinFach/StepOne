<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->increments('idOrder');
            $table->enum('status', ['enviado', 'cancelado', 'espera', 'entregado'])->default('enviado')->index();
            $table->timestamp('createdAt')->nullable()->useCurrent()->index();
            $table->decimal('shippingCost', 10, 2);
            $table->decimal('totalAmount', 10, 2);
            $table->string('shippingCompany', 100)->nullable();
            $table->string('trackingNumber', 100)->nullable();
            $table->unsignedInteger('idAddress');
            $table->unsignedInteger('idCostumer');
            $table->unsignedInteger('idCoupon')->nullable();

            $table->foreign('idAddress')->references('idAddres')->on('address')->restrictOnDelete()->cascadeOnUpdate();
            $table->foreign('idCostumer')->references('idCostumer')->on('costumer')->restrictOnDelete()->cascadeOnUpdate();
            $table->foreign('idCoupon')->references('idCoupon')->on('coupon')->nullOnDelete()->cascadeOnUpdate();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
