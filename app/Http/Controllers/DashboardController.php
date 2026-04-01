<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        // Get project ID from query param or session
        $projectId = $request->query('project_id') ?? session('selected_project_id');

        // If no project is selected, redirect to project selection
        if (!$projectId) {
            return redirect()->route('projects.select');
        }

        // Get the selected project or fail
        $project = Project::findOrFail($projectId);

        // Store the selected project in session
        session(['selected_project_id' => $projectId]);

        $recentProjects = Project::with('creator')
            ->latest()
            ->limit(5)
            ->get();

        return view('dashboard', [
            'project' => $project,
            'recentProjects' => $recentProjects,
        ]);
    }
}
