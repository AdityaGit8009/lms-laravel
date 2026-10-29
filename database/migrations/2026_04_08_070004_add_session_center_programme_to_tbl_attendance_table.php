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

        DB::statement("
        UPDATE tbl_attendance a
        JOIN tbl_batch b ON b.batch_id = a.attend_batch
        JOIN tbl_student s ON s.stu_id = a.attend_student
        SET
        a.attend_session = b.session_id,
        a.attend_center = s.center_id,
        a.attend_programme = b.programme_id
        WHERE
        a.attend_session = ''
        OR a.attend_center = ''
        OR a.attend_programme = ''
        ");
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
