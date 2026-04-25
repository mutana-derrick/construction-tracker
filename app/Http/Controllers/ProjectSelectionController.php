<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Http\Request;

class ProjectSelectionController extends Controller
{
    /**
     * Display the project selection page after login.
     */
    public function index(Request $request)
    {
        $projects = Project::query()
            ->withCount([
                'equipmentLogs',
                'equipmentCosts',
            ])
            ->withSum('equipmentCosts', 'total_cost')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('project-selection', [
            'projects' => $projects,
            'projectCount' => $projects->count(),
        ]);
    }

    /**
     * Set the selected project and redirect to dashboard.
     */
    public function select(Request $request, Project $project)
    {
        // Store selected project in session
        session(['selected_project_id' => $project->id]);

        return redirect()->route('dashboard', ['project_id' => $project->id]);
    }

    /**
     * Clear selected project and go back to selection.
     */
    public function clearSelection(Request $request)
    {
        session()->forget('selected_project_id');

        return redirect()->route('projects.select');
    }
}