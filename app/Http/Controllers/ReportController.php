<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Services\ExcelExportService;
use App\Services\PDFExportService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

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

    /**
     * Display daily report generation form
     */
    public function daily(Request $request)
    {
        $projectId = $request->query('project_id') ?? session('selected_project_id');
        $project = $projectId ? Project::findOrFail($projectId) : null;
        
        $projects = Project::all();
        
        return view('reports.daily', [
            'projects' => $projects,
            'project' => $project,
        ]);
    }

    /**
     * Display monthly report generation form
     */
    public function monthly(Request $request)
    {
        $projectId = $request->query('project_id') ?? session('selected_project_id');
        $project = $projectId ? Project::findOrFail($projectId) : null;
        
        $projects = Project::all();
        
        return view('reports.monthly', [
            'projects' => $projects,
            'project' => $project,
        ]);
    }

    /**
     * Export daily report as Excel
     */
    public function getDailyExcel(Request $request)
    {
        $validated = $request->validate([
            'project_id' => 'required|exists:projects,id',
            'date' => 'required|date_format:Y-m-d',
        ]);

        // Check if user has access to project
        $project = Project::findOrFail($validated['project_id']);
        $this->authorize('view', $project);

        try {
            $filepath = $this->excelExportService->exportDailyReport(
                $project,
                $validated['date']
            );

            return response()->download(
                $filepath,
                "daily_report_{$project->name}_{$validated['date']}.xlsx",
                ['Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet']
            )->deleteFileAfterSend(true);
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to generate Excel report: ' . $e->getMessage());
        }
    }

    /**
     * Export daily report as PDF
     */
    public function getDailyPDF(Request $request)
    {
        $validated = $request->validate([
            'project_id' => 'required|exists:projects,id',
            'date' => 'required|date_format:Y-m-d',
        ]);

        // Check if user has access to project
        $project = Project::findOrFail($validated['project_id']);
        $this->authorize('view', $project);

        try {
            $filepath = $this->pdfExportService->exportDailyReport(
                $project,
                $validated['date']
            );

            return response()->download(
                $filepath,
                "daily_report_{$project->name}_{$validated['date']}.pdf",
                ['Content-Type' => 'application/pdf']
            )->deleteFileAfterSend(true);
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to generate PDF report: ' . $e->getMessage());
        }
    }

    /**
     * Export monthly report as Excel
     */
    public function getMonthlyExcel(Request $request)
    {
        $validated = $request->validate([
            'project_id' => 'required|exists:projects,id',
            'month' => 'required|integer|between:1,12',
            'year' => 'required|integer|min:2020|max:2099',
        ]);

        // Check if user has access to project
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

    /**
     * Export monthly report as PDF
     */
    public function getMonthlyPDF(Request $request)
    {
        $validated = $request->validate([
            'project_id' => 'required|exists:projects,id',
            'month' => 'required|integer|between:1,12',
            'year' => 'required|integer|min:2020|max:2099',
        ]);

        // Check if user has access to project
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
}