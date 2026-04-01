<?php

namespace App\Http\Controllers;

use App\Models\MaterialUsage;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MaterialUsageController extends Controller
{
    /**
     * Display a listing of material usage logs for a project.
     */
    public function index(Request $request)
    {
        $projectId = $request->query('project_id');
        $project = Project::findOrFail($projectId);

        // Get all material usage logs for this project
        $logs = MaterialUsage::where('project_id', $projectId)
            ->with('user', 'project')
            ->orderBy('date', 'desc')
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('material-usage.index', [
            'project' => $project,
            'logs' => $logs,
        ]);
    }

    /**
     * Show the form for creating a new material usage log record.
     */
    public function create(Request $request)
    {
        $projectId = $request->query('project_id');
        $project = Project::findOrFail($projectId);

        // Check authorization
        $this->authorize('create', MaterialUsage::class);

        return view('material-usage.create', [
            'project' => $project,
        ]);
    }

    /**
     * Store a newly created material usage log record in storage.
     */
    public function store(Request $request)
    {
        // Check authorization
        $this->authorize('create', MaterialUsage::class);

        // Validate input
        $validated = $request->validate([
            'project_id' => 'required|exists:projects,id',
            'material_name' => 'required|string|max:100',
            'activity' => 'required|string|max:255',
            'planned_qty' => 'required|numeric|min:0',
            'used_qty' => 'required|numeric|min:0',
        ]);

        // Auto-assign current user and today's date
        $validated['user_id'] = Auth::id();
        $validated['date'] = now()->toDateString();

        try {
            MaterialUsage::create($validated);
            return redirect()
                ->route('material-usage.index', ['project_id' => $validated['project_id']])
                ->with('success', 'Material usage log record created successfully.');
        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->with('error', 'Failed to create material usage log record: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified material usage log record.
     */
    public function show(MaterialUsage $materialUsage)
    {
        // Check authorization
        $this->authorize('view', $materialUsage);

        $materialUsage->load('user', 'project');

        return view('material-usage.show', [
            'log' => $materialUsage,
            'project' => $materialUsage->project,
            'canEdit' => Auth::check() && Auth::user()->can('update', $materialUsage),
        ]);
    }

    /**
     * Show the form for editing the specified material usage log record.
     */
    public function edit(MaterialUsage $materialUsage)
    {
        // Check authorization (includes 5-minute check)
        $this->authorize('update', $materialUsage);

        $materialUsage->load('project');

        return view('material-usage.edit', [
            'log' => $materialUsage,
            'project' => $materialUsage->project,
        ]);
    }

    /**
     * Update the specified material usage log record in storage.
     * 
     * ⏱️ CRITICAL: 5-minute edit window enforced by policy
     */
    public function update(Request $request, MaterialUsage $materialUsage)
    {
        // Check authorization (includes 5-minute check)
        $this->authorize('update', $materialUsage);

        // Validate input
        $validated = $request->validate([
            'material_name' => 'required|string|max:100',
            'activity' => 'required|string|max:255',
            'planned_qty' => 'required|numeric|min:0',
            'used_qty' => 'required|numeric|min:0',
        ]);

        try {
            $materialUsage->update($validated);
            return redirect()
                ->route('material-usage.show', $materialUsage)
                ->with('success', 'Material usage log record updated successfully.');
        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->with('error', 'Failed to update material usage log record: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified material usage log record from storage.
     * 
     * ❌ DISABLED: No deletion allowed for audit compliance
     */
    public function destroy(MaterialUsage $materialUsage)
    {
        return response()->json(
            ['message' => 'Material usage log records cannot be deleted to maintain audit compliance.'],
            403
        );
    }
}
