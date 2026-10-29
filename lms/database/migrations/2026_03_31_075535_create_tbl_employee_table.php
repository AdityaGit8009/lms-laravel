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
        Schema::create('tbl_employee', function (Blueprint $table) {
            $table->id('emp_id');

            $table->string('emp_name', 150);
            $table->string('emp_deg', 150)->nullable();
            $table->string('emp_mobile', 20)->nullable();
            $table->string('emp_email', 150)->unique();
            $table->string('emp_password');

            $table->string('emp_code', 50)->nullable()->unique();
            $table->string('emp_gender', 20)->nullable();
            $table->string('emp_type', 50)->nullable();
            $table->text('emp_address')->nullable();
            $table->string('emp_pic', 255)->nullable();

            $table->string('emp_role', 50)->nullable();
            $table->unsignedBigInteger('role_id')->nullable();

            $table->string('cr_by', 100)->nullable();
            $table->date('cr_date')->nullable();
            $table->time('cr_time')->nullable();

            $table->string('emp_del_status', 10)->default('N');
            $table->string('status', 20)->default('Active');
            $table->string('status3', 50)->nullable();
            $table->string('status4', 50)->nullable();

            $table->string('emp_alt_contact', 20)->nullable();
            $table->date('emp_dob')->nullable();
            $table->string('emp_indentity', 255)->nullable();
            $table->string('emp_Company_Name', 255)->nullable();
            $table->string('emp_exp', 100)->nullable();
            $table->string('emp_Specialization', 255)->nullable();
            $table->text('emp_skills')->nullable();
            $table->string('emp_industry', 255)->nullable();
            $table->string('emp_work_mode', 100)->nullable();
            $table->string('emp_highest_qualification', 255)->nullable();
            $table->string('emp_degree_diploma', 255)->nullable();
            $table->string('emp_university', 255)->nullable();
            $table->string('emp_passing_year', 20)->nullable();
            $table->text('emp_certifications')->nullable();
            $table->text('emp_bio')->nullable();
            $table->text('emp_achievements')->nullable();
            $table->text('emp_notable_projects')->nullable();
            $table->text('emp_past_organizations')->nullable();
            $table->text('emp_client_testimonials')->nullable();

            $table->string('emp_resume_path', 255)->nullable();
            $table->string('emp_linkedin', 255)->nullable();
            $table->string('emp_personal_website', 255)->nullable();
            $table->string('emp_twitter_handle', 255)->nullable();
            $table->string('emp_youtube_channel', 255)->nullable();

            $table->timestamps();

            $table->foreign('role_id')
                ->references('role_id')
                ->on('roles')
                ->nullOnDelete()
                ->cascadeOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tbl_employee');
    }
};
