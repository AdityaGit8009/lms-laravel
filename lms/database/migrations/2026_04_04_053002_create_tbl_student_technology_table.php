<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tbl_student_technology', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('stu_id');
            $table->unsignedBigInteger('technology_id');
            $table->string('created_by', 100)->nullable();
            $table->string('updated_by', 100)->nullable();
            $table->timestamps();

            $table->unique(['stu_id', 'technology_id'], 'uniq_student_technology');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tbl_student_technology');
    }
};
