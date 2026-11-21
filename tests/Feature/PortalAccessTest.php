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
}
