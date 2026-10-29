<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('tbl_subject_mock_tests', function (Blueprint $table) {
            $table->bigIncrements('subject_mock_test_id');
            $table->unsignedBigInteger('subject_id');
            $table->string('title', 255);
            $table->string('upload_file', 255)->nullable();
            $table->integer('max_marks')->nullable();
            $table->longText('mock_test');
            $table->tinyInteger('status')->default(1);
            $table->string('created_by', 100)->nullable();
            $table->string('updated_by', 100)->nullable();
            $table->timestamps();

            $table->index('subject_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tbl_subject_mock_tests');
    }
};
