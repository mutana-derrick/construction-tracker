@extends('layouts.app')

@section('page-title', 'Projects')

@section('content')
  <div class="space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
      <div>
        <h1 class="text-2xl font-bold text-gray-900">Projects</h1>
        <p class="text-gray-600 mt-1">Manage all construction projects</p>
      </div>
      @can('create', \App\Models\Project::class)
        <a href="{{ route('projects.create') }}" class="btn-primary">
          ➕ New Project
        </a>
      @endcan
    </div>

    <!-- Projects Table -->
    <div class="card">
      @if($projects->count() > 0)
        <div class="overflow-x-auto">
          <table class="table">
            <thead>
              <tr>
                <th>Project Name</th>
                <th>Location</th>
                <th>Created By</th>
                <th>Logs</th>
                <th>Created</th>
                <th>Actions</th>
              </tr>
            </thead>
            <tbody>
              @foreach($projects as $project)
                <tr>
                  <td class="font-medium text-gray-900">{{ $project->name }}</td>
                  <td class="text-gray-600">{{ $project->location }}</td>
                  <td class="text-gray-600">{{ $project->creator->name }}</td>
                  <td>
                    <span class="inline-block px-2 py-1 bg-blue-50 text-blue-700 rounded text-xs font-medium">
                      {{ $project->equipmentLogs->count() + $project->equipmentCosts->count() + $project->productivityLogs->count() + $project->casualLabourLogs->count() + $project->materialUsage->count() + $project->materialCosts->count() }}
                    </span>
                  </td>
                  <td class="text-gray-600 text-sm">{{ $project->created_at->format('M d, Y') }}</td>
                  <td class="flex gap-2">
                    <a href="{{ route('projects.show', $project) }}" class="text-primary-600 hover:text-primary-700 font-medium">
                      View
                    </a>
                    @can('update', $project)
                      <a href="{{ route('projects.edit', $project) }}" class="text-blue-600 hover:text-blue-700 font-medium">
                        Edit
                      </a>
                    @endcan
                  </td>
                </tr>
              @endforeach
            </tbody>
          </table>
        </div>

        <!-- Pagination -->
        <div class="mt-6">
          {{ $projects->links() }}
        </div>
      @else
        <div class="text-center py-12">
          <p class="text-gray-600 mb-4">No projects yet</p>
          @can('create', \App\Models\Project::class)
            <a href="{{ route('projects.create') }}" class="btn-primary">
              Create First Project
            </a>
          @endcan
        </div>
      @endif
    </div>
  </div>
@endsection
