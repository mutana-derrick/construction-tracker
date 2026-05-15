<?php

namespace App\Services;

use App\Models\Project;
use Carbon\Carbon;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Illuminate\Support\Collection;

class ExcelExportService
{
    public function exportDailyReport(Project $project, string $date): string
    {
        $activities = $this->getDailyActivityCosts($project, $date);

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Daily Report');

        $this->buildHeader($sheet, $project, 'Daily Site Report', Carbon::parse($date)->format('F d, Y'));
        $this->buildDailyTable($sheet, $activities, 9);
        $this->styleSheet($sheet);

        return $this->saveSpreadsheet($spreadsheet, "daily_report_{$project->id}_{$date}_" . time() . '.xlsx');
    }

    public function exportMonthlyReport(Project $project, int $month, int $year): string
    {
        $dailyRows = $this->getMonthlyDailyCosts($project, $month, $year);
        $monthName = date('F', mktime(0, 0, 0, $month, 1));

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Monthly Report');

        $this->buildHeader($sheet, $project, 'Monthly Cost Report', "{$monthName} {$year}");
        $this->buildMonthlyTable($sheet, $dailyRows, 9);
        $this->styleSheet($sheet);

        return $this->saveSpreadsheet($spreadsheet, "monthly_report_{$project->id}_{$month}_{$year}_" . time() . '.xlsx');
    }

    private function buildHeader(Worksheet $sheet, Project $project, string $reportType, string $period): void
    {
        $sheet->mergeCells('A1:E1');
        $sheet->setCellValue('A1', 'Construction Productivity Tracking System');

        $sheet->mergeCells('A2:E2');
        $sheet->setCellValue('A2', $reportType);

        $sheet->setCellValue('A4', 'Project');
        $sheet->setCellValue('B4', $project->name);

        $sheet->setCellValue('A5', 'Location');
        $sheet->setCellValue('B5', $project->location ?? 'N/A');

        $sheet->setCellValue('D4', 'Report Period');
        $sheet->setCellValue('E4', $period);

        $sheet->setCellValue('D5', 'Generated At');
        $sheet->setCellValue('E5', now()->format('F d, Y g:i A'));

        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(18);
        $sheet->getStyle('A2')->getFont()->setBold(true)->setSize(14);
        $sheet->getStyle('A4:A5')->getFont()->setBold(true);
        $sheet->getStyle('D4:D5')->getFont()->setBold(true);

        $sheet->getStyle('A1:E2')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('A4:E5')->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
        $sheet->getStyle('A4:A5')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB('F8FAFC');
        $sheet->getStyle('D4:D5')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB('F8FAFC');
    }

    private function buildDailyTable(Worksheet $sheet, Collection $activities, int $startRow): void
    {
        $headers = ['Activity', 'Equipment Cost', 'Labour Cost', 'Material Cost', 'Total Cost'];
        $sheet->fromArray($headers, null, "A{$startRow}");

        $row = $startRow + 1;

        foreach ($activities as $activity) {
            $sheet->setCellValue("A{$row}", $activity['activity']);
            $sheet->setCellValue("B{$row}", $activity['equipment_cost']);
            $sheet->setCellValue("C{$row}", $activity['labour_cost']);
            $sheet->setCellValue("D{$row}", $activity['material_cost']);
            $sheet->setCellValue("E{$row}", $activity['total_cost']);
            $row++;
        }

        $this->addTotalsRow($sheet, $row, [
            $activities->sum('equipment_cost'),
            $activities->sum('labour_cost'),
            $activities->sum('material_cost'),
            $activities->sum('total_cost'),
        ]);

        $this->styleReportTable($sheet, $startRow, $row);
    }

