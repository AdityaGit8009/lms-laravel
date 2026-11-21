<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class QrAttendanceTest extends TestCase
{
    use RefreshDatabase;

    public function test_the_student_form_posts_to_its_session_backed_endpoint(): void
    {
        $student = new User(['role' => 'student', 'approval_status' => 'Approved', 'email' => 'learner@example.test']);
        $this->actingAs($student)->get(route('qr.attendance.form'))->assertOk()
            ->assertSee(route('attendance.qr.attendance.submit'));
        $this->from(route('qr.attendance.form'))->post(route('attendance.qr.attendance.submit'), [])
            ->assertRedirect(route('qr.attendance.form'))->assertSessionHasErrors('mobile');
        $this->postJson(route('attendance.centers'))->assertForbidden();
    }

    public function test_qr_check_in_writes_the_register_used_by_staff_and_preserves_a_single_mark(): void
    {
        $email = 'learner@example.test';
        $mobile = '9000000001';
        $student = new User(['role' => 'student', 'approval_status' => 'Approved', 'email' => $email]);
        $student->id = 17;
        $studentId = DB::table('tbl_student')->insertGetId([
            'stu_name' => 'Test Learner', 'stu_email' => $email, 'stu_mobile' => '+91'.$mobile,
            'stu_regid' => 'TEST0001', 'stu_college' => 'Sample College', 'stu_course' => 1,
            'stu_course_year' => '1', 'stu_branch' => 1, 'stu_password' => 'unused',
            'stu_gender' => 'Other', 'stu_pro' => 1, 'stu_session' => 1,
        ]);
        $session = DB::table('tbl_session')->insertGetId(['session_name' => 'Test session']);
        $programme = DB::table('tbl_programme')->insertGetId([
            'programme_name' => 'Test programme', 'session_id' => $session, 'duration' => '1',
        ]);
        $technology = DB::table('tbl_technology')->insertGetId([
            'technology_name' => 'Test technology', 'session_id' => $session, 'programme_id' => $programme,
        ]);
        $batch = DB::table('tbl_batch')->insertGetId([
            'batch_name' => 'Test batch', 'session_id' => $session,
            'programme_id' => $programme, 'technology_id' => $technology,
        ]);
        DB::table('tbl_student_batch')->insert(['stu_id' => $studentId, 'batch_id' => $batch]);

        $this->actingAs($student)->from(route('qr.attendance.form'));
        $submit = route('attendance.qr.attendance.submit');
        $this->post($submit, ['mobile' => $mobile])->assertSessionHas('success');
        $this->assertDatabaseHas('tbl_attendance', [
            'attend_student' => $studentId, 'attend_batch' => $batch, 'attendance' => 'P',
        ]);
        $this->post($submit, ['mobile' => $mobile])->assertSessionHas('error');
        $this->assertDatabaseCount('tbl_attendance', 1);
        $this->assertDatabaseCount('tbl_qr_attendance_device_logs', 1);

        $otherLearner = (array) DB::table('tbl_student')->where('stu_id', $studentId)->first();
        unset($otherLearner['stu_id']);
        $secondId = DB::table('tbl_student')->insertGetId(array_replace($otherLearner, [
            'stu_regid' => 'TEST0002', 'stu_email' => 'another@example.test', 'stu_mobile' => '9000000002',
        ]));
        DB::table('tbl_student')->whereIn('stu_id', [$studentId, $secondId])->update(['center_id' => 1]);
        DB::table('tbl_student_batch')->insert(['stu_id' => $secondId, 'batch_id' => $batch]);
        $admin = new User(['role' => 'admin']);
        $admin->id = 29;
        $this->actingAs($admin)->postJson(route('attendance.students'), [
            'session_ids' => [$session], 'center_ids' => [1], 'programme_ids' => [$programme],
            'technology_ids' => [$technology], 'batch_ids' => [$batch],
        ])->assertOk()->assertJsonCount(1, 'data')->assertJsonPath('data.0.stu_id', $secondId);

        $row = ['batch_id' => $batch, 'technology_id' => $technology, 'center_id' => 1,
            'programme_id' => $programme, 'session_id' => $session, 'attendance' => 'A'];
        $this->postJson(route('attendance.store'), [
            'students' => [$row + ['stu_id' => $studentId], $row + ['stu_id' => $secondId]],
        ])->assertOk()->assertJsonPath('status', true);
        $this->assertDatabaseCount('tbl_attendance', 2);
        $this->assertDatabaseHas('tbl_attendance', ['attend_student' => $studentId, 'attendance' => 'P']);
    }
}
