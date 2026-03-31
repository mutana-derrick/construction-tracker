<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $recentProjects = Project::with('creator')
            ->latest()
            ->limit(5)
            ->get();

        return view('dashboard', [
            'recentProjects' => $recentProjects,
        ]);
    }
}
