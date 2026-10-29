<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tbl_employee', function (Blueprint $table) {
            $table->tinyInteger('status')->default(1)->after('emp_pic');
        });
    }

    public function down(): void
    {
        Schema::table('tbl_employee', function (Blueprint $table) {
            $table->dropColumn('status');
        });
    }
};
