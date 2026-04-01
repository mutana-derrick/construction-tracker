<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Project;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create test users with different roles
        $recorderUser = User::factory()->create([
            'name' => 'John Recorder',
            'email' => 'recorder@example.com',
            'role' => 'recorder',
        ]);

        $viewerUser = User::factory()->create([
            'name' => 'Jane Viewer',
            'email' => 'viewer@example.com',
            'role' => 'viewer',
        ]);

        // Create test projects
        $project1 = Project::create([
            'name' => 'Main Construction Site',
            'location' => '123 Main Street, City',
            'start_date' => now()->subDays(30),
            'expected_end_date' => now()->addDays(60),
            'budget' => 500000,
            'created_by' => $recorderUser->id,
        ]);

        $project2 = Project::create([
            'name' => 'Renovation Project',
            'location' => '456 Oak Avenue, Town',
            'start_date' => now()->subDays(15),
            'expected_end_date' => now()->addDays(45),
            'budget' => 250000,
            'created_by' => $recorderUser->id,
        ]);
    }
}
