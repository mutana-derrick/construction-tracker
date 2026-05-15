<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Services\ExcelExportService;
use App\Services\PDFExportService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class ReportController extends Controller
{
    protected ExcelExportService $excelExportService;
    protected PDFExportService $pdfExportService;

    public function __construct(
        ExcelExportService $excelExportService,
        PDFExportService $pdfExportService
    ) {
        $this->excelExportService = $excelExportService;
        $this->pdfExportService = $pdfExportService;
    }

    public function daily(Request $request)
    {
        $project = $this->getSelectedProject($request);

        if (!$project) {
            return redirect()->route('projects.select')
                ->with('error', 'Please select a project first.');
        }

        $this->authorize('view', $project);

        $selectedDate = $request->query('date', now()->toDateString());
        $activities = $this->getDailyActivityCosts($project, $selectedDate);

        return view('reports.daily', [
            'project' => $project,
            'selectedDate' => $selectedDate,
            'activities' => $activities,
            'equipmentCostTotal' => $activities->sum('equipment_cost'),
            'labourCostTotal' => $activities->sum('labour_cost'),
            'materialCostTotal' => $activities->sum('material_cost'),
            'totalCost' => $activities->sum('total_cost'),
        ]);
    }

    public function monthly(Request $request)
    {
        $project = $this->getSelectedProject($request);

        if (!$project) {
            return redirect()->route('projects.select')
                ->with('error', 'Please select a project first.');
        }

        $this->authorize('view', $project);

        $selectedMonth = (int) $request->query('month', now()->month);
        $selectedYear = (int) $request->query('year', now()->year);
        $monthName = date('F', mktime(0, 0, 0, $selectedMonth, 1));

        $dailyRows = $this->getMonthlyDailyCosts($project, $selectedMonth, $selectedYear);

        return view('reports.monthly', [
            'project' => $project,
            'selectedMonth' => $selectedMonth,
            'selectedYear' => $selectedYear,
            'monthName' => $monthName,
            'dailyRows' => $dailyRows,
            'equipmentCostTotal' => $dailyRows->sum('equipment_cost'),
            'labourCostTotal' => $dailyRows->sum('labour_cost'),
            'materialCostTotal' => $dailyRows->sum('material_cost'),
            'totalCost' => $dailyRows->sum('total_cost'),
        ]);
    }

    public function getDailyExcel(Request $request)
    {
        $validated = $request->validate([
            'project_id' => 'required|exists:projects,id',
            'date' => 'required|date_format:Y-m-d',
        ]);

        $project = Project::findOrFail($validated['project_id']);
        $this->authorize('view', $project);

        try {
            $filepath = $this->excelExportService->exportDailyReport($project, $validated['date']);

            return response()->download(
                $filepath,
                "daily_report_{$project->name}_{$validated['date']}.xlsx",
                ['Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet']
            )->deleteFileAfterSend(true);
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to generate Excel report: ' . $e->getMessage());
        }
    }

    public function getDailyPDF(Request $request)
    {
        $validated = $request->validate([
            'project_id' => 'required|exists:projects,id',
            'date' => 'required|date_format:Y-m-d',
        ]);

        $project = Project::findOrFail($validated['project_id']);
        $this->authorize('view', $project);

        try {
            $filepath = $this->pdfExportService->exportDailyReport($project, $validated['date']);

            return response()->download(
                $filepath,
                "daily_report_{$project->name}_{$validated['date']}.pdf",
                ['Content-Type' => 'application/pdf']
            )->deleteFileAfterSend(true);
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to generate PDF report: ' . $e->getMessage());
        }
    }

    public function getMonthlyExcel(Request $request)
    {
        $validated = $request->validate([
            'project_id' => 'required|exists:projects,id',
            'month' => 'required|integer|between:1,12',
            'year' => 'required|integer|min:2020|max:2099',
        ]);

        $project = Project::findOrFail($validated['project_id']);
        $this->authorize('view', $project);

        try {
            $filepath = $this->excelExportService->exportMonthlyReport(
                $project,
                $validated['month'],
                $validated['year']
            );

            $monthName = date('F', mktime(0, 0, 0, $validated['month'], 1));

            return response()->download(
                $filepath,
                "monthly_report_{$project->name}_{$monthName}_{$validated['year']}.xlsx",
                ['Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet']
            )->deleteFileAfterSend(true);
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to generate Excel report: ' . $e->getMessage());
        }
    }

    public function getMonthlyPDF(Request $request)
    {
        $validated = $request->validate([
            'project_id' => 'required|exists:projects,id',
            'month' => 'required|integer|between:1,12',
            'year' => 'required|integer|min:2020|max:2099',
        ]);

        $project = Project::findOrFail($validated['project_id']);
        $this->authorize('view', $project);

        try {
            $filepath = $this->pdfExportService->exportMonthlyReport(
                $project,
                $validated['month'],
                $validated['year']
            );

            $monthName = date('F', mktime(0, 0, 0, $validated['month'], 1));

            return response()->download(
                $filepath,
                "monthly_report_{$project->name}_{$monthName}_{$validated['year']}.pdf",
                ['Content-Type' => 'application/pdf']
            )->deleteFileAfterSend(true);
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to generate PDF report: ' . $e->getMessage());
        }
    }

    private function getSelectedProject(Request $request): ?Project
    {
        $projectId = $request->query('project_id') ?? session('selected_project_id');

        return $projectId ? Project::find($projectId) : null;
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

    private function ensureActivity(Collection $activities, string $activity): void
    {
        if (!$activities->has($activity)) {
            $activities[$activity] = [
                'activity' => $activity,
                'equipment_cost' => 0,
                'labour_cost' => 0,
                'material_cost' => 0,
                'total_cost' => 0,
            ];
        }
    }

    private function ensureDay(Collection $days, string $date): void
    {
        if (!$days->has($date)) {
            $days[$date] = [
                'date' => $date,
                'equipment_cost' => 0,
                'labour_cost' => 0,
                'material_cost' => 0,
                'total_cost' => 0,
            ];
        }
    }
}