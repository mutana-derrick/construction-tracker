<?php

namespace Database\Seeders;

use App\Models\Activity;
use Illuminate\Database\Seeder;

class ActivitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $activities = [
            'Foundation Pouring',
            'Excavation',
            'Backfilling',
            'Road Compaction',
            'Concrete Work',
        ];

        foreach ($activities as $name) {
            Activity::updateOrCreate(
                ['name' => $name],
                ['created_by' => null]
            );
        }
    }
}