<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('tbl_programme', function (Blueprint $table) {
            $table->softDeletes()->after('updated_by'); // deleted_at
            $table->string('deleted_by', 100)->nullable()->after('deleted_at');
            $table->timestamp('restored_at')->nullable()->after('deleted_by');
            $table->string('restored_by', 100)->nullable()->after('restored_at');
        });
    }

    public function down(): void
    {
        Schema::table('tbl_programme', function (Blueprint $table) {
            $table->dropColumn(['deleted_at', 'deleted_by', 'restored_at', 'restored_by']);
        });
    }
};
