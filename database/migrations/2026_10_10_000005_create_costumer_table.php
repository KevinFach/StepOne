<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('costumer', function (Blueprint $table) {
            $table->increments('idCostumer');
            $table->string('first_name', 100);
            $table->string('last_name', 100);
            $table->string('phone', 20)->unique();
            $table->string('password', 255);
            $table->string('tempPassword', 255)->nullable();
            $table->string('email', 255)->unique();
            $table->char('rfc', 13)->unique();
            $table->char('CURP', 18)->unique();
            $table->dateTime('last_login')->nullable();
            $table->boolean('is_active')->default(true);
            $table->boolean('is_admin')->default(false);
            $table->rememberToken();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('costumer');
    }
};
