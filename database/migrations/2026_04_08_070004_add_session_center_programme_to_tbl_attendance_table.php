<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tbl_attendance', function (Blueprint $table) {
            $table->string('attend_session', 30)->default('')->after('attend_date');
            $table->string('attend_center', 30)->default('')->after('attend_session');
            $table->string('attend_programme', 30)->default('')->after('attend_center');

            $table->index('attend_session', 'idx_attend_session');
            $table->index('attend_center', 'idx_attend_center');
            $table->index('attend_programme', 'idx_attend_programme');
            $table->index(['attend_session', 'attend_center', 'attend_programme'], 'idx_session_center_programme');
        });

        $batch = DB::table('tbl_batch')->whereColumn('batch_id', 'tbl_attendance.attend_batch');
        $student = DB::table('tbl_student')->whereColumn('stu_id', 'tbl_attendance.attend_student');

        DB::table('tbl_attendance')
            ->whereExists($batch)
            ->whereExists($student)
            ->where(function ($query) {
                $query->where('attend_session', '')
                    ->orWhere('attend_center', '')
                    ->orWhere('attend_programme', '');
            })
            ->update([
                'attend_session' => (clone $batch)->select('session_id'),
                'attend_center' => (clone $student)->select('center_id'),
                'attend_programme' => (clone $batch)->select('programme_id'),
            ]);
    }

    public function down(): void
    {
        Schema::table('tbl_attendance', function (Blueprint $table) {
            $table->dropIndex('idx_attend_session');
            $table->dropIndex('idx_attend_center');
            $table->dropIndex('idx_attend_programme');
            $table->dropIndex('idx_session_center_programme');

            $table->dropColumn([
                'attend_session',
                'attend_center',
                'attend_programme'
            ]);
        });
    }
};
