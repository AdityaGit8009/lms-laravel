<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('tbl_upload_content', 'title_key')) {
            Schema::table('tbl_upload_content', function (Blueprint $table) {
                $table->char('title_key', 32)->nullable()->after('title');
            });
        }

        DB::table('tbl_upload_content')
            ->select('content_id', 'title')
            ->orderBy('content_id')
            ->chunk(200, function ($rows) {
                foreach ($rows as $row) {
                    DB::table('tbl_upload_content')
                        ->where('content_id', $row->content_id)
                        ->update([
                            'title_key' => md5(mb_strtolower(trim((string) $row->title), 'UTF-8'))
                        ]);
                }
            });

        $duplicates = DB::table('tbl_upload_content')
            ->select('topic_id', 'title_key', DB::raw('COUNT(*) as total'))
            ->groupBy('topic_id', 'title_key')
            ->having('total', '>', 1)
            ->get();

        if ($duplicates->count() > 0) {
            throw new RuntimeException(
                'Duplicate records already exist in tbl_upload_content for same topic_id + title. ' .
                    'Please remove duplicates first, then run migration again.'
            );
        }

        Schema::table('tbl_upload_content', function (Blueprint $table) {
            $table->unique(['topic_id', 'title_key'], 'uq_upload_content_topic_title_key');
        });
    }

    public function down(): void
    {
        Schema::table('tbl_upload_content', function (Blueprint $table) {
            if ($this->hasIndex('tbl_upload_content', 'uq_upload_content_topic_title_key')) {
                $table->dropUnique('uq_upload_content_topic_title_key');
            }
        });

        if (Schema::hasColumn('tbl_upload_content', 'title_key')) {
            Schema::table('tbl_upload_content', function (Blueprint $table) {
                $table->dropColumn('title_key');
            });
        }
    }

    private function hasIndex(string $table, string $indexName): bool
    {
        $database = DB::getDatabaseName();

        $result = DB::select("
            SELECT COUNT(1) AS total
            FROM information_schema.statistics
            WHERE table_schema = ?
              AND table_name = ?
              AND index_name = ?
        ", [$database, $table, $indexName]);

        return !empty($result) && (int) $result[0]->total > 0;
    }
};
