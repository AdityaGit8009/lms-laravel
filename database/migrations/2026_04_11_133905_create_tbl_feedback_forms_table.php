<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tbl_feedback_forms', function (Blueprint $table) {
            $table->id('form_id');
            $table->uuid('public_key')->unique();
            $table->string('title', 255);
            $table->text('description')->nullable();
            foreach (['session_ids', 'center_ids', 'programme_ids', 'technology_ids', 'batch_ids'] as $field) {
                $table->json($field)->nullable();
            }
            $table->enum('form_status', ['open', 'close'])->default('open');
            $table->json('questions_json');
            $table->unsignedBigInteger('created_by')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tbl_feedback_forms');
    }
};
