<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tbl_upload_content', function (Blueprint $table) {
            $columnsToDrop = [];

            if (Schema::hasColumn('tbl_upload_content', 'student_assignments')) {
                $columnsToDrop[] = 'student_assignments';
            }

            if (Schema::hasColumn('tbl_upload_content', 'assignment_file')) {
                $columnsToDrop[] = 'assignment_file';
            }

            if (!empty($columnsToDrop)) {
                $table->dropColumn($columnsToDrop);
            }
        });
    }

    public function down(): void
    {
        Schema::table('tbl_upload_content', function (Blueprint $table) {
            if (!Schema::hasColumn('tbl_upload_content', 'student_assignments')) {
                $table->longText('student_assignments')->nullable()->after('trainer_task');
            }

            if (!Schema::hasColumn('tbl_upload_content', 'assignment_file')) {
                $table->string('assignment_file', 255)->nullable()->after('student_assignments');
            }
        });
    }
};
