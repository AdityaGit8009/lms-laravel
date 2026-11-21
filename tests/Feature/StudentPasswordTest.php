<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class StudentPasswordTest extends TestCase
{
    use RefreshDatabase;

    public function test_password_changes_require_the_current_password_and_confirmation(): void
    {
        $current = 'current-test-pass';
        $next = 'updated-test-pass';
        $student = User::create([
            'name' => 'Test Learner', 'email' => 'password@example.test', 'role' => 'student',
            'approval_status' => 'Approved', 'password' => Hash::make($current),
        ]);
        $this->actingAs($student)->get(route('student.change_password'))->assertOk()
            ->assertSee(route('student.password.update'));
        $this->post(route('student.password.update'), [
            'current_password' => $current, 'password' => $next, 'password_confirmation' => 'different',
        ])->assertSessionHasErrors('password');
        $this->assertTrue(Hash::check($current, $student->fresh()->password));

        $this->post(route('student.password.update'), [
            'current_password' => $current, 'password' => $next, 'password_confirmation' => $next,
        ])->assertRedirect(route('student.change_password'))->assertSessionHas('success');
        $this->assertTrue(Hash::check($next, $student->fresh()->password));
    }
}
