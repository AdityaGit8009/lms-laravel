<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('student_login_logs', function (Blueprint $table) {
            $table->bigIncrements('log_id');
            $table->unsignedBigInteger('user_id')->nullable();
            $table->unsignedBigInteger('stu_id')->nullable();

            $table->string('student_email', 100)->nullable();
            $table->string('student_mobile', 15)->nullable();

            $table->dateTime('login_at')->nullable();
            $table->dateTime('logout_at')->nullable();
            $table->dateTime('last_seen_at')->nullable();

            $table->enum('status', ['online', 'offline'])->default('offline');
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();

            $table->timestamps();

            $table->index('user_id');
            $table->index('stu_id');
            $table->index('student_email');
            $table->index('login_at');
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('student_login_logs');
    }
};
