
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tbl_courses', function (Blueprint $table) {
            $table->bigIncrements('course_id');
            $table->string('course_name', 150);
            $table->integer('duration')->comment('Duration in years');
            $table->string('created_by', 100)->nullable();
            $table->string('updated_by', 100)->nullable();
            $table->timestamps();
        });

        Schema::table('tbl_branches', function (Blueprint $table) {
            $table->foreign('course_id')->references('course_id')->on('tbl_courses')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        $foreignKeys = collect(Schema::getForeignKeys('tbl_branches'));
        if ($foreignKeys->contains(fn ($key) => $key['columns'] === ['course_id'])) {
            Schema::table('tbl_branches', function (Blueprint $table) {
                $table->dropForeign(['course_id']);
            });
        }

        Schema::dropIfExists('tbl_courses');
    }
};
