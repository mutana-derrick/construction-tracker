<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use App\Models\EquipmentCost;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EquipmentCostController extends Controller
{
    /**
     * Display a listing of equipment costs for a project.
     */
    public function index(Request $request)
    {
        $projectId = $request->query('project_id');
        $project = Project::findOrFail($projectId);
        $this->authorize('view', $project);

        // Get all equipment costs for this project
        $costs = EquipmentCost::where('project_id', $projectId)
            ->with('user', 'project')
            ->orderBy('date', 'desc')
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('equipment-costs.index', [
            'project' => $project,
            'costs' => $costs,
        ]);
    }

    /**
     * Show the form for creating a new equipment cost record.
     */
    public function create(Request $request)
    {
        $projectId = $request->query('project_id');
        $project = Project::findOrFail($projectId);
        $this->authorize('view', $project);

        // Check authorization
        $this->authorize('create', EquipmentCost::class);

        return view('equipment-costs.create', [
            'project' => $project,
            'activities' => Activity::orderBy('name')->get(),
        ]);
    }

    /**
     * Store a newly created equipment cost record in storage.
     */
    public function store(Request $request)
    {
        // Check authorization
        $this->authorize('create', EquipmentCost::class);

        // Validate input
        $validated = $request->validate([
            'project_id' => 'required|exists:projects,id',
            'activity_id' => 'required|exists:activities,id',
            'equipment_type' => 'required|string|max:100',
            'units_done' => 'required|numeric|min:0',
            'cost_per_unit' => 'required|numeric|min:0',
            'comment' => 'nullable|string|max:500',
        ]);
        
        $project = Project::findOrFail($validated['project_id']);

        $this->authorize('view', $project);

        // Auto-assign current user and today's date
        $validated['user_id'] = Auth::id();
        $validated['date'] = now()->toDateString();

        // Calculate total cost
        $validated['total_cost'] = $validated['units_done'] * $validated['cost_per_unit'];

        try {
            EquipmentCost::create($validated);
            return redirect()
                ->route('equipment-costs.index', ['project_id' => $validated['project_id']])
                ->with('success', 'Equipment cost record created successfully.');
        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->with('error', 'Failed to create equipment cost record: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified equipment cost record.
     */
    public function show(EquipmentCost $equipmentCost)
    {
        // Check authorization
        $this->authorize('view', $equipmentCost);

        $equipmentCost->load('user', 'project');

        return view('equipment-costs.show', [
            'cost' => $equipmentCost,
            'project' => $equipmentCost->project,
            'canEdit' => Auth::check() && Auth::user()->can('update', $equipmentCost),
        ]);
    }

    /**
     * Show the form for editing the specified equipment cost record.
     */
    public function edit(EquipmentCost $equipmentCost)
    {
        // Check authorization (includes 5-minute check)
        $this->authorize('update', $equipmentCost);

        $equipmentCost->load('project');

        return view('equipment-costs.edit', [
            'cost' => $equipmentCost,
            'project' => $equipmentCost->project,
            'activities' => Activity::orderBy('name')->get(),
        ]);
    }

    /**
     * Update the specified equipment cost record in storage.
     * 
     * ⏱️ CRITICAL: 5-minute edit window enforced by policy
     */
    public function update(Request $request, EquipmentCost $equipmentCost)
    {
        // Check authorization (includes 5-minute check)
        $this->authorize('update', $equipmentCost);

        // Validate input
        $validated = $request->validate([
            'activity_id' => 'required|exists:activities,id',
            'equipment_type' => 'required|string|max:100',
            'units_done' => 'required|numeric|min:0',
            'cost_per_unit' => 'required|numeric|min:0',
            'comment' => 'nullable|string|max:500',
        ]);

        // Calculate total cost
        $validated['total_cost'] = $validated['units_done'] * $validated['cost_per_unit'];

        try {
            $equipmentCost->update($validated);
            return redirect()
                ->route('equipment-costs.show', $equipmentCost)
                ->with('success', 'Equipment cost record updated successfully.');
        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->with('error', 'Failed to update equipment cost record: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified equipment cost record from storage.
     * 
     * ❌ DISABLED: No deletion allowed for audit compliance
     */
    public function destroy(EquipmentCost $equipmentCost)
    {
        return response()->json(
            ['message' => 'Equipment cost records cannot be deleted to maintain audit compliance.'],
            403
        );
    }
}