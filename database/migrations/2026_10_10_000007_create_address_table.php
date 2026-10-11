<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('address', function (Blueprint $table) {
            $table->increments('idAddres');
            $table->string('street', 150);
            $table->string('number', 20);
            $table->string('colony', 100);
            $table->string('state', 100)->index();
            $table->string('city', 100);
            $table->char('zip', 5)->index();
            $table->unsignedInteger('idCostumer');

            $table->foreign('idCostumer')->references('idCostumer')->on('costumer')->cascadeOnDelete()->cascadeOnUpdate();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('address');
    }
};
