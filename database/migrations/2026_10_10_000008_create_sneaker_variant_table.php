<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sneakerVariant', function (Blueprint $table) {
            $table->increments('idSneakerVariant');
            $table->string('size', 10);
            $table->enum('gender', ['Man', 'Woman', 'Unisex'])->default('Unisex');
            $table->string('color', 50);
            $table->decimal('price', 10, 2)->index();
            $table->integer('stock')->nullable()->default(0);
            $table->unsignedInteger('idSneaker');
            $table->string('image', 100)->nullable();

            $table->foreign('idSneaker')->references('idSneaker')->on('sneaker')->cascadeOnDelete()->cascadeOnUpdate();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sneakerVariant');
    }
};
