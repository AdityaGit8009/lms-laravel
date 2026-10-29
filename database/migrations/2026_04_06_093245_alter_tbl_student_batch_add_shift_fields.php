<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tbl_student_batch', function (Blueprint $table) {
            if (!Schema::hasColumn('tbl_student_batch', 'is_active')) {
                $table->enum('is_active', ['Y', 'N'])->default('Y')->after('batch_id');
            }

            if (!Schema::hasColumn('tbl_student_batch', 'assigned_at')) {
                $table->dateTime('assigned_at')->nullable()->after('is_active');
            }

            if (!Schema::hasColumn('tbl_student_batch', 'removed_at')) {
                $table->dateTime('removed_at')->nullable()->after('assigned_at');
            }

            if (!Schema::hasColumn('tbl_student_batch', 'shift_reason')) {
                $table->string('shift_reason', 255)->nullable()->after('removed_at');
            }
        });
    }

    public function down(): void
    {
        Schema::table('tbl_student_batch', function (Blueprint $table) {
            $dropColumns = [];

            if (Schema::hasColumn('tbl_student_batch', 'is_active')) {
                $dropColumns[] = 'is_active';
            }
            if (Schema::hasColumn('tbl_student_batch', 'assigned_at')) {
                $dropColumns[] = 'assigned_at';
            }
            if (Schema::hasColumn('tbl_student_batch', 'removed_at')) {
                $dropColumns[] = 'removed_at';
            }
            if (Schema::hasColumn('tbl_student_batch', 'shift_reason')) {
                $dropColumns[] = 'shift_reason';
            }

            if (!empty($dropColumns)) {
                $table->dropColumn($dropColumns);
            }
        });
    }
};
