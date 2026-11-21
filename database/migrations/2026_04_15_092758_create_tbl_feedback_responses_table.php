<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tbl_feedback_responses', function (Blueprint $table) {
            $table->id('response_id');
            $table->unsignedBigInteger('form_id')->index();
            $table->unsignedBigInteger('user_id')->nullable();
            foreach (['student_id', 'session_id', 'center_id', 'programme_id', 'technology_id', 'batch_id'] as $field) {
                $table->unsignedBigInteger($field)->nullable()->index();
            }
            $table->string('student_name', 255)->nullable();
            $table->string('email', 255)->nullable();
            $table->string('mobile')->nullable();
            $table->json('answers_json');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tbl_feedback_responses');
    }
};
