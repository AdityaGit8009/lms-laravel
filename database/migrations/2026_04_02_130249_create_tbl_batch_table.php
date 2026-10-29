<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tbl_batch', function (Blueprint $table) {
            $table->id('batch_id');
            $table->unsignedBigInteger('session_id');
            $table->unsignedBigInteger('programme_id');
            $table->unsignedBigInteger('technology_id');
            $table->string('batch_name', 150);
            $table->string('created_by', 100)->nullable();
            $table->string('updated_by', 100)->nullable();
            $table->timestamps();

            $table->foreign('session_id')
                ->references('session_id')
                ->on('tbl_session')
                ->onDelete('cascade');

            $table->foreign('programme_id')
                ->references('programme_id')
                ->on('tbl_programme')
                ->onDelete('cascade');

            $table->foreign('technology_id')
                ->references('technology_id')
                ->on('tbl_technology')
                ->onDelete('cascade');

            $table->unique(['technology_id', 'batch_name'], 'uq_technology_batch_name');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tbl_batch');
    }
};
