<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // MySQL may already have created this table before the old foreign key step failed.
        if (Schema::hasTable('tbl_branches')) {
            return;
        }

        Schema::create('tbl_branches', function (Blueprint $table) {
            $table->bigIncrements('branch_id');
            $table->unsignedBigInteger('course_id');
            $table->string('branch_name', 150);
            $table->string('created_by', 100)->nullable();
            $table->string('updated_by', 100)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tbl_branches');
    }
};
