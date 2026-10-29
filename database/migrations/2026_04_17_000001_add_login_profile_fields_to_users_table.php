<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'approval_status')) {
                $table->string('approval_status', 20)->default('Approved')->after('role');
            }
            if (!Schema::hasColumn('users', 'college')) {
                $table->string('college')->nullable()->after('image');
            }
            if (!Schema::hasColumn('users', 'regno')) {
                $table->string('regno', 50)->nullable()->unique()->after('college');
            }
            if (!Schema::hasColumn('users', 'course')) {
                $table->string('course', 100)->nullable()->after('regno');
            }
            if (!Schema::hasColumn('users', 'branch')) {
                $table->string('branch', 100)->nullable()->after('course');
            }
            if (!Schema::hasColumn('users', 'year')) {
                $table->string('year', 30)->nullable()->after('branch');
            }
            if (!Schema::hasColumn('users', 'gender')) {
                $table->string('gender', 20)->nullable()->after('year');
            }
            if (!Schema::hasColumn('users', 'program')) {
                $table->string('program', 100)->nullable()->after('gender');
            }
            if (!Schema::hasColumn('users', 'location')) {
                $table->string('location')->nullable()->after('program');
            }
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $columns = ['approval_status', 'college', 'regno', 'course', 'branch', 'year', 'gender', 'program', 'location'];
            foreach ($columns as $column) {
                if (Schema::hasColumn('users', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
