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

    public function test_attendance_report_and_data_route_share_the_date_range_and_totals(): void
    {
        $filters = ['from_date' => '2026-01-09', 'to_date' => '2026-01-03'];
        $this->actingAs(new User(['role' => 'admin', 'name' => 'Test Admin']));
        $this->getJson(route('admin.viewattandence.data', $filters))->assertOk()
            ->assertJsonPath('fromDate', '2026-01-03')
            ->assertJsonPath('toDate', '2026-01-09')
            ->assertJsonPath('totalStudents', 0);
        $this->get(route('admin.viewattandence', $filters))->assertOk()
            ->assertViewHas('fromDate', '2026-01-03')
            ->assertViewHas('totalStudents', 0);
    }
}
