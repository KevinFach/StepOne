<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sneaker', function (Blueprint $table) {
            $table->increments('idSneaker');
            $table->string('name', 150)->index();
            $table->unsignedInteger('idBrand')->nullable();
            $table->text('description')->nullable();
            $table->unsignedInteger('idCategory')->nullable();
            $table->integer('estado')->default(1);
            $table->string('image', 100);

            $table->foreign('idBrand')->references('idBrand')->on('Brand')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreign('idCategory')->references('idCategory')->on('Category')->cascadeOnDelete()->cascadeOnUpdate();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sneaker');
    }
};
