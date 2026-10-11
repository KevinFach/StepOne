<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('purchase', function (Blueprint $table) {
            $table->increments('idPurchase');
            $table->string('invoiceNumber', 100)->index();
            $table->decimal('totalAmount', 10, 2);
            $table->string('paymentMethod', 50);
            $table->timestamp('purchaseDate')->nullable()->useCurrent()->index();
            $table->unsignedInteger('idSupplier');
            $table->unsignedInteger('idEmployee');

            $table->foreign('idSupplier')->references('idSupplier')->on('supplier')->restrictOnDelete()->cascadeOnUpdate();
            $table->foreign('idEmployee')->references('idEmployed')->on('employed')->restrictOnDelete()->cascadeOnUpdate();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('purchase');
    }
};
