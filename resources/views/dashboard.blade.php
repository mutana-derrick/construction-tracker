@extends('layouts.app')

@section('page-title', 'Dashboard')

@section('content')
  <div class="space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
      <div>
        <h1 class="text-3xl font-bold text-gray-900">Welcome back!</h1>
        <p class="text-gray-600 mt-1">{{ now()->format('l, F j, Y') }}</p>
      </div>
      @can('create', \App\Models\Project::class)
        <a
          href="{{ route('projects.create') }}"
          class="btn-primary"
        >
          ➕ New Project
        </a>
      @endcan
    </div>

    <!-- Quick Stats -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
      <div class="card">
        <div class="flex items-center justify-between">
          <div>
            <p class="text-gray-600 text-sm font-medium">Active Projects</p>
            <p class="text-3xl font-bold text-gray-900 mt-2">
              {{ \App\Models\Project::count() }}
            </p>
          </div>
          <span class="text-4xl">📁</span>
        </div>
      </div>

      <div class="card">
        <div class="flex items-center justify-between">
          <div>
            <p class="text-gray-600 text-sm font-medium">Today's Logs</p>
            <p class="text-3xl font-bold text-gray-900 mt-2">
              {{ \App\Models\EquipmentLog::whereDate('date', today())->count() +
                 \App\Models\EquipmentCost::whereDate('date', today())->count() +
                 \App\Models\ProductivityLog::whereDate('date', today())->count() +
                 \App\Models\CasualLabourLog::whereDate('date', today())->count() +
                 \App\Models\MaterialUsage::whereDate('date', today())->count() +
                 \App\Models\MaterialCost::whereDate('date', today())->count() }}
            </p>
          </div>
          <span class="text-4xl">📊</span>
        </div>
      </div>

      <div class="card">
        <div class="flex items-center justify-between">
          <div>
            <p class="text-gray-600 text-sm font-medium">Total Equipment Cost</p>
            <p class="text-3xl font-bold text-gray-900 mt-2">
              ${{ number_format(\App\Models\EquipmentCost::sum('total_cost'), 0) }}
            </p>
          </div>
          <span class="text-4xl">💰</span>
        </div>
      </div>

      <div class="card">
        <div class="flex items-center justify-between">
          <div>
            <p class="text-gray-600 text-sm font-medium">Labour Cost (Today)</p>
            <p class="text-3xl font-bold text-gray-900 mt-2">
              ${{ number_format(\App\Models\CasualLabourLog::whereDate('date', today())->sum('total_cost'), 0) }}
            </p>
          </div>
          <span class="text-4xl">👥</span>
        </div>
      </div>
    </div>

    <!-- Recent Projects -->
    <div class="card">
      <div class="mb-6">
        <h2 class="text-lg font-semibold text-gray-900">Recent Projects</h2>
      </div>

      @if($recentProjects->count() > 0)
        <div class="overflow-x-auto">
          <table class="table">
            <thead>
              <tr>
                <th>Project Name</th>
                <th>Location</th>
                <th>Created By</th>
                <th>Created</th>
                <th>Actions</th>
              </tr>
            </thead>
            <tbody>
              @foreach($recentProjects as $project)
                <tr>
                  <td class="font-medium text-gray-900">{{ $project->name }}</td>
                  <td class="text-gray-600">{{ $project->location }}</td>
                  <td class="text-gray-600">{{ $project->creator->name }}</td>
                  <td class="text-gray-600">{{ $project->created_at->format('M d, Y') }}</td>
                  <td>
                    <a
                      href="{{ route('projects.show', $project) }}"
                      class="text-primary-600 hover:text-primary-700 font-medium"
                    >
                      View →
                    </a>
                  </td>
                </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      @else
        <p class="text-gray-600 text-center py-8">
          No projects yet.
          @can('create', \App\Models\Project::class)
            <a href="{{ route('projects.create') }}" class="text-primary-600 hover:text-primary-700">Create one now</a>
          @endcan
        </p>
      @endif
    </div>

    <!-- Quick Actions -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
      <div class="card">
        <h3 class="font-semibold text-gray-900 mb-4">Quick Actions</h3>
        <div class="space-y-3">
          @can('create', \App\Models\EquipmentLog::class)
            <a href="{{ route('equipment-logs.create') }}" class="btn-secondary block text-center">
              Log Equipment
            </a>
          @endcan
          @can('create', \App\Models\ProductivityLog::class)
            <a href="{{ route('productivity-logs.create') }}" class="btn-secondary block text-center">
              Log Productivity
            </a>
          @endcan
          @can('create', \App\Models\MaterialUsage::class)
            <a href="{{ route('material-usage.create') }}" class="btn-secondary block text-center">
              Log Material Usage
            </a>
          @endcan
        </div>
      </div>

      <div class="card">
        <h3 class="font-semibold text-gray-900 mb-4">Reports</h3>
        <div class="space-y-3">
          <a href="{{ route('reports.daily') }}" class="btn-secondary block text-center">
            Daily Report
          </a>
          <a href="{{ route('reports.monthly') }}" class="btn-secondary block text-center">
            Monthly Report
          </a>
        </div>
      </div>

      <div class="card">
        <h3 class="font-semibold text-gray-900 mb-4">User Role</h3>
        <div class="space-y-3">
          <p class="text-gray-700">
            <span class="font-medium">{{ auth()->user()->name }}</span><br>
            @if(auth()->user()->role === 'recorder')
              <span class="inline-block mt-2 px-3 py-1 bg-primary-50 text-primary-700 rounded-full text-sm font-medium">
                🎙️ Recorder (Can Create & Edit)
              </span>
            @else
              <span class="inline-block mt-2 px-3 py-1 bg-blue-50 text-blue-700 rounded-full text-sm font-medium">
                👁️ Viewer (View Only)
              </span>
            @endif
          </p>
        </div>
      </div>
    </div>
  </div>
@endsection
