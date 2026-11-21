<?php

namespace Tests\Feature;

use App\Models\FeedbackForm;
use App\Models\User;
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
}
