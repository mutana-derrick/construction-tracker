<?php

namespace Tests\Feature;

use App\Models\Project;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProjectAccessControlTest extends TestCase
{
    use RefreshDatabase;

    public function test_recorder_cannot_view_other_recorders_project_scoped_pages(): void
    {
        $recorderA = User::factory()->recorder()->create();
        $recorderB = User::factory()->recorder()->create();

        $projectOwnedByB = Project::create([
            'name' => 'Other user project',
            'location' => 'Site B',
            'description' => 'Test',
            'budget' => 1000,
            'start_date' => now()->toDateString(),
            'expected_end_date' => now()->addDay()->toDateString(),
            'created_by' => $recorderB->id,
        ]);

        $this->actingAs($recorderA);

        $pages = [
            route('equipment.management', ['project_id' => $projectOwnedByB->id]),
            route('material.management', ['project_id' => $projectOwnedByB->id]),
            route('equipment-logs.index', ['project_id' => $projectOwnedByB->id]),
            route('equipment-logs.create', ['project_id' => $projectOwnedByB->id]),
            route('equipment-costs.index', ['project_id' => $projectOwnedByB->id]),
            route('equipment-costs.create', ['project_id' => $projectOwnedByB->id]),
            route('productivity-logs.index', ['project_id' => $projectOwnedByB->id]),
            route('productivity-logs.create', ['project_id' => $projectOwnedByB->id]),
            route('casual-labour-logs.index', ['project_id' => $projectOwnedByB->id]),
            route('casual-labour-logs.create', ['project_id' => $projectOwnedByB->id]),
            route('material-usage.index', ['project_id' => $projectOwnedByB->id]),
            route('material-usage.create', ['project_id' => $projectOwnedByB->id]),
            route('material-costs.index', ['project_id' => $projectOwnedByB->id]),
            route('material-costs.create', ['project_id' => $projectOwnedByB->id]),
            route('reports.daily', ['project_id' => $projectOwnedByB->id]),
            route('reports.monthly', ['project_id' => $projectOwnedByB->id]),
        ];

        foreach ($pages as $url) {
            $this->get($url)->assertForbidden();
        }
    }

    public function test_recorder_cannot_post_log_into_other_users_project(): void
    {
        $recorderA = User::factory()->recorder()->create();
        $recorderB = User::factory()->recorder()->create();

        $projectOwnedByB = Project::create([
            'name' => 'Other user project',
            'location' => 'Site B',
            'description' => 'Test',
            'budget' => 1000,
            'start_date' => now()->toDateString(),
            'expected_end_date' => now()->addDay()->toDateString(),
            'created_by' => $recorderB->id,
        ]);

        $this->actingAs($recorderA);

        $activityId = \App\Models\Activity::query()->create([
            'name' => 'Excavation',
            'created_by' => null,
        ])->id;

        $payload = [
            'project_id' => $projectOwnedByB->id,
            'activity_id' => $activityId,
            'equipment_name' => 'Excavator',
            'workers' => 2,
            'output' => 10,
            'comment' => 'Attempt',
        ];

        $this->post(route('productivity-logs.store'), $payload)->assertForbidden();

        $this->assertDatabaseCount('productivity_logs', 0);
    }
}
