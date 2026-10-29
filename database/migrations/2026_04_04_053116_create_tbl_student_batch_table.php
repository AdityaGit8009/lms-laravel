<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tbl_student_batch', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('stu_id');
            $table->unsignedBigInteger('batch_id');
            $table->string('created_by', 100)->nullable();
            $table->string('updated_by', 100)->nullable();
            $table->timestamps();

            $table->unique(['stu_id', 'batch_id'], 'uniq_student_batch');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tbl_student_batch');
    }
};
