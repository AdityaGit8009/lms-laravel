<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tbl_popup_notification', function (Blueprint $table) {
            $table->id('popup_notification_id');

            $table->json('session_ids')->nullable();
            $table->json('center_ids')->nullable();
            $table->json('programme_ids')->nullable();
            $table->json('technology_ids')->nullable();
            $table->json('batch_ids')->nullable();

            $table->string('title', 255);
            $table->string('image', 255)->nullable();
            $table->longText('description')->nullable();

            $table->string('created_by', 255)->nullable();
            $table->string('updated_by', 255)->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tbl_popup_notification');
    }
};
