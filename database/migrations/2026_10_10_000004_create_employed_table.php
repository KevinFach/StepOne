<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('employed', function (Blueprint $table) {
            $table->increments('idEmployed');
            $table->string('first_name', 255);
            $table->string('last_name', 100);
            $table->string('email', 255)->unique();
            $table->string('phone', 20)->nullable();
            $table->enum('status', ['active', 'inactive'])->default('active')->index();
            $table->string('RFC', 13)->nullable()->index();
            $table->string('password', 255);
            $table->string('position', 100)->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('employed');
    }
};
