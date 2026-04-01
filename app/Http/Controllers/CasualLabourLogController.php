<?php

namespace App\Http\Controllers;

use App\Models\CasualLabourLog;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CasualLabourLogController extends Controller
{
    /**
     * Display a listing of casual labour logs for a project.
     */
    public function index(Request $request)
    {
        $projectId = $request->query('project_id');
        $project = Project::findOrFail($projectId);

        // Get all casual labour logs for this project
        $logs = CasualLabourLog::where('project_id', $projectId)
            ->with('user', 'project')
            ->orderBy('date', 'desc')
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('casual-labour-logs.index', [
            'project' => $project,
            'logs' => $logs,
        ]);
    }

    /**
     * Show the form for creating a new casual labour log record.
     */
    public function create(Request $request)
    {
        $projectId = $request->query('project_id');
        $project = Project::findOrFail($projectId);

        // Check authorization
        $this->authorize('create', CasualLabourLog::class);

        return view('casual-labour-logs.create', [
            'project' => $project,
        ]);
    }

    /**
     * Store a newly created casual labour log record in storage.
     */
    public function store(Request $request)
    {
        // Check authorization
        $this->authorize('create', CasualLabourLog::class);

        // Validate input
        $validated = $request->validate([
            'project_id' => 'required|exists:projects,id',
            'activity' => 'required|string|max:255',
            'labour_classification' => 'required|string|max:100',
            'number_of_workers' => 'required|integer|min:1',
            'wage' => 'required|numeric|min:0',
        ]);

        // Auto-assign current user and today's date
        $validated['user_id'] = Auth::id();
        $validated['date'] = now()->toDateString();

        // Calculate total cost
        $validated['total_cost'] = $validated['number_of_workers'] * $validated['wage'];

        try {
            CasualLabourLog::create($validated);
            return redirect()
                ->route('casual-labour-logs.index', ['project_id' => $validated['project_id']])
                ->with('success', 'Casual labour log record created successfully.');
        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->with('error', 'Failed to create casual labour log record: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified casual labour log record.
     */
    public function show(CasualLabourLog $casualLabourLog)
    {
        // Check authorization
        $this->authorize('view', $casualLabourLog);

        $casualLabourLog->load('user', 'project');

        return view('casual-labour-logs.show', [
            'log' => $casualLabourLog,
            'project' => $casualLabourLog->project,
            'canEdit' => Auth::check() && Auth::user()->can('update', $casualLabourLog),
        ]);
    }

    /**
     * Show the form for editing the specified casual labour log record.
     */
    public function edit(CasualLabourLog $casualLabourLog)
    {
        // Check authorization (includes 5-minute check)
        $this->authorize('update', $casualLabourLog);

        $casualLabourLog->load('project');

        return view('casual-labour-logs.edit', [
            'log' => $casualLabourLog,
            'project' => $casualLabourLog->project,
        ]);
    }

    /**
     * Update the specified casual labour log record in storage.
     * 
     * ⏱️ CRITICAL: 5-minute edit window enforced by policy
     */
    public function update(Request $request, CasualLabourLog $casualLabourLog)
    {
        // Check authorization (includes 5-minute check)
        $this->authorize('update', $casualLabourLog);

        // Validate input
        $validated = $request->validate([
            'activity' => 'required|string|max:255',
            'labour_classification' => 'required|string|max:100',
            'number_of_workers' => 'required|integer|min:1',
            'wage' => 'required|numeric|min:0',
        ]);

        // Calculate total cost
        $validated['total_cost'] = $validated['number_of_workers'] * $validated['wage'];

        try {
            $casualLabourLog->update($validated);
            return redirect()
                ->route('casual-labour-logs.show', $casualLabourLog)
                ->with('success', 'Casual labour log record updated successfully.');
        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->with('error', 'Failed to update casual labour log record: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified casual labour log record from storage.
     * 
     * ❌ DISABLED: No deletion allowed for audit compliance
     */
    public function destroy(CasualLabourLog $casualLabourLog)
    {
        return response()->json(
            ['message' => 'Casual labour log records cannot be deleted to maintain audit compliance.'],
            403
        );
    }
}
