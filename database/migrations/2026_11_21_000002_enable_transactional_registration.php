<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        if (!in_array(DB::getDriverName(), ['mysql', 'mariadb'], true)) {
            return;
        }

        $legacyTables = DB::table('information_schema.tables')
            ->where('table_schema', DB::getDatabaseName())
            ->whereIn('table_name', ['users', 'tbl_register'])
            ->where('engine', '<>', 'InnoDB')->pluck('table_name');

        foreach ($legacyTables as $table) {
            DB::statement("ALTER TABLE `{$table}` ENGINE=InnoDB");
        }
    }

    public function down(): void
    {
        // Keep account records on transactional storage, including preexisting InnoDB tables.
    }
};
