<?php

namespace Tests\Feature;

use App\Models\FeedbackForm;
use App\Models\User;
use App\Models\Student;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Tests\TestCase;

class FeedbackWorkflowTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_manage_a_form_on_a_migrated_database(): void
    {
        $form = FeedbackForm::create([
            'public_key' => (string) Str::uuid(), 'title' => 'Course review',
            'form_status' => 'open', 'questions_json' => [],
        ]);

        $this->actingAs(new User(['role' => 'admin', 'name' => 'Test Admin']))
            ->get(route('admin.feedback.manage'))->assertOk()->assertSee($form->title);
        $this->assertSame(0, $form->responses()->count());
    }

    public function test_a_student_response_is_stored_once_and_is_readable_by_staff(): void
    {
        $user = User::create([
            'name' => 'Test Learner', 'email' => 'feedback@example.test', 'password' => 'unused',
            'role' => 'student', 'approval_status' => 'Approved',
        ]);
        $student = Student::create([
            'stu_name' => $user->name, 'stu_email' => $user->email, 'stu_mobile' => '9000000002',
            'stu_regid' => 'FEED0001', 'stu_college' => 'Test College', 'stu_course' => 1,
            'stu_course_year' => '1', 'stu_branch' => 1, 'stu_password' => 'unused',
            'stu_gender' => 'Other', 'stu_pro' => 1, 'stu_session' => 1,
        ]);
        $form = FeedbackForm::create([
            'public_key' => (string) Str::uuid(), 'title' => 'Course review', 'form_status' => 'open',
            'questions_json' => [['text' => 'What helped?', 'type' => 'short_answer', 'required' => true]],
        ]);
        $this->actingAs($user)->get(route('student.feedback.show', $form))->assertOk();
        $this->post(route('student.feedback.store', $form), ['question_0' => 'Practice'])
            ->assertRedirect(route('student.feedback.index'))->assertSessionHas('success');
        $this->post(route('student.feedback.store', $form), ['question_0' => 'Practice'])
            ->assertSessionHas('info');
        $this->assertDatabaseCount('tbl_feedback_responses', 1);
        $this->assertDatabaseHas('tbl_feedback_responses', ['student_name' => $user->name, 'student_id' => $student->getKey()]);

        $this->actingAs(new User(['role' => 'admin']))
            ->postJson(route('admin.feedback.analytics.student.response'), [
                'form_id' => $form->getKey(), 'student_id' => $student->getKey(),
            ])->assertOk()->assertJsonPath('data.answers.0.value', 'Practice');
    }
}
