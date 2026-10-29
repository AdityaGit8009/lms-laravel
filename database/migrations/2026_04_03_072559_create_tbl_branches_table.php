<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tbl_branches', function (Blueprint $table) {
            $table->bigIncrements('branch_id');
            $table->unsignedBigInteger('course_id');
            $table->string('branch_name', 150);
            $table->string('created_by', 100)->nullable();
            $table->string('updated_by', 100)->nullable();
            $table->timestamps();

            $table->foreign('course_id')->references('course_id')->on('tbl_courses')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tbl_branches');
    }
};
