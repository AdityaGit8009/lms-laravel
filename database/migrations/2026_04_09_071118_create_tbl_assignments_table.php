<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tbl_assignments', function (Blueprint $table) {
            $table->id('assignment_id');
            $table->unsignedBigInteger('topic_id');
            $table->unsignedBigInteger('subject_id');
            $table->longText('Assignment_Ques');
            $table->string('Assignment_file', 255)->nullable();
            $table->unsignedInteger('max_marks')->nullable();
            $table->date('due_date')->nullable();
            $table->timestamps();

            $table->index('topic_id');
            $table->index('subject_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tbl_assignments');
    }
};
