<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tbl_online_test_questions', function (Blueprint $table) {
            $table->id('otq_id');
            $table->unsignedBigInteger('online_test_id');
            $table->unsignedInteger('question_no');
            $table->unsignedInteger('marks')->default(0);
            $table->longText('question');
            $table->text('option_a');
            $table->text('option_b');
            $table->text('option_c');
            $table->text('option_d');
            $table->enum('correct_option', ['A', 'B', 'C', 'D']);
            $table->timestamps();

            $table->index('online_test_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tbl_online_test_questions');
    }
};
