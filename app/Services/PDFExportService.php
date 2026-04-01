<?php

namespace App\Services;

use App\Models\Project;
use Dompdf\Dompdf;
use Illuminate\Support\Facades\View;

class PDFExportService
{
    /**
     * Export daily report to PDF
     */
    public function exportDailyReport(Project $project, string $date): string
    {
        $data = [
            'project' => $project,
            'date' => $date,
            'equipmentLogs' => $project->equipmentLogs()
                ->whereDate('date', $date)
                ->with('user')
                ->get(),
            'equipmentCosts' => $project->equipmentCosts()
                ->whereDate('date', $date)
                ->with('user')
                ->get(),
            'productivityLogs' => $project->productivityLogs()
                ->whereDate('date', $date)
                ->with('user')
                ->get(),
            'labourLogs' => $project->casualLabourLogs()
                ->whereDate('date', $date)
                ->with('user')
                ->get(),
            'materialUsage' => $project->materialUsage()
                ->whereDate('date', $date)
                ->with('user')
                ->get(),
            'materialCosts' => $project->materialCosts()
                ->whereDate('date', $date)
                ->with('user')
                ->get(),
        ];

        // Calculate totals
        $data['equipmentCostTotal'] = $data['equipmentCosts']->sum('total_cost');
        $data['labourCostTotal'] = $data['labourLogs']->sum('total_cost');
        $data['materialCostTotal'] = $data['materialCosts']->sum('total');
        $data['totalCost'] = $data['equipmentCostTotal'] + $data['labourCostTotal'] + $data['materialCostTotal'];

        // Generate HTML from view
        $html = View::make('exports.daily-report', $data)->render();

        // Create PDF
        $dompdf = new Dompdf();
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        // Save to file
        $filename = "daily_report_{$project->id}_{$date}_" . time() . '.pdf';
        $filepath = storage_path("app/exports/{$filename}");

        // Create directory if it doesn't exist
        if (!is_dir(dirname($filepath))) {
            mkdir(dirname($filepath), 0755, true);
        }

        file_put_contents($filepath, $dompdf->output());

        return $filepath;
    }

    /**
     * Export monthly report to PDF
     */
    public function exportMonthlyReport(Project $project, int $month, int $year): string
    {
        $data = [
            'project' => $project,
            'month' => $month,
            'year' => $year,
            'monthName' => date('F', mktime(0, 0, 0, $month, 1)),
            'equipmentSummary' => $this->getEquipmentSummary($project, $month, $year),
            'labourSummary' => $this->getLabourSummary($project, $month, $year),
            'materialSummary' => $this->getMaterialSummary($project, $month, $year),
        ];

        // Calculate totals
        $data['equipmentCostTotal'] = $project->equipmentCosts()
            ->whereYear('date', $year)
            ->whereMonth('date', $month)
            ->sum('total_cost');

        $data['labourCostTotal'] = $project->casualLabourLogs()
            ->whereYear('date', $year)
            ->whereMonth('date', $month)
            ->sum('total_cost');

        $data['materialCostTotal'] = $project->materialCosts()
            ->whereYear('date', $year)
            ->whereMonth('date', $month)
            ->sum('total');

        $data['totalCost'] = $data['equipmentCostTotal'] + $data['labourCostTotal'] + $data['materialCostTotal'];

        // Generate HTML from view
        $html = View::make('exports.monthly-report', $data)->render();

        // Create PDF
        $dompdf = new Dompdf();
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        // Save to file
        $filename = "monthly_report_{$project->id}_{$month}_{$year}_" . time() . '.pdf';
        $filepath = storage_path("app/exports/{$filename}");

        // Create directory if it doesn't exist
        if (!is_dir(dirname($filepath))) {
            mkdir(dirname($filepath), 0755, true);
        }

        file_put_contents($filepath, $dompdf->output());

        return $filepath;
    }

    /**
     * Get equipment summary for monthly report
     */
    private function getEquipmentSummary(Project $project, int $month, int $year): array
    {
        $logs = $project->equipmentCosts()
            ->whereYear('date', $year)
            ->whereMonth('date', $month)
            ->get();

        $summary = [];
        foreach ($logs->groupBy('equipment_type') as $equipmentType => $items) {
            $summary[] = [
                'equipment' => $equipmentType,
                'totalUnits' => $items->sum('units_done'),
                'totalCost' => $items->sum('total_cost'),
                'avgCost' => $items->count() > 0 ? $items->sum('total_cost') / $items->sum('units_done') : 0,
            ];
        }

        return $summary;
    }

    /**
     * Get labour summary for monthly report
     */
    private function getLabourSummary(Project $project, int $month, int $year): array
    {
        $logs = $project->casualLabourLogs()
            ->whereYear('date', $year)
            ->whereMonth('date', $month)
            ->get();

        $summary = [];
        foreach ($logs->groupBy('labour_classification') as $classification => $items) {
            $summary[] = [
                'classification' => $classification,
                'totalWorkers' => $items->sum('number_of_workers'),
                'totalCost' => $items->sum('total_cost'),
                'avgWage' => $items->sum('number_of_workers') > 0 ? $items->sum('total_cost') / $items->sum('number_of_workers') : 0,
            ];
        }

        return $summary;
    }

    /**
     * Get material summary for monthly report
     */
    private function getMaterialSummary(Project $project, int $month, int $year): array
    {
        $logs = $project->materialCosts()
            ->whereYear('date', $year)
            ->whereMonth('date', $month)
            ->get();

        $summary = [];
        foreach ($logs->groupBy('material_name') as $material => $items) {
            $summary[] = [
                'material' => $material,
                'totalQty' => $items->sum('used_qty'),
                'totalCost' => $items->sum('total'),
                'avgCost' => $items->sum('used_qty') > 0 ? $items->sum('total') / $items->sum('used_qty') : 0,
            ];
        }

        return $summary;
    }
}
