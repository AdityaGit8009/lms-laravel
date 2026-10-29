<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tbl_technology', function (Blueprint $table) {
            $table->id('technology_id');
            $table->unsignedBigInteger('session_id');
            $table->unsignedBigInteger('programme_id');
            $table->string('technology_name', 150);
            $table->string('technology_image', 255)->nullable();
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

            $table->unique(['programme_id', 'technology_name'], 'uq_programme_technology_name');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tbl_technology');
    }
};
