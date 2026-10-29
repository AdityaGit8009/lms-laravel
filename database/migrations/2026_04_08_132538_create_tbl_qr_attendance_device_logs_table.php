<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tbl_qr_attendance_device_logs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('stu_id')->nullable();
            $table->string('mobile', 20)->nullable();
            $table->string('ip_address', 45);
            $table->date('attendance_date');
            $table->timestamps();

            $table->unique(['ip_address', 'attendance_date'], 'uq_ip_date_once');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tbl_qr_attendance_device_logs');
    }
};
