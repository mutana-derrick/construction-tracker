<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use App\Models\EquipmentLog;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EquipmentLogController extends Controller
{
    /**
     * Display a listing of equipment logs for a project.
     */
    public function index(Request $request)
    {
        $projectId = $request->query('project_id');
        $project = Project::findOrFail($projectId);
        $this->authorize('view', $project);

        // Get all equipment logs for this project
        $logs = EquipmentLog::where('project_id', $projectId)
            ->with('user', 'project')
            ->orderBy('date', 'desc')
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('equipment-logs.index', [
            'project' => $project,
            'logs' => $logs,
        ]);
    }

    /**
     * Show the form for creating a new equipment log.
     */
    public function create(Request $request)
    {
        $projectId = $request->query('project_id');
        $project = Project::findOrFail($projectId);
        $this->authorize('view', $project);

        // Check authorization
        $this->authorize('create', EquipmentLog::class);

        return view('equipment-logs.create', [
            'project' => $project,
            'activities' => Activity::orderBy('name')->get(),
        ]);
    }

    /**
     * Store a newly created equipment log in storage.
     */
    public function store(Request $request)
    {
        // Check authorization
        $this->authorize('create', EquipmentLog::class);

        // Validate input
        $validated = $request->validate([
            'project_id' => 'required|exists:projects,id',
            'equipment_type' => 'required|string|max:100',
            'equipment_id' => 'required|string|max:100',
            'activity_id' => 'required|exists:activities,id',
            'planned_output' => 'required|numeric|min:0',
            'actual_output' => 'required|numeric|min:0',
            'working_hours' => 'required|numeric|min:0',
            'available_hours' => 'required|numeric|min:0',
            'fuel_used' => 'nullable|numeric|min:0',
            'comment' => 'nullable|string|max:500',
        ]);

        $project = Project::findOrFail($validated['project_id']);

        $this->authorize('view', $project);

        // Auto-assign current user and today's date
        $validated['user_id'] = Auth::id();
        $validated['date'] = now()->toDateString();

        try {
            EquipmentLog::create($validated);
            return redirect()
                ->route('equipment-logs.index', ['project_id' => $validated['project_id']])
                ->with('success', 'Equipment log created successfully.');
        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->with('error', 'Failed to create equipment log: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified equipment log.
     */
    public function show(EquipmentLog $equipmentLog)
    {
        // Check authorization
        $this->authorize('view', $equipmentLog);

        $equipmentLog->load('user', 'project');

        return view('equipment-logs.show', [
            'log' => $equipmentLog,
            'project' => $equipmentLog->project,
            'canEdit' => Auth::check() && Auth::user()->can('update', $equipmentLog),
        ]);
    }

    /**
     * Show the form for editing the specified equipment log.
     */
    public function edit(EquipmentLog $equipmentLog)
    {
        // Check authorization (includes 5-minute check)
        $this->authorize('update', $equipmentLog);

        $equipmentLog->load('project');

        return view('equipment-logs.edit', [
            'log' => $equipmentLog,
            'project' => $equipmentLog->project,
            'activities' => Activity::orderBy('name')->get(),
        ]);
    }

    /**
     * Update the specified equipment log in storage.
     * 
     * ⏱️ CRITICAL: 5-minute edit window enforced by policy
     */
    public function update(Request $request, EquipmentLog $equipmentLog)
    {
        // Check authorization (includes 5-minute check)
        $this->authorize('update', $equipmentLog);

        // Validate input
        $validated = $request->validate([
            'equipment_type' => 'required|string|max:100',
            'equipment_id' => 'required|string|max:100',
            'activity_id' => 'required|exists:activities,id',
            'planned_output' => 'required|numeric|min:0',
            'actual_output' => 'required|numeric|min:0',
            'working_hours' => 'required|numeric|min:0',
            'available_hours' => 'required|numeric|min:0',
            'fuel_used' => 'nullable|numeric|min:0',
            'comment' => 'nullable|string|max:500',
        ]);

        try {
            $equipmentLog->update($validated);
            return redirect()
                ->route('equipment-logs.show', $equipmentLog)
                ->with('success', 'Equipment log updated successfully.');
        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->with('error', 'Failed to update equipment log: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified equipment log from storage.
     * 
     * ❌ DISABLED: No deletion allowed for audit compliance
     */
    public function destroy(EquipmentLog $equipmentLog)
    {
        return redirect()->back()->with(
            'error',
            'Delete operations are not allowed for audit purposes.',
            403
        );
    }
}