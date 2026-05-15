<?php

namespace App\Services;

use App\Models\Project;
use Dompdf\Dompdf;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\View;

class PDFExportService
{
    public function exportDailyReport(Project $project, string $date): string
    {
        $activities = $this->getDailyActivityCosts($project, $date);

        $data = [
            'project' => $project,
            'date' => $date,
            'activities' => $activities,
            'equipmentCostTotal' => $activities->sum('equipment_cost'),
            'labourCostTotal' => $activities->sum('labour_cost'),
            'materialCostTotal' => $activities->sum('material_cost'),
            'totalCost' => $activities->sum('total_cost'),
        ];

        $html = View::make('exports.daily-report', $data)->render();

        return $this->savePdf(
            $html,
            "daily_report_{$project->id}_{$date}_" . time() . '.pdf'
        );
    }

    public function exportMonthlyReport(Project $project, int $month, int $year): string
    {
        $dailyRows = $this->getMonthlyDailyCosts($project, $month, $year);
        $monthName = date('F', mktime(0, 0, 0, $month, 1));

        $data = [
            'project' => $project,
            'month' => $month,
            'year' => $year,
            'monthName' => $monthName,
            'dailyRows' => $dailyRows,
            'equipmentCostTotal' => $dailyRows->sum('equipment_cost'),
            'labourCostTotal' => $dailyRows->sum('labour_cost'),
            'materialCostTotal' => $dailyRows->sum('material_cost'),
            'totalCost' => $dailyRows->sum('total_cost'),
        ];

        $html = View::make('exports.monthly-report', $data)->render();

        return $this->savePdf(
            $html,
            "monthly_report_{$project->id}_{$month}_{$year}_" . time() . '.pdf'
        );
    }

    private function getDailyActivityCosts(Project $project, string $date): Collection
    {
        $activities = [];

        foreach ($project->equipmentCosts()->whereDate('date', $date)->get() as $item) {
            $activity = $item->activity;

            if (!isset($activities[$activity])) {
                $activities[$activity] = $this->emptyActivityRow($activity);
            }

            $activities[$activity]['equipment_cost'] += (float) $item->total_cost;
        }

        foreach ($project->casualLabourLogs()->whereDate('date', $date)->get() as $item) {
            $activity = $item->activity;

            if (!isset($activities[$activity])) {
                $activities[$activity] = $this->emptyActivityRow($activity);
            }

            $activities[$activity]['labour_cost'] += (float) $item->total_cost;
        }

        foreach ($project->materialCosts()->whereDate('date', $date)->get() as $item) {
            $activity = $item->activity ?? 'Material Cost';

            if (!isset($activities[$activity])) {
                $activities[$activity] = $this->emptyActivityRow($activity);
            }

            $activities[$activity]['material_cost'] += (float) $item->total;
        }

        foreach ($activities as &$activity) {
            $activity['total_cost'] =
                $activity['equipment_cost'] +
                $activity['labour_cost'] +
                $activity['material_cost'];
        }

        return collect($activities)->sortBy('activity')->values();
    }

    private function getMonthlyDailyCosts(Project $project, int $month, int $year): Collection
    {
        $days = [];

        foreach ($project->equipmentCosts()->whereYear('date', $year)->whereMonth('date', $month)->get() as $item) {
            $date = \Carbon\Carbon::parse($item->date)->format('Y-m-d');

            if (!isset($days[$date])) {
                $days[$date] = $this->emptyDayRow($date);
            }

            $days[$date]['equipment_cost'] += (float) $item->total_cost;
        }

        foreach ($project->casualLabourLogs()->whereYear('date', $year)->whereMonth('date', $month)->get() as $item) {
            $date = \Carbon\Carbon::parse($item->date)->format('Y-m-d');

            if (!isset($days[$date])) {
                $days[$date] = $this->emptyDayRow($date);
            }

            $days[$date]['labour_cost'] += (float) $item->total_cost;
        }

        foreach ($project->materialCosts()->whereYear('date', $year)->whereMonth('date', $month)->get() as $item) {
            $date = \Carbon\Carbon::parse($item->date)->format('Y-m-d');

            if (!isset($days[$date])) {
                $days[$date] = $this->emptyDayRow($date);
            }

            $days[$date]['material_cost'] += (float) $item->total;
        }

        foreach ($days as &$day) {
            $day['total_cost'] =
                $day['equipment_cost'] +
                $day['labour_cost'] +
                $day['material_cost'];
        }

        return collect($days)->sortBy('date')->values();
    }

    private function emptyActivityRow(string $activity): array
    {
        return [
            'activity' => $activity,
            'equipment_cost' => 0,
            'labour_cost' => 0,
            'material_cost' => 0,
            'total_cost' => 0,
        ];
    }

    private function emptyDayRow(string $date): array
    {
        return [
            'date' => $date,
            'equipment_cost' => 0,
            'labour_cost' => 0,
            'material_cost' => 0,
            'total_cost' => 0,
        ];
    }




    private function savePdf(string $html, string $filename): string
    {
        $dompdf = new Dompdf();
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        $filepath = storage_path("app/exports/{$filename}");

        if (!is_dir(dirname($filepath))) {
            mkdir(dirname($filepath), 0755, true);
        }

        file_put_contents($filepath, $dompdf->output());

        return $filepath;
    }
}