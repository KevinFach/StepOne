<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('review', function (Blueprint $table) {
            $table->increments('idReview');
            $table->text('comment')->nullable();
            $table->tinyInteger('rating')->index();
            $table->timestamp('createdAt')->nullable()->useCurrent()->index();
            $table->unsignedInteger('idSneaker');
            $table->unsignedInteger('idCostumer');

            $table->foreign('idSneaker')->references('idSneaker')->on('sneaker')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreign('idCostumer')->references('idCostumer')->on('costumer')->cascadeOnDelete()->cascadeOnUpdate();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('review');
    }
};
