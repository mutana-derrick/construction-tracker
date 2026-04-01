@extends('layouts.app')

@section('page-title', 'Projects')

@section('content')
  <div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col md:flex-row items-start md:items-center justify-between gap-4">
      <div>
        <div class="flex items-center gap-3 mb-2">
          <svg class="w-8 h-8 text-primary-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"></path>
          </svg>
          <h1 class="text-3xl font-bold text-gray-900">Projects</h1>
        </div>
        <p class="text-gray-600 mt-1">Manage all construction projects</p>
      </div>
      @can('create', \App\Models\Project::class)
        <a href="{{ route('projects.create') }}" class="inline-flex items-center gap-2 px-4 py-2.5 bg-primary-400 hover:bg-primary-500 text-gray-900 font-semibold rounded-lg transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-primary-400 focus:ring-offset-2 whitespace-nowrap">
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
          </svg>
          New Project
        </a>
      @endcan
    </div>

    <!-- Projects Table -->
    <div class="bg-white rounded-xl border border-gray-200">
      @if($projects->count() > 0)
        <div class="overflow-x-auto">
          <table class="w-full text-sm">
            <thead>
              <tr class="border-b border-gray-200">
                <th class="px-6 py-4 text-left font-semibold text-gray-900">Project Name</th>
                <th class="px-6 py-4 text-left font-semibold text-gray-900">Location</th>
                <th class="px-6 py-4 text-left font-semibold text-gray-900">Created By</th>
                <th class="px-6 py-4 text-left font-semibold text-gray-900">Logs</th>
                <th class="px-6 py-4 text-left font-semibold text-gray-900">Created</th>
                <th class="px-6 py-4 text-right font-semibold text-gray-900">Actions</th>
              </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
              @foreach($projects as $project)
                <tr class="hover:bg-gray-50 transition-colors">
                  <td class="px-6 py-4 font-semibold text-gray-900">{{ $project->name }}</td>
                  <td class="px-6 py-4 text-gray-600">{{ $project->location }}</td>
                  <td class="px-6 py-4 text-gray-600">
                    <div class="inline-flex items-center gap-2 px-2.5 py-1.5 bg-blue-50 text-blue-700 rounded-md text-xs font-medium border border-blue-200">
                      <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                      </svg>
                      {{ $project->creator->name }}
                    </div>
                  </td>
                  <td class="px-6 py-4">
                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 bg-primary-50 text-primary-700 rounded-md text-xs font-semibold border border-primary-200">
                      <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                      </svg>
                      {{ $project->equipmentLogs->count() + $project->equipmentCosts->count() + $project->productivityLogs->count() + $project->casualLabourLogs->count() + $project->materialUsage->count() + $project->materialCosts->count() }}
                    </span>
                  </td>
                  <td class="px-6 py-4 text-gray-600 text-xs">{{ $project->created_at->format('M d, Y') }}</td>
                  <td class="px-6 py-4 text-right">
                    <div class="flex items-center justify-end gap-3">
                      <a href="{{ route('projects.show', $project) }}" class="inline-flex items-center gap-1 text-primary-400 hover:text-primary-500 font-medium transition-colors text-sm">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                        </svg>
                        View
                      </a>
                      @can('update', $project)
                        <a href="{{ route('projects.edit', $project) }}" class="inline-flex items-center gap-1 text-gray-600 hover:text-gray-900 font-medium transition-colors text-sm">
                          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                          </svg>
                          Edit
                        </a>
                      @endcan
                    </div>
                  </td>
                </tr>
              @endforeach
            </tbody>
          </table>
        </div>

        <!-- Pagination -->
        <div class="px-6 py-4 border-t border-gray-200">
          {{ $projects->links() }}
        </div>
      @else
        <div class="text-center py-12 px-6">
          <svg class="w-12 h-12 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"></path>
          </svg>
          <p class="text-gray-600 mb-4 font-medium">No projects yet</p>
          @can('create', \App\Models\Project::class)
            <a href="{{ route('projects.create') }}" class="inline-flex items-center gap-2 px-4 py-2.5 bg-primary-50 text-primary-600 hover:bg-primary-100 rounded-lg font-medium transition-colors">
              <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
              </svg>
              Create your first project
            </a>
          @endcan
        </div>
      @endif
    </div>
  </div>
@endsection
