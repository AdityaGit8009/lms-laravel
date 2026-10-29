<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tbl_attendance', function (Blueprint $table) {
            $table->engine = 'MyISAM';
            $table->charset = 'latin1';
            $table->collation = 'latin1_swedish_ci';

            $table->increments('attend_id');
            $table->string('attend_date', 30);
            $table->string('attend_technology', 30);
            $table->string('attend_batch', 30);
            $table->string('attend_student', 30);
            $table->string('attendance', 10);
            $table->string('attend_by', 30)->default('48');
            $table->string('added_date', 30);
            $table->string('added_time', 30);

            $table->index('attend_student', 'idx_attend_student');
            $table->index(['attend_student', 'attendance'], 'idx_attendance_flag');
            $table->index(['attend_technology', 'attend_batch', 'attend_student'], 'idx_tech_batch_student');
            $table->index(['attend_technology', 'attend_batch', 'attend_date'], 'idx_tech_batch_date');
            $table->index('attend_student', 'idx_student');
            $table->index('attend_date', 'idx_date');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tbl_attendance');
    }
};
