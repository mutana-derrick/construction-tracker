<?php

namespace App\Http\Controllers;

use App\Models\MaterialUsage;
use App\Models\MaterialCost;
use App\Models\Project;
use Illuminate\Http\Request;

class MaterialManagementController extends Controller
{
    public function index(Request $request)
    {
        $projectId = $request->query('project_id');

        if (!$projectId) {
            return redirect()
                ->route('projects.select')
                ->with('error', 'Please select a project first.');
        }

        $project = Project::findOrFail($projectId);

        $this->authorize('view', $project);

        $usages = MaterialUsage::where('project_id', $project->id)
            ->with(['user', 'project'])
            ->orderBy('date', 'desc')
            ->paginate(10, ['*'], 'usage_page');

        $costs = MaterialCost::where('project_id', $project->id)
            ->with(['user', 'project'])
            ->orderBy('date', 'desc')
            ->paginate(10, ['*'], 'cost_page');

        $totalMaterialCost = MaterialCost::where('project_id', $project->id)->sum('total');

        return view('material.index', [
            'project' => $project,
            'usages' => $usages,
            'costs' => $costs,
            'totalMaterialCost' => $totalMaterialCost,
        ]);
    }
}