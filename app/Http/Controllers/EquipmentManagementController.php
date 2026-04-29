<?php

namespace App\Http\Controllers;

use App\Models\EquipmentCost;
use App\Models\EquipmentLog;
use App\Models\Project;
use Illuminate\Http\Request;

class EquipmentManagementController extends Controller
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

        $logs = EquipmentLog::where('project_id', $project->id)
            ->with(['user', 'project'])
            ->orderBy('date', 'desc')
            ->orderBy('created_at', 'desc')
            ->paginate(10, ['*'], 'logs_page');

        $costs = EquipmentCost::where('project_id', $project->id)
            ->with(['user', 'project'])
            ->orderBy('date', 'desc')
            ->orderBy('created_at', 'desc')
            ->paginate(10, ['*'], 'costs_page');

        $totalEquipmentCost = EquipmentCost::where('project_id', $project->id)->sum('total_cost');

        return view('equipment.index', [
            'project' => $project,
            'logs' => $logs,
            'costs' => $costs,
            'totalEquipmentCost' => $totalEquipmentCost,
        ]);
    }
}