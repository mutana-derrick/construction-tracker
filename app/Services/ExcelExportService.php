<?php

namespace App\Services;

use App\Models\Project;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Color;

class ExcelExportService
{
    /**
     * Export daily reports to Excel
     */
    public function exportDailyReport(Project $project, string $date): string
    {
        $spreadsheet = new Spreadsheet();
        $spreadsheet->getProperties()
            ->setCreator('Construction Tracker')
            ->setTitle("Daily Report - {$project->name}")
            ->setSubject("Daily Report for {$date}");

        // Equipment Logs Sheet
        $this->addEquipmentLogsSheet($spreadsheet, $project, $date);

        // Equipment Costs Sheet
        $this->addEquipmentCostsSheet($spreadsheet, $project, $date);

        // Productivity Logs Sheet
        $this->addProductivityLogsSheet($spreadsheet, $project, $date);

        // Labour Logs Sheet
        $this->addLabourLogsSheet($spreadsheet, $project, $date);

        // Material Usage Sheet
        $this->addMaterialUsageSheet($spreadsheet, $project, $date);

        // Material Costs Sheet
        $this->addMaterialCostsSheet($spreadsheet, $project, $date);

        // Summary Sheet
        $this->addSummarySheet($spreadsheet, $project, $date);

        // Save to temporary file
        $filename = "daily_report_{$project->id}_{$date}_" . time() . '.xlsx';
        $filepath = storage_path("app/exports/{$filename}");

        // Create directory if it doesn't exist
        if (!is_dir(dirname($filepath))) {
            mkdir(dirname($filepath), 0755, true);
        }

        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
        $writer->save($filepath);

        return $filepath;
    }

    /**
     * Export monthly reports to Excel
     */
    public function exportMonthlyReport(Project $project, int $month, int $year): string
    {
        $spreadsheet = new Spreadsheet();
        $spreadsheet->getProperties()
            ->setCreator('Construction Tracker')
            ->setTitle("Monthly Report - {$project->name}")
            ->setSubject("Monthly Report for {$month}/{$year}");

        // Equipment Summary
        $this->addMonthlyEquipmentSummary($spreadsheet, $project, $month, $year);

        // Labour Summary
        $this->addMonthlyLabourSummary($spreadsheet, $project, $month, $year);

        // Material Summary
        $this->addMonthlyMaterialSummary($spreadsheet, $project, $month, $year);

        // Monthly Statistics
        $this->addMonthlyStatistics($spreadsheet, $project, $month, $year);

        // Save to temporary file
        $filename = "monthly_report_{$project->id}_{$month}_{$year}_" . time() . '.xlsx';
        $filepath = storage_path("app/exports/{$filename}");

        // Create directory if it doesn't exist
        if (!is_dir(dirname($filepath))) {
            mkdir(dirname($filepath), 0755, true);
        }

        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
        $writer->save($filepath);

        return $filepath;
    }

    /**
     * Add Equipment Logs sheet to spreadsheet
     */
    private function addEquipmentLogsSheet(Spreadsheet $spreadsheet, Project $project, string $date): void
    {
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Equipment Logs');

        $logs = $project->equipmentLogs()
            ->whereDate('date', $date)
            ->with('user')
            ->get();

        // Headers
        $headers = ['Date', 'Equipment Type', 'Activity', 'Hours Done', 'Output', 'Recorded By', 'Comment'];
        $this->styleHeaderRow($sheet, $headers);

        // Data
        $row = 2;
        foreach ($logs as $log) {
            $sheet->setCellValue("A{$row}", $log->date->format('m/d/Y'));
            $sheet->setCellValue("B{$row}", $log->equipment_type);
            $sheet->setCellValue("C{$row}", $log->activity);
            $sheet->setCellValue("D{$row}", $log->hours_done);
            $sheet->setCellValue("E{$row}", $log->output);
            $sheet->setCellValue("F{$row}", $log->user->name);
            $sheet->setCellValue("G{$row}", $log->comment ?? '');
            $row++;
        }

        $this->autoSizeColumns($sheet, count($headers));
    }

    /**
     * Add Equipment Costs sheet to spreadsheet
     */
    private function addEquipmentCostsSheet(Spreadsheet $spreadsheet, Project $project, string $date): void
    {
        $sheet = $spreadsheet->createSheet();
        $sheet->setTitle('Equipment Costs');

        $logs = $project->equipmentCosts()
            ->whereDate('date', $date)
            ->with('user')
            ->get();

        // Headers
        $headers = ['Date', 'Equipment Type', 'Activity', 'Units Done', 'Cost per Unit', 'Total Cost', 'Recorded By'];
        $this->styleHeaderRow($sheet, $headers);

        // Data
        $row = 2;
        foreach ($logs as $log) {
            $sheet->setCellValue("A{$row}", $log->date->format('m/d/Y'));
            $sheet->setCellValue("B{$row}", $log->equipment_type);
            $sheet->setCellValue("C{$row}", $log->activity);
            $sheet->setCellValue("D{$row}", $log->units_done);
            $sheet->setCellValue("E{$row}", $log->cost_per_unit);
            $sheet->setCellValue("F{$row}", $log->total_cost);
            $sheet->setCellValue("G{$row}", $log->user->name);
            $row++;
        }

        $this->autoSizeColumns($sheet, count($headers));
    }

