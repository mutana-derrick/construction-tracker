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
        $this->call([
            ActivitySeeder::class,
        ]);

    // Use updateOrCreate to prevent duplicate errors on redeploy
    $recorderUser = User::updateOrCreate(
        ['email' => 'recorder@example.com'],
        [
            'name' => 'John Recorder',
            'role' => 'recorder',
            'password' => bcrypt('password'),
            'email_verified_at' => now(), 
        ]
    );

    $viewerUser = User::updateOrCreate(
        ['email' => 'viewer@example.com'],
        [
            'name' => 'Jane Viewer',
            'role' => 'viewer',
            'password' => bcrypt('password'),
            'email_verified_at' => now(),
        ]
    );

    // Create test projects only if they don't exist
    if (Project::count() == 0) {
        Project::create([
            'name' => 'Main Construction Site',
            'location' => '123 Main Street, City',
            'start_date' => now()->subDays(30),
            'expected_end_date' => now()->addDays(60),
            'budget' => 500000,
            'created_by' => $recorderUser->id,
        ]);

        Project::create([
            'name' => 'Renovation Project',
            'location' => '456 Oak Avenue, Town',
            'start_date' => now()->subDays(15),
            'expected_end_date' => now()->addDays(45),
            'budget' => 250000,
            'created_by' => $recorderUser->id,
        ]);
    }
    }
}