<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payment', function (Blueprint $table) {
            $table->increments('idPayment');
            $table->unsignedInteger('idOrder')->nullable();
            $table->dateTime('payment_date')->nullable()->useCurrent();
            $table->decimal('amount', 10, 2)->nullable();
            $table->enum('method', ['Card', 'Transfer', 'Paypal'])->nullable()->default('Card');
            $table->enum('status', ['Pending', 'Completed', 'Failed'])->nullable()->default('Pending');

            $table->foreign('idOrder')->references('idOrder')->on('orders')->restrictOnDelete()->cascadeOnUpdate();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payment');
    }
};
