<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasColumn('tbl_upload_content', 'video_recording')) {
            Schema::table('tbl_upload_content', function (Blueprint $table) {
                $table->dropColumn('video_recording');
            });
        }
    }

    public function down(): void
    {
        if (!Schema::hasColumn('tbl_upload_content', 'video_recording')) {
            Schema::table('tbl_upload_content', function (Blueprint $table) {
                $table->longText('video_recording')->nullable()->after('video_link');
            });
        }
    }
};