<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tbl_student', function (Blueprint $table) {
            $table->bigIncrements('stu_id');

            $table->unsignedBigInteger('stu_role')->default(2);

            $table->string('stu_name', 200);
            $table->string('stu_regid', 50)->unique();
            $table->string('stu_college', 255);
            $table->unsignedBigInteger('stu_course');
            $table->string('stu_course_year', 30);
            $table->unsignedBigInteger('stu_branch');
            $table->string('stu_mobile', 15)->unique();
            $table->string('stu_email', 100)->unique();
            $table->string('stu_password', 255);
            $table->enum('stu_gender', ['Male', 'Female', 'Other']);
            $table->string('stu_pic', 255)->nullable();
            $table->unsignedBigInteger('stu_pro');

            $table->unsignedBigInteger('stu_session');

            $table->enum('del_status', ['Y', 'N'])->default('N');
            $table->enum('is_block', ['Y', 'N'])->default('N');

            $table->string('created_by', 100)->nullable();
            $table->string('updated_by', 100)->nullable();

            $table->timestamps();

            $table->index('stu_course');
            $table->index('stu_branch');
            $table->index('stu_pro');
            $table->index('stu_session');
            $table->index('del_status');
            $table->index('is_block');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tbl_student');
    }
};
