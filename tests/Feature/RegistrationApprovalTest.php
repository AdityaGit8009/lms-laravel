<?php

namespace Tests\Feature;

use App\Models\RegisterStudent;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class RegistrationApprovalTest extends TestCase
{
    use RefreshDatabase;

    public function test_self_registration_needs_approval_before_login_can_resume_a_student_link(): void
    {
        $email = 'registration@example.test';
        $password = 'Learner-test-123';
        $this->postJson(route('api.register'), [
            'name' => 'Test Learner', 'email' => $email, 'password' => $password,
            'regno' => 'TESTA01', 'mobile' => '9000000003', 'college' => 'Sample College',
            'course' => '2', 'branch' => 'Sample', 'year' => '1', 'gender' => 'female',
            'pro' => '13', 'location' => 'House',
        ])->assertCreated();

        $registration = RegisterStudent::where('email', $email)->firstOrFail();
        $account = User::where('email', $email)->firstOrFail();
        $this->assertSame('Pending', $registration->approval_status);
        $this->assertTrue(Hash::check($password, $account->password));
        $this->postJson(route('api.login'), compact('email', 'password'))->assertForbidden();
        $this->assertGuest();

        $this->actingAs(new User(['role' => 'admin']))
            ->post(route('admin.approvals.updateStatus', $registration), ['approval_status' => 'Approved'])
            ->assertRedirect(route('admin.approvals'));
        $this->assertSame('Approved', $registration->fresh()->approval_status);
        $this->assertSame('Approved', $account->fresh()->approval_status);
        $this->postJson(route('api.logout'))->assertOk();
        $this->get(route('qr.attendance.form'))->assertRedirect(route('login'));
        $this->postJson(route('api.login'), compact('email', 'password'))->assertOk()
            ->assertJsonPath('redirect', route('qr.attendance.form'));
        $this->assertAuthenticatedAs($account);
    }

    public function test_an_unlinked_registration_stays_pending(): void
    {
        $registration = RegisterStudent::create(['email' => 'missing@example.test', 'approval_status' => 'Pending']);
        $this->actingAs(new User(['role' => 'admin']))
            ->post(route('admin.approvals.updateStatus', $registration), ['approval_status' => 'Approved'])
            ->assertSessionHasErrors('approval_status');
        $this->assertSame('Pending', $registration->fresh()->approval_status);
    }
}
