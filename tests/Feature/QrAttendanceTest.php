<?php

namespace Tests\Feature;

use App\Models\User;
use Tests\TestCase;

class QrAttendanceTest extends TestCase
{
    public function test_the_student_form_posts_to_its_session_backed_endpoint(): void
    {
        $student = new User(['role' => 'student', 'approval_status' => 'Approved']);
        $this->actingAs($student)->get(route('qr.attendance.form'))->assertOk()
            ->assertSee(route('attendance.qr.attendance.submit'));
        $this->from(route('qr.attendance.form'))->post(route('attendance.qr.attendance.submit'), [])
            ->assertRedirect(route('qr.attendance.form'))->assertSessionHasErrors('mobile');
        $this->postJson(route('attendance.centers'))->assertForbidden();
    }
}
