<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tbl_upload_content', function (Blueprint $table) {
            $table->id('content_id');
            $table->unsignedBigInteger('subject_id');
            $table->unsignedBigInteger('topic_id');
            $table->string('title', 255);

            $table->longText('learning_objective')->nullable();
            $table->longText('content_body')->nullable();

            $table->string('resource_file', 255)->nullable();
            $table->string('video_link', 255)->nullable();

            $table->longText('video_recording')->nullable();
            $table->longText('trainer_task')->nullable();
            $table->longText('student_assignments')->nullable();

            $table->string('assignment_file', 255)->nullable();
            $table->longText('learning_outcomes')->nullable();

            $table->string('created_by', 100)->nullable();
            $table->string('updated_by', 100)->nullable();
            $table->timestamps();

            $table->index('subject_id', 'idx_upload_content_subject_id');
            $table->index('topic_id', 'idx_upload_content_topic_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tbl_upload_content');
    }
};
