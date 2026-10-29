<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tbl_notification', function (Blueprint $table) {
            $table->id('notification_id');
            $table->unsignedBigInteger('session_id');
            $table->unsignedBigInteger('center_id');
            $table->unsignedBigInteger('programme_id');
            $table->unsignedBigInteger('technology_id');
            $table->unsignedBigInteger('batch_id');
            $table->unsignedBigInteger('notification_head_id');
            $table->string('title', 255);
            $table->string('notification_file', 255)->nullable();
            $table->longText('description')->nullable();
            $table->string('created_by', 255)->nullable();
            $table->string('updated_by', 255)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tbl_notification');
    }
};
