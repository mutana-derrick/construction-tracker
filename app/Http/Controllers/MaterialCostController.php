<?php

namespace App\Http\Controllers;

use App\Models\MaterialCost;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MaterialCostController extends Controller
{
    /**
     * Display a listing of material cost logs for a project.
     */
    public function index(Request $request)
    {
        $projectId = $request->query('project_id');
        $project = Project::findOrFail($projectId);

        // Get all material cost logs for this project
        $logs = MaterialCost::where('project_id', $projectId)
            ->with('user', 'project')
            ->orderBy('date', 'desc')
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('material-costs.index', [
            'project' => $project,
            'logs' => $logs,
        ]);
    }

    /**
     * Show the form for creating a new material cost log record.
     */
    public function create(Request $request)
    {
        $projectId = $request->query('project_id');
        $project = Project::findOrFail($projectId);

        // Check authorization
        $this->authorize('create', MaterialCost::class);

        return view('material-costs.create', [
            'project' => $project,
        ]);
    }

    /**
     * Store a newly created material cost log record in storage.
     */
    public function store(Request $request)
    {
        // Check authorization
        $this->authorize('create', MaterialCost::class);

        // Validate input
        $validated = $request->validate([
            'project_id' => 'required|exists:projects,id',
            'material_name' => 'required|string|max:100',
            'used_qty' => 'required|numeric|min:0',
            'cost_per_item' => 'required|numeric|min:0',
        ]);

        // Auto-assign current user and today's date
        $validated['user_id'] = Auth::id();
        $validated['date'] = now()->toDateString();

        // Calculate total cost
        $validated['total'] = $validated['used_qty'] * $validated['cost_per_item'];

        try {
            MaterialCost::create($validated);
            return redirect()
                ->route('material-costs.index', ['project_id' => $validated['project_id']])
                ->with('success', 'Material cost log record created successfully.');
        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->with('error', 'Failed to create material cost log record: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified material cost log record.
     */
    public function show(MaterialCost $materialCost)
    {
        // Check authorization
        $this->authorize('view', $materialCost);

        $materialCost->load('user', 'project');

        return view('material-costs.show', [
            'log' => $materialCost,
            'project' => $materialCost->project,
            'canEdit' => Auth::check() && Auth::user()->can('update', $materialCost),
        ]);
    }

    /**
     * Show the form for editing the specified material cost log record.
     */
    public function edit(MaterialCost $materialCost)
    {
        // Check authorization (includes 5-minute check)
        $this->authorize('update', $materialCost);

        $materialCost->load('project');

        return view('material-costs.edit', [
            'log' => $materialCost,
            'project' => $materialCost->project,
        ]);
    }

    /**
     * Update the specified material cost log record in storage.
     * 
     * ⏱️ CRITICAL: 5-minute edit window enforced by policy
     */
    public function update(Request $request, MaterialCost $materialCost)
    {
        // Check authorization (includes 5-minute check)
        $this->authorize('update', $materialCost);

        // Validate input
        $validated = $request->validate([
            'material_name' => 'required|string|max:100',
            'used_qty' => 'required|numeric|min:0',
            'cost_per_item' => 'required|numeric|min:0',
        ]);

        // Calculate total cost
        $validated['total'] = $validated['used_qty'] * $validated['cost_per_item'];

        try {
            $materialCost->update($validated);
            return redirect()
                ->route('material-costs.show', $materialCost)
                ->with('success', 'Material cost log record updated successfully.');
        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->with('error', 'Failed to update material cost log record: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified material cost log record from storage.
     * 
     * ❌ DISABLED: No deletion allowed for audit compliance
     */
    public function destroy(MaterialCost $materialCost)
    {
        return response()->json(
            ['message' => 'Material cost log records cannot be deleted to maintain audit compliance.'],
            403
        );
    }
}