    /**
     * Add Productivity Logs sheet to spreadsheet
     */
    private function addProductivityLogsSheet(Spreadsheet $spreadsheet, Project $project, string $date): void
    {
        $sheet = $spreadsheet->createSheet();
        $sheet->setTitle('Productivity Logs');

        $logs = $project->productivityLogs()
            ->whereDate('date', $date)
            ->with('user')
            ->get();

        // Headers
        $headers = ['Date', 'Activity', 'Equipment', 'Workers', 'Output', 'Output/Worker', 'Recorded By'];
        $this->styleHeaderRow($sheet, $headers);

        // Data
        $row = 2;
        foreach ($logs as $log) {
            $sheet->setCellValue("A{$row}", $log->date->format('m/d/Y'));
            $sheet->setCellValue("B{$row}", $log->activity);
            $sheet->setCellValue("C{$row}", $log->equipment_name);
            $sheet->setCellValue("D{$row}", $log->workers);
            $sheet->setCellValue("E{$row}", $log->output);
            $sheet->setCellValue("F{$row}", $log->output / max(1, $log->workers));
            $sheet->setCellValue("G{$row}", $log->user->name);
            $row++;
        }

        $this->autoSizeColumns($sheet, count($headers));
    }

    /**
     * Add Labour Logs sheet to spreadsheet
     */
    private function addLabourLogsSheet(Spreadsheet $spreadsheet, Project $project, string $date): void
    {
        $sheet = $spreadsheet->createSheet();
        $sheet->setTitle('Labour Logs');

        $logs = $project->casualLabourLogs()
            ->whereDate('date', $date)
            ->with('user')
            ->get();

        // Headers
        $headers = ['Date', 'Activity', 'Classification', 'Workers', 'Wage', 'Total Cost', 'Recorded By'];
        $this->styleHeaderRow($sheet, $headers);

        // Data
        $row = 2;
        foreach ($logs as $log) {
            $sheet->setCellValue("A{$row}", $log->date->format('m/d/Y'));
            $sheet->setCellValue("B{$row}", $log->activity);
            $sheet->setCellValue("C{$row}", $log->labour_classification);
            $sheet->setCellValue("D{$row}", $log->number_of_workers);
            $sheet->setCellValue("E{$row}", $log->wage);
            $sheet->setCellValue("F{$row}", $log->total_cost);
            $sheet->setCellValue("G{$row}", $log->user->name);
            $row++;
        }

        $this->autoSizeColumns($sheet, count($headers));
    }

    /**
     * Add Material Usage sheet to spreadsheet
     */
    private function addMaterialUsageSheet(Spreadsheet $spreadsheet, Project $project, string $date): void
    {
        $sheet = $spreadsheet->createSheet();
        $sheet->setTitle('Material Usage');

        $logs = $project->materialUsage()
            ->whereDate('date', $date)
            ->with('user')
            ->get();

        // Headers
        $headers = ['Date', 'Material', 'Activity', 'Planned Qty', 'Used Qty', 'Difference', 'Recorded By'];
        $this->styleHeaderRow($sheet, $headers);

        // Data
        $row = 2;
        foreach ($logs as $log) {
            $sheet->setCellValue("A{$row}", $log->date->format('m/d/Y'));
            $sheet->setCellValue("B{$row}", $log->material_name);
            $sheet->setCellValue("C{$row}", $log->activity);
            $sheet->setCellValue("D{$row}", $log->planned_qty);
            $sheet->setCellValue("E{$row}", $log->used_qty);
            $sheet->setCellValue("F{$row}", $log->planned_qty - $log->used_qty);
            $sheet->setCellValue("G{$row}", $log->user->name);
            $row++;
        }

        $this->autoSizeColumns($sheet, count($headers));
    }

