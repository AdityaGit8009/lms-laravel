<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('tbl_programme', function (Blueprint $table) {
            $table->id('programme_id');
            $table->unsignedBigInteger('session_id');
            $table->string('programme_name', 150);
            $table->string('duration', 100);
            $table->timestamps();

            $table->foreign('session_id')
                ->references('session_id')
                ->on('tbl_session')
                ->onDelete('cascade');

            $table->unique(['session_id', 'programme_name'], 'uq_programme_session_name');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tbl_programme');
    }
};
