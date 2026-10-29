<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tbl_recordings', function (Blueprint $table) {
            $table->id('rec_id');
            $table->unsignedBigInteger('subject_id');
            $table->unsignedBigInteger('topic_id');
            $table->longText('video_Recording');
            $table->unsignedInteger('max_marks')->nullable();
            $table->date('due_date')->nullable();
            $table->timestamps();

            $table->index('subject_id');
            $table->index('topic_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tbl_recordings');
    }
};