    /**
     * Add Material Costs sheet to spreadsheet
     */
    private function addMaterialCostsSheet(Spreadsheet $spreadsheet, Project $project, string $date): void
    {
        $sheet = $spreadsheet->createSheet();
        $sheet->setTitle('Material Costs');

        $logs = $project->materialCosts()
            ->whereDate('date', $date)
            ->with('user')
            ->get();

        // Headers
        $headers = ['Date', 'Material', 'Quantity Used', 'Cost per Item', 'Total Cost', 'Recorded By'];
        $this->styleHeaderRow($sheet, $headers);

        // Data
        $row = 2;
        foreach ($logs as $log) {
            $sheet->setCellValue("A{$row}", $log->date->format('m/d/Y'));
            $sheet->setCellValue("B{$row}", $log->material_name);
            $sheet->setCellValue("C{$row}", $log->used_qty);
            $sheet->setCellValue("D{$row}", $log->cost_per_item);
            $sheet->setCellValue("E{$row}", $log->total);
            $sheet->setCellValue("F{$row}", $log->user->name);
            $row++;
        }

        $this->autoSizeColumns($sheet, count($headers));
    }

    /**
     * Add Summary sheet to spreadsheet
     */
    private function addSummarySheet(Spreadsheet $spreadsheet, Project $project, string $date): void
    {
        $sheet = $spreadsheet->createSheet();
        $sheet->setTitle('Summary');

        $row = 1;

        // Title
        $sheet->setCellValue("A{$row}", "Daily Report Summary");
        $sheet->getStyle("A{$row}")->getFont()->setBold(true)->setSize(14);
        $row += 2;

        // Project info
        $sheet->setCellValue("A{$row}", "Project:");
        $sheet->setCellValue("B{$row}", $project->name);
        $row++;
        $sheet->setCellValue("A{$row}", "Date:");
        $sheet->setCellValue("B{$row}", $date);
        $row += 2;

        // Summary statistics
        $equipmentLogCount = $project->equipmentLogs()->whereDate('date', $date)->count();
        $equipmentCostTotal = $project->equipmentCosts()->whereDate('date', $date)->sum('total_cost');
        $labourCostTotal = $project->casualLabourLogs()->whereDate('date', $date)->sum('total_cost');
        $materialCostTotal = $project->materialCosts()->whereDate('date', $date)->sum('total');

        $sheet->setCellValue("A{$row}", "Equipment Log Records:");
        $sheet->setCellValue("B{$row}", $equipmentLogCount);
        $row++;
        $sheet->setCellValue("A{$row}", "Equipment Costs:");
        $sheet->setCellValue("B{$row}", '$' . number_format($equipmentCostTotal, 2));
        $row++;
        $sheet->setCellValue("A{$row}", "Labour Costs:");
        $sheet->setCellValue("B{$row}", '$' . number_format($labourCostTotal, 2));
        $row++;
        $sheet->setCellValue("A{$row}", "Material Costs:");
        $sheet->setCellValue("B{$row}", '$' . number_format($materialCostTotal, 2));
        $row++;
        $sheet->setCellValue("A{$row}", "Total Costs:");
        $sheet->setCellValue("B{$row}", '$' . number_format($equipmentCostTotal + $labourCostTotal + $materialCostTotal, 2));

        $this->autoSizeColumns($sheet, 2);
    }

    /**
     * Add Monthly Equipment Summary sheet
     */
    private function addMonthlyEquipmentSummary(Spreadsheet $spreadsheet, Project $project, int $month, int $year): void
    {
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Equipment Summary');

        $logs = $project->equipmentCosts()
            ->whereYear('date', $year)
            ->whereMonth('date', $month)
            ->with('user')
            ->get();

        // Headers
        $headers = ['Equipment Type', 'Total Units', 'Total Cost', 'Avg Cost/Unit'];
        $this->styleHeaderRow($sheet, $headers);

        // Group by equipment type
        $grouped = $logs->groupBy('equipment_type');
        $row = 2;

        foreach ($grouped as $equipmentType => $items) {
            $totalUnits = $items->sum('units_done');
            $totalCost = $items->sum('total_cost');
            $avgCost = $totalUnits > 0 ? $totalCost / $totalUnits : 0;

            $sheet->setCellValue("A{$row}", $equipmentType);
            $sheet->setCellValue("B{$row}", $totalUnits);
            $sheet->setCellValue("C{$row}", $totalCost);
            $sheet->setCellValue("D{$row}", $avgCost);
            $row++;
        }

        $this->autoSizeColumns($sheet, count($headers));
    }

    /**
     * Add Monthly Labour Summary sheet
     */
    private function addMonthlyLabourSummary(Spreadsheet $spreadsheet, Project $project, int $month, int $year): void
    {
        $sheet = $spreadsheet->createSheet();
        $sheet->setTitle('Labour Summary');

        $logs = $project->casualLabourLogs()
            ->whereYear('date', $year)
            ->whereMonth('date', $month)
            ->with('user')
            ->get();

        // Headers
        $headers = ['Classification', 'Total Workers', 'Total Wages', 'Avg Wage/Worker'];
        $this->styleHeaderRow($sheet, $headers);

        // Group by classification
        $grouped = $logs->groupBy('labour_classification');
        $row = 2;

        foreach ($grouped as $classification => $items) {
            $totalWorkers = $items->sum('number_of_workers');
            $totalCost = $items->sum('total_cost');
            $avgWage = $totalWorkers > 0 ? $totalCost / $totalWorkers : 0;

            $sheet->setCellValue("A{$row}", $classification);
            $sheet->setCellValue("B{$row}", $totalWorkers);
            $sheet->setCellValue("C{$row}", $totalCost);
            $sheet->setCellValue("D{$row}", $avgWage);
            $row++;
        }

        $this->autoSizeColumns($sheet, count($headers));
    }

