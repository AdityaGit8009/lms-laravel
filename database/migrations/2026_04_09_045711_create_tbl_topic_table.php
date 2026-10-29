<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tbl_topic', function (Blueprint $table) {
            $table->id('topic_id');
            $table->unsignedBigInteger('subject_id');
            $table->string('topic_name', 150);
            $table->tinyInteger('status')->default(1);
            $table->string('created_by', 100)->nullable();
            $table->string('updated_by', 100)->nullable();
            $table->timestamps();

            $table->unique(['subject_id', 'topic_name'], 'uq_subject_topic');
            $table->index('subject_id', 'idx_topic_subject_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tbl_topic');
    }
};
