<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tbl_attendance', function (Blueprint $table) {
            if (!Schema::hasColumn('tbl_attendance', 'attendance_date')) {
                $table->date('attendance_date')->nullable()->after('attend_student');
            }

            if (!Schema::hasColumn('tbl_attendance', 'attendance_status')) {
                $table->string('attendance_status', 20)->default('Absent')->after('attendance_date');
            }

            if (!Schema::hasColumn('tbl_attendance', 'ip_address')) {
                $table->string('ip_address', 45)->nullable()->after('attendance_status');
            }

            if (!Schema::hasColumn('tbl_attendance', 'marked_via')) {
                $table->string('marked_via', 30)->nullable()->after('ip_address');
            }

            if (!Schema::hasColumn('tbl_attendance', 'created_at') && !Schema::hasColumn('tbl_attendance', 'updated_at')) {
                $table->timestamps();
            }
        });
    }

    public function down(): void
    {
        Schema::table('tbl_attendance', function (Blueprint $table) {
            if (Schema::hasColumn('tbl_attendance', 'marked_via')) {
                $table->dropColumn('marked_via');
            }

            if (Schema::hasColumn('tbl_attendance', 'ip_address')) {
                $table->dropColumn('ip_address');
            }

            if (Schema::hasColumn('tbl_attendance', 'attendance_status')) {
                $table->dropColumn('attendance_status');
            }

            if (Schema::hasColumn('tbl_attendance', 'attendance_date')) {
                $table->dropColumn('attendance_date');
            }

            if (Schema::hasColumn('tbl_attendance', 'created_at') && Schema::hasColumn('tbl_attendance', 'updated_at')) {
                $table->dropTimestamps();
            }
        });
    }
};