    /**
     * Add Monthly Material Summary sheet
     */
    private function addMonthlyMaterialSummary(Spreadsheet $spreadsheet, Project $project, int $month, int $year): void
    {
        $sheet = $spreadsheet->createSheet();
        $sheet->setTitle('Material Summary');

        $logs = $project->materialCosts()
            ->whereYear('date', $year)
            ->whereMonth('date', $month)
            ->with('user')
            ->get();

        // Headers
        $headers = ['Material', 'Total Quantity', 'Total Cost', 'Avg Cost/Item'];
        $this->styleHeaderRow($sheet, $headers);

        // Group by material
        $grouped = $logs->groupBy('material_name');
        $row = 2;

        foreach ($grouped as $material => $items) {
            $totalQty = $items->sum('used_qty');
            $totalCost = $items->sum('total');
            $avgCost = $totalQty > 0 ? $totalCost / $totalQty : 0;

            $sheet->setCellValue("A{$row}", $material);
            $sheet->setCellValue("B{$row}", $totalQty);
            $sheet->setCellValue("C{$row}", $totalCost);
            $sheet->setCellValue("D{$row}", $avgCost);
            $row++;
        }

        $this->autoSizeColumns($sheet, count($headers));
    }

    /**
     * Add Monthly Statistics sheet
     */
    private function addMonthlyStatistics(Spreadsheet $spreadsheet, Project $project, int $month, int $year): void
    {
        $sheet = $spreadsheet->createSheet();
        $sheet->setTitle('Statistics');

        $row = 1;

        // Title
        $sheet->setCellValue("A{$row}", "Monthly Statistics");
        $sheet->getStyle("A{$row}")->getFont()->setBold(true)->setSize(14);
        $row += 2;

        // Project info
        $sheet->setCellValue("A{$row}", "Project:");
        $sheet->setCellValue("B{$row}", $project->name);
        $row++;
        $sheet->setCellValue("A{$row}", "Period:");
        $sheet->setCellValue("B{$row}", date('F Y', mktime(0, 0, 0, $month, 1, $year)));
        $row += 2;

        // Summary statistics
        $equipmentCostTotal = $project->equipmentCosts()
            ->whereYear('date', $year)
            ->whereMonth('date', $month)
            ->sum('total_cost');

        $labourCostTotal = $project->casualLabourLogs()
            ->whereYear('date', $year)
            ->whereMonth('date', $month)
            ->sum('total_cost');

        $materialCostTotal = $project->materialCosts()
            ->whereYear('date', $year)
            ->whereMonth('date', $month)
            ->sum('total');

        $totalCost = $equipmentCostTotal + $labourCostTotal + $materialCostTotal;

        $sheet->setCellValue("A{$row}", "Equipment Costs:");
        $sheet->setCellValue("B{$row}", '$' . number_format($equipmentCostTotal, 2));
        $row++;
        $sheet->setCellValue("A{$row}", "Labour Costs:");
        $sheet->setCellValue("B{$row}", '$' . number_format($labourCostTotal, 2));
        $row++;
        $sheet->setCellValue("A{$row}", "Material Costs:");
        $sheet->setCellValue("B{$row}", '$' . number_format($materialCostTotal, 2));
        $row++;
        $sheet->setCellValue("A{$row}", "Total Costs:");
        $sheet->setCellValue("B{$row}", '$' . number_format($totalCost, 2));

        $this->autoSizeColumns($sheet, 2);
    }

    /**
     * Style header row with background color and bold text
     */
    private function styleHeaderRow($sheet, array $headers): void
    {
        $col = 'A';
        foreach ($headers as $header) {
            $sheet->setCellValue("{$col}1", $header);
            $sheet->getStyle("{$col}1")->getFont()->setBold(true)->setColor(new Color('FFFFFF'));
            $sheet->getStyle("{$col}1")->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('FF1F4E78');
            $col++;
        }
    }

    /**
     * Auto-size columns based on content
     */
    private function autoSizeColumns($sheet, int $columnCount): void
    {
        for ($i = 0; $i < $columnCount; $i++) {
            $sheet->getColumnDimensionByColumn($i + 1)->setAutoSize(true);
        }
    }
}
