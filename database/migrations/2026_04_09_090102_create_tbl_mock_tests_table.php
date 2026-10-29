<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tbl_mock_tests', function (Blueprint $table) {
            $table->id('mock_id');
            $table->unsignedBigInteger('subject_id');
            $table->unsignedBigInteger('topic_id');
            $table->longText('mock_test');
            $table->unsignedInteger('marks')->nullable();
            $table->timestamps();

            $table->index('subject_id');
            $table->index('topic_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tbl_mock_tests');
    }
};
