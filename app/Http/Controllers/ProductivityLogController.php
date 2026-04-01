<?php

namespace App\Http\Controllers;

use App\Models\ProductivityLog;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProductivityLogController extends Controller
{
    /**
     * Display a listing of productivity logs for a project.
     */
    public function index(Request $request)
    {
        $projectId = $request->query('project_id');
        $project = Project::findOrFail($projectId);

        // Get all productivity logs for this project
        $logs = ProductivityLog::where('project_id', $projectId)
            ->with('user', 'project')
            ->orderBy('date', 'desc')
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('productivity-logs.index', [
            'project' => $project,
            'logs' => $logs,
        ]);
    }

    /**
     * Show the form for creating a new productivity log record.
     */
    public function create(Request $request)
    {
        $projectId = $request->query('project_id');
        $project = Project::findOrFail($projectId);

        // Check authorization
        $this->authorize('create', ProductivityLog::class);

        return view('productivity-logs.create', [
            'project' => $project,
        ]);
    }

    /**
     * Store a newly created productivity log record in storage.
     */
    public function store(Request $request)
    {
        // Check authorization
        $this->authorize('create', ProductivityLog::class);

        // Validate input
        $validated = $request->validate([
            'project_id' => 'required|exists:projects,id',
            'activity' => 'required|string|max:255',
            'equipment_name' => 'required|string|max:100',
            'workers' => 'required|integer|min:1',
            'output' => 'required|numeric|min:0',
            'comment' => 'nullable|string|max:500',
        ]);

        // Auto-assign current user and today's date
        $validated['user_id'] = Auth::id();
        $validated['date'] = now()->toDateString();

        try {
            ProductivityLog::create($validated);
            return redirect()
                ->route('productivity-logs.index', ['project_id' => $validated['project_id']])
                ->with('success', 'Productivity log record created successfully.');
        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->with('error', 'Failed to create productivity log record: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified productivity log record.
     */
    public function show(ProductivityLog $productivityLog)
    {
        // Check authorization
        $this->authorize('view', $productivityLog);

        $productivityLog->load('user', 'project');

        return view('productivity-logs.show', [
            'log' => $productivityLog,
            'project' => $productivityLog->project,
            'canEdit' => Auth::check() && Auth::user()->can('update', $productivityLog),
        ]);
    }

    /**
     * Show the form for editing the specified productivity log record.
     */
    public function edit(ProductivityLog $productivityLog)
    {
        // Check authorization (includes 5-minute check)
        $this->authorize('update', $productivityLog);

        $productivityLog->load('project');

        return view('productivity-logs.edit', [
            'log' => $productivityLog,
            'project' => $productivityLog->project,
        ]);
    }

    /**
     * Update the specified productivity log record in storage.
     * 
     * ⏱️ CRITICAL: 5-minute edit window enforced by policy
     */
    public function update(Request $request, ProductivityLog $productivityLog)
    {
        // Check authorization (includes 5-minute check)
        $this->authorize('update', $productivityLog);

        // Validate input
        $validated = $request->validate([
            'activity' => 'required|string|max:255',
            'equipment_name' => 'required|string|max:100',
            'workers' => 'required|integer|min:1',
            'output' => 'required|numeric|min:0',
            'comment' => 'nullable|string|max:500',
        ]);

        try {
            $productivityLog->update($validated);
            return redirect()
                ->route('productivity-logs.show', $productivityLog)
                ->with('success', 'Productivity log record updated successfully.');
        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->with('error', 'Failed to update productivity log record: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified productivity log record from storage.
     * 
     * ❌ DISABLED: No deletion allowed for audit compliance
     */
    public function destroy(ProductivityLog $productivityLog)
    {
        return response()->json(
            ['message' => 'Productivity log records cannot be deleted to maintain audit compliance.'],
            403
        );
    }
}
