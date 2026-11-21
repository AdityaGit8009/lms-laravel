<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $key = ['attend_date', 'attend_batch', 'attend_student'];
        if (DB::table('tbl_attendance')->select($key)->groupBy(...$key)->havingRaw('COUNT(*) > 1')->exists()) {
            throw new RuntimeException('Resolve duplicate attendance entries for the same day, batch and student before migrating.');
        }

        if (in_array(DB::getDriverName(), ['mysql', 'mariadb'], true)) {
            foreach (['tbl_attendance', 'tbl_qr_attendance_device_logs'] as $table) {
                $engine = DB::table('information_schema.tables')
                    ->where('table_schema', DB::getDatabaseName())->where('table_name', $table)->value('engine');
                if (strtolower((string) $engine) !== 'innodb') {
                    DB::statement("ALTER TABLE `{$table}` ENGINE=InnoDB");
                }
            }
        }

        Schema::table('tbl_attendance', function (Blueprint $table) use ($key) {
            $table->unique($key, 'uq_attendance_day_batch_student');
        });
    }

    public function down(): void
    {
        Schema::table('tbl_attendance', function (Blueprint $table) {
            $table->dropUnique('uq_attendance_day_batch_student');
        });
        // Retain transactional storage when rolling back the register constraint.
    }
};
