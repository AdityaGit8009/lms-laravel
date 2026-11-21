<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AcademicRoutesTest extends TestCase
{
    use RefreshDatabase;

    public function test_content_bundle_topic_picker_returns_json_for_the_selected_subject(): void
    {
        $this->actingAs(new User(['role' => 'admin']))
            ->getJson(route('admin.content-bundles.topics', 1))
            ->assertOk()
            ->assertExactJson(['status' => true, 'topics' => []]);
    }
}