    private function buildMonthlyTable(Worksheet $sheet, Collection $dailyRows, int $startRow): void
    {
        $headers = ['Date', 'Equipment Cost', 'Labour Cost', 'Material Cost', 'Total Cost'];
        $sheet->fromArray($headers, null, "A{$startRow}");

        $row = $startRow + 1;

        foreach ($dailyRows as $dailyRow) {
            $sheet->setCellValue("A{$row}", Carbon::parse($dailyRow['date'])->format('M d, Y'));
            $sheet->setCellValue("B{$row}", $dailyRow['equipment_cost']);
            $sheet->setCellValue("C{$row}", $dailyRow['labour_cost']);
            $sheet->setCellValue("D{$row}", $dailyRow['material_cost']);
            $sheet->setCellValue("E{$row}", $dailyRow['total_cost']);
            $row++;
        }

        $this->addTotalsRow($sheet, $row, [
            $dailyRows->sum('equipment_cost'),
            $dailyRows->sum('labour_cost'),
            $dailyRows->sum('material_cost'),
            $dailyRows->sum('total_cost'),
        ]);

        $this->styleReportTable($sheet, $startRow, $row);
    }

    private function addTotalsRow(Worksheet $sheet, int $row, array $totals): void
    {
        $sheet->setCellValue("A{$row}", 'TOTAL');
        $sheet->setCellValue("B{$row}", $totals[0]);
        $sheet->setCellValue("C{$row}", $totals[1]);
        $sheet->setCellValue("D{$row}", $totals[2]);
        $sheet->setCellValue("E{$row}", $totals[3]);

        $sheet->getStyle("A{$row}:E{$row}")->getFont()->setBold(true);
        $sheet->getStyle("A{$row}:E{$row}")
            ->getFill()
            ->setFillType(Fill::FILL_SOLID)
            ->getStartColor()
            ->setRGB('FFEA9D');
    }

    private function styleReportTable(Worksheet $sheet, int $headerRow, int $lastRow): void
    {
        $sheet->getStyle("A{$headerRow}:E{$headerRow}")->getFont()->setBold(true);
        $sheet->getStyle("A{$headerRow}:E{$headerRow}")
            ->getFill()
            ->setFillType(Fill::FILL_SOLID)
            ->getStartColor()
            ->setRGB('E1AD01');

        $sheet->getStyle("A{$headerRow}:E{$lastRow}")
            ->getBorders()
            ->getAllBorders()
            ->setBorderStyle(Border::BORDER_THIN);

        $sheet->getStyle("B" . ($headerRow + 1) . ":E{$lastRow}")
            ->getNumberFormat()
            ->setFormatCode('"Rwf "#,##0');

        $sheet->getStyle("B{$headerRow}:E{$lastRow}")
            ->getAlignment()
            ->setHorizontal(Alignment::HORIZONTAL_RIGHT);
    }

    private function styleSheet(Worksheet $sheet): void
    {
        foreach (range('A', 'E') as $column) {
            $sheet->getColumnDimension($column)->setAutoSize(true);
        }

        $sheet->freezePane('A10');

        $sheet->getPageSetup()
            ->setOrientation(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::ORIENTATION_LANDSCAPE)
            ->setFitToWidth(1)
            ->setFitToHeight(0);
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
            $date = Carbon::parse($item->date)->format('Y-m-d');

            if (!isset($days[$date])) {
                $days[$date] = $this->emptyDayRow($date);
            }

            $days[$date]['equipment_cost'] += (float) $item->total_cost;
        }

        foreach ($project->casualLabourLogs()->whereYear('date', $year)->whereMonth('date', $month)->get() as $item) {
            $date = Carbon::parse($item->date)->format('Y-m-d');

            if (!isset($days[$date])) {
                $days[$date] = $this->emptyDayRow($date);
            }

            $days[$date]['labour_cost'] += (float) $item->total_cost;
        }

        foreach ($project->materialCosts()->whereYear('date', $year)->whereMonth('date', $month)->get() as $item) {
            $date = Carbon::parse($item->date)->format('Y-m-d');

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

    private function saveSpreadsheet(Spreadsheet $spreadsheet, string $filename): string
    {
        $filepath = storage_path("app/exports/{$filename}");

        if (!is_dir(dirname($filepath))) {
            mkdir(dirname($filepath), 0755, true);
        }

        $writer = new Xlsx($spreadsheet);
        $writer->save($filepath);

        return $filepath;
    }
}