<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (!Schema::hasTable('activities')) {
            return;
        }

        $tables = [
            'equipment_logs',
            'equipment_costs',
            'productivity_logs',
            'casual_labour_logs',
            'material_usage',
            'material_costs',
        ];

        foreach ($tables as $table) {
            if (!Schema::hasTable($table)) {
                continue;
            }

            if (!Schema::hasColumn($table, 'activity')) {
                continue;
            }

            if (!Schema::hasColumn($table, 'activity_id')) {
                continue;
            }

            // 1) Create activities from distinct free text (case/whitespace normalized)
            $rawRows = DB::table($table)
                ->whereNotNull('activity')
                ->select('activity')
                ->distinct()
                ->get();

            foreach ($rawRows as $row) {
                $name = $this->normalizeActivityName((string) $row->activity);

                if ($name === '') {
                    continue;
                }

                // insert ignore style
                DB::table('activities')->updateOrInsert(
                    ['name' => $name],
                    ['created_by' => null, 'created_at' => now(), 'updated_at' => now()]
                );
            }

            // 2) Backfill activity_id by matching normalized activity text to activities.name
            $activities = DB::table('activities')
                ->select('id', 'name')
                ->get();

            $map = [];
            foreach ($activities as $act) {
                $map[$this->normalizeActivityName((string) $act->name)] = (int) $act->id;
            }

            // Chunk through records with null activity_id
            DB::table($table)
                ->whereNull('activity_id')
                ->whereNotNull('activity')
                ->orderBy('id')
                ->chunkById(500, function ($rows) use ($table, $map) {
                    foreach ($rows as $r) {
                        $key = $this->normalizeActivityName((string) $r->activity);
                        $activityId = $map[$key] ?? null;

                        if (!$activityId) {
                            continue;
                        }

                        DB::table($table)
                            ->where('id', $r->id)
                            ->update([
                                'activity_id' => $activityId,
                                'updated_at' => now(),
                            ]);
                    }
                });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Intentionally no-op.
        // We don't want to delete activities records because they may be in active use.
        // If you need to rollback, rollback the schema migration that added activity_id.
    }

    private function normalizeActivityName(string $value): string
    {
        $value = trim(preg_replace('/\s+/', ' ', $value) ?? '');
        $value = mb_strtolower($value);

        // Title Case for display consistency
        $value = mb_convert_case($value, MB_CASE_TITLE, 'UTF-8');

        return trim($value);
    }
};
