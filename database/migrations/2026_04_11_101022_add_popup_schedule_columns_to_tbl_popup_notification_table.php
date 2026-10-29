<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tbl_popup_notification', function (Blueprint $table) {
            $table->dateTime('popup_from')->nullable()->after('title');
            $table->dateTime('popup_to')->nullable()->after('popup_from');
        });
    }

    public function down(): void
    {
        Schema::table('tbl_popup_notification', function (Blueprint $table) {
            $table->dropColumn(['popup_from', 'popup_to']);
        });
    }
};
