<?php

namespace Tests\Feature;

use App\Models\User;
use Tests\TestCase;

class PortalAccessTest extends TestCase
{
    public function test_admin_pages_require_an_admin_session(): void
    {
        $this->get(route('admin.settings'))->assertRedirect(route('login'));
        $this->actingAs(new User(['role' => 'student']));
        $this->get(route('admin.settings'))->assertForbidden();
        $this->get(route('admin.student.login.status'))->assertForbidden();

        $this->actingAs(new User(['role' => 'Admin', 'name' => 'Test Admin']));
        $this->get(route('admin.settings'))->assertOk();
    }

    public function test_management_requests_use_the_administrator_session(): void
    {
        $this->getJson(route('admin.notifications.list'))->assertUnauthorized();
        $this->actingAs(new User(['role' => 'student']));
        $this->getJson(route('admin.notifications.list'))->assertForbidden();
        $this->postJson(route('admin.approvals.updateStatus', 1))->assertForbidden();
    }

    public function test_student_pages_share_approval_and_role_checks(): void
    {
        $admin = new User(['role' => 'admin']);
        $this->actingAs($admin)->get(route('student.profile'))->assertForbidden();
        $this->assertAuthenticatedAs($admin);

        $student = new User(['role' => 'student', 'approval_status' => 'Approved', 'name' => 'Test Student']);
        $this->actingAs($student)->get(route('student.profile'))->assertOk()->assertSee('Test Student');
        $student->is_block = 'Y';
        $this->get(route('student.feedback.index'))->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_signed_in_students_return_to_their_own_dashboard_from_login(): void
    {
        $this->actingAs(new User(['role' => 'student', 'approval_status' => 'Approved']))
            ->get(route('login'))->assertRedirect(route('student.dashboard'));
    }

    public function test_the_admin_profile_uses_the_account_role_and_an_avatar_fallback(): void
    {
        $admin = new User(['role' => 'admin', 'name' => 'Test Admin', 'image' => 'missing-test-image.png']);
        $this->actingAs($admin)->get(route('admin.profile'))->assertOk()
            ->assertSee('Test Admin')->assertSee('data-admin-avatar="preview"', false)
            ->assertDontSee('missing-test-image.png');
    }
}
