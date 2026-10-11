<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('coupon', function (Blueprint $table) {
            $table->increments('idCoupon');
            $table->integer('maxUses');
            $table->decimal('discountValue', 10, 2);
            $table->string('code', 11)->unique();
            $table->enum('status', ['active', 'inactive'])->default('active')->index();
            $table->date('validFrom');
            $table->date('validUntil');

            $table->index(['validFrom', 'validUntil']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('coupon');
    }
};
