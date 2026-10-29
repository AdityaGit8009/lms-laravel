<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tbl_employee_programme', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('emp_id');
            $table->unsignedBigInteger('programme_id');
            $table->enum('is_active', ['Y', 'N'])->default('Y');
            $table->string('created_by')->nullable();
            $table->string('updated_by')->nullable();
            $table->timestamps();
            $table->unique(['emp_id', 'programme_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tbl_employee_programme');
    }
};
