<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tbl_student', function (Blueprint $table) {
            $table->unsignedBigInteger('center_id')->nullable()->after('stu_college');
        });
    }

    public function down(): void
    {
        Schema::table('tbl_student', function (Blueprint $table) {
            $table->dropColumn('center_id');
        });
    }
};
