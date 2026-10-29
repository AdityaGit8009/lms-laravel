<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('tbl_register', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->string('college')->nullable();
            $table->string('regno')->nullable()->unique();
            $table->string('course')->nullable();
            $table->string('branch')->nullable();
            $table->string('year')->nullable();
            $table->string('mobile', 10)->nullable()->unique();
            $table->string('email')->nullable()->unique();
            $table->string('password')->nullable();
            $table->string('gender')->nullable();
            $table->string('program')->nullable();
            $table->string('location')->nullable();
            $table->string('photo')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tbl_register');
    }
};
