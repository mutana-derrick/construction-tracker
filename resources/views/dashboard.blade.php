@extends('layouts.app')

@section('page-title', 'Dashboard - ' . $project->name)

@section('content')
  <div class="space-y-8">
    <!-- Welcome Header with Project Info -->
    <div class="flex flex-col md:flex-row items-start md:items-center justify-between">
      <div>
        <h1 class="text-4xl font-bold text-gray-900 mb-2">{{ $project->name }}</h1>
        <p class="text-gray-600 text-sm flex items-center gap-2">
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
          </svg>
          <span>{{ now()->format('l, F j, Y') }}</span>
        </p>
        @if ($project->location)
          <p class="text-gray-600 text-sm flex items-center gap-2 mt-1">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
            </svg>
            <span>{{ $project->location }}</span>
          </p>
        @endif
      </div>
      <a
        href="{{ route('equipment-logs.create', ['project_id' => $project->id]) }}"
        class="mt-4 md:mt-0 inline-flex items-center gap-2 px-4 py-2.5 bg-yellow-400 hover:bg-yellow-500 text-gray-900 font-semibold rounded-lg transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-yellow-400 focus:ring-offset-2 shadow-sm hover:shadow-md"
      >
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
        </svg>
        New Log Entry
      </a>
    </div>

    <!-- Key Metrics Grid - Project Specific -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
      <!-- Equipment Logs Card -->
      <div class="bg-white rounded-xl border border-gray-200 p-6 shadow-sm hover:shadow-md transition-shadow">
        <div class="flex items-start justify-between">
          <div class="flex-1">
            <p class="text-sm font-semibold text-gray-600 uppercase tracking-wide">Equipment Logs</p>
            <div class="mt-4 flex items-baseline gap-2">
              <p class="text-3xl font-bold text-gray-900">
                {{ $project->equipmentLogs->count() }}
              </p>
              <p class="text-xs text-gray-500">total</p>
            </div>
          </div>
          <div class="inline-flex items-center justify-center w-12 h-12 bg-yellow-50 rounded-lg">
            <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
            </svg>
          </div>
        </div>
        <div class="mt-4 pt-4 border-t border-gray-100">
          <a href="{{ route('equipment-logs.index', ['project_id' => $project->id]) }}" class="text-sm font-medium text-yellow-600 hover:text-yellow-700 transition-colors flex items-center gap-1">
            View logs
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
            </svg>
          </a>
        </div>
      </div>

      <!-- Today's Logs Card -->
      <div class="bg-white rounded-xl border border-gray-200 p-6 shadow-sm hover:shadow-md transition-shadow">
        <div class="flex items-start justify-between">
          <div class="flex-1">
            <p class="text-sm font-semibold text-gray-600 uppercase tracking-wide">Today's Entries</p>
            <div class="mt-4 flex items-baseline gap-2">
              <p class="text-3xl font-bold text-gray-900">
                {{ $project->equipmentLogs->filter(fn($log) => $log->date->isToday())->count() +
                   $project->equipmentCosts->filter(fn($cost) => $cost->date->isToday())->count() }}
              </p>
              <p class="text-xs text-gray-500">entries</p>
            </div>
          </div>
          <div class="inline-flex items-center justify-center w-12 h-12 bg-green-50 rounded-lg">
            <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
            </svg>
          </div>
        </div>
        <div class="mt-4 pt-4 border-t border-gray-100">
          <p class="text-xs text-gray-500">In this project</p>
        </div>
      </div>

      <!-- Equipment Cost Card -->
      <div class="bg-white rounded-xl border border-gray-200 p-6 shadow-sm hover:shadow-md transition-shadow">
        <div class="flex items-start justify-between">
          <div class="flex-1">
            <p class="text-sm font-semibold text-gray-600 uppercase tracking-wide">Equipment Cost</p>
            <div class="mt-4 flex items-baseline gap-2">
              <p class="text-3xl font-bold text-gray-900">
                Rwf {{ number_format($project->equipment_costs_sum_total_cost ?? 0, 0) }}
              </p>
            </div>
          </div>
          <div class="inline-flex items-center justify-center w-12 h-12 bg-yellow-50 rounded-lg">
            <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
          </div>
        </div>
        <div class="mt-4 pt-4 border-t border-gray-100">
          <a href="{{ route('equipment-costs.index', ['project_id' => $project->id]) }}" class="text-sm font-medium text-yellow-600 hover:text-yellow-700 transition-colors flex items-center gap-1">
            View records
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
            </svg>
          </a>
        </div>
      </div>

      <!-- Productivity Average Card -->
      <div class="bg-white rounded-xl border border-gray-200 p-6 shadow-sm hover:shadow-md transition-shadow">
        <div class="flex items-start justify-between">
          <div class="flex-1">
            <p class="text-sm font-semibold text-gray-600 uppercase tracking-wide">Avg Productivity</p>
            <div class="mt-4 flex items-baseline gap-2">
              <p class="text-3xl font-bold text-gray-900">
                {{ $project->equipmentLogs->count() > 0 
                  ? number_format($project->equipmentLogs->average('actual_output'), 1) 
                  : 'N/A' }}
              </p>
              <p class="text-xs text-gray-500">units/day</p>
            </div>
          </div>
          <div class="inline-flex items-center justify-center w-12 h-12 bg-yellow-50 rounded-lg">
            <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
            </svg>
          </div>
        </div>
        <div class="mt-4 pt-4 border-t border-gray-100">
          <a href="{{ route('equipment-logs.index', ['project_id' => $project->id]) }}" class="text-sm font-medium text-yellow-600 hover:text-yellow-700 transition-colors flex items-center gap-1">
            View details
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
            </svg>
          </a>
        </div>
      </div>
    </div>

    <!-- Recent Equipment Logs Table -->
    <div class="bg-white rounded-xl border border-gray-200 p-6 shadow-sm">
      <div class="mb-8">
        <div class="flex items-center justify-between">
          <div>
            <h2 class="text-xl font-bold text-gray-900">Recent Equipment Logs</h2>
            <p class="text-sm text-gray-600 mt-1">Latest log entries for this project</p>
          </div>
          <a href="{{ route('equipment-logs.index', ['project_id' => $project->id]) }}" class="text-sm font-medium text-yellow-600 hover:text-yellow-700 transition-colors flex items-center gap-1">
            View all logs
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
            </svg>
          </a>
        </div>
      </div>

      @if($recentProjects->count() > 0)
        <div class="overflow-x-auto">
          <table class="w-full text-sm">
            <thead>
              <tr class="border-b border-gray-200">
                <th class="px-4 py-3 text-left font-semibold text-gray-900">Project Name</th>
                <th class="px-4 py-3 text-left font-semibold text-gray-900">Location</th>
                <th class="px-4 py-3 text-left font-semibold text-gray-900">Created By</th>
                <th class="px-4 py-3 text-left font-semibold text-gray-900">Date Created</th>
                <th class="px-4 py-3 text-right font-semibold text-gray-900">Action</th>
              </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
              @foreach($recentProjects as $project)
                <tr class="hover:bg-gray-50 transition-colors">
                  <td class="px-4 py-4 font-medium text-gray-900">{{ $project->name }}</td>
                  <td class="px-4 py-4 text-gray-600">{{ $project->location }}</td>
                  <td class="px-4 py-4 text-gray-600">
                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 bg-blue-50 text-blue-700 rounded-md text-xs font-medium">
                      <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                      </svg>
                      {{ $project->creator->name }}
                    </span>
                  </td>
                  <td class="px-4 py-4 text-gray-600 text-xs">{{ $project->created_at->format('M d, Y') }}</td>
                  <td class="px-4 py-4 text-right">
                    <a
                      href="{{ route('projects.show', $project) }}"
                      class="inline-flex items-center gap-1 text-yellow-600 hover:text-yellow-700 font-medium transition-colors text-sm"
                    >
                      View
                      <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                      </svg>
                    </a>
                  </td>
                </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      @else
        <div class="py-12 text-center">
          <svg class="w-12 h-12 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
          </svg>
          <p class="text-gray-600 mb-4">No projects created yet</p>
          @can('create', \App\Models\Project::class)
            <a href="{{ route('projects.create') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-yellow-50 text-yellow-600 hover:bg-yellow-100 rounded-lg font-medium transition-colors text-sm shadow-sm">
              <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
              </svg>
              Create your first project
            </a>
          @endcan
        </div>
      @endif
    </div>

    <!-- Quick Actions Grid -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
      <!-- Quick Actions Card -->
      <div class="bg-white rounded-xl border border-gray-200 p-6 shadow-sm">
        <h3 class="text-lg font-bold text-gray-900 mb-6 flex items-center gap-2">
          <svg class="w-5 h-5 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
          </svg>
          Quick Actions
        </h3>
        <div class="space-y-3">
          @can('create', \App\Models\EquipmentLog::class)
            <a href="{{ route('equipment-logs.create') }}" class="w-full px-4 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-900 font-medium rounded-lg transition-colors text-sm text-center inline-flex items-center justify-center gap-2">
              <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
              </svg>
              Log Equipment
            </a>
          @endcan
          @can('create', \App\Models\ProductivityLog::class)
            <a href="{{ route('productivity-logs.create') }}" class="w-full px-4 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-900 font-medium rounded-lg transition-colors text-sm text-center inline-flex items-center justify-center gap-2">
              <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
              </svg>
              Log Productivity
            </a>
          @endcan
          @can('create', \App\Models\MaterialUsage::class)
            <a href="{{ route('material-usage.create') }}" class="w-full px-4 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-900 font-medium rounded-lg transition-colors text-sm text-center inline-flex items-center justify-center gap-2">
              <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10l8-4"></path>
              </svg>
              Log Material
            </a>
          @endcan
        </div>
      </div>

      <!-- Reports Card -->
      <div class="bg-white rounded-xl border border-gray-200 p-6 shadow-sm">
        <h3 class="text-lg font-bold text-gray-900 mb-6 flex items-center gap-2">
          <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
          </svg>
          Reports
        </h3>
        <div class="space-y-3">
          <a href="{{ route('reports.daily') }}" class="w-full px-4 py-2.5 bg-blue-50 hover:bg-blue-100 text-blue-700 font-medium rounded-lg transition-colors text-sm text-center inline-flex items-center justify-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
            </svg>
            Daily Report
          </a>
          <a href="{{ route('reports.monthly') }}" class="w-full px-4 py-2.5 bg-purple-50 hover:bg-purple-100 text-purple-700 font-medium rounded-lg transition-colors text-sm text-center inline-flex items-center justify-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
            </svg>
            Monthly Report
          </a>
          <a href="{{ route('projects.index') }}" class="w-full px-4 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-900 font-medium rounded-lg transition-colors text-sm text-center inline-flex items-center justify-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
            </svg>
            View All Projects
          </a>
        </div>
      </div>

      <!-- User Role Card -->
      <div class="bg-white rounded-xl border border-gray-200 p-6 shadow-sm">
        <h3 class="text-lg font-bold text-gray-900 mb-6 flex items-center gap-2">
          <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
          </svg>
          Your Account
        </h3>
        <div class="space-y-4">
          <div>
            <p class="text-xs font-semibold text-gray-600 uppercase tracking-wide">User</p>
            <p class="text-gray-900 font-medium mt-2">{{ auth()->user()->name }}</p>
          </div>
          <div>
            <p class="text-xs font-semibold text-gray-600 uppercase tracking-wide mb-2">Role</p>
            @if(auth()->user()->role === 'recorder')
              <span class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-yellow-50 text-yellow-700 rounded-lg text-xs font-semibold border border-yellow-200 shadow-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11a7 7 0 01-7 7m0 0a7 7 0 01-7-7m7 7v4m0 0H8m4 0h4"></path>
                </svg>
                Recorder
              </span>
              <p class="text-xs text-gray-600 mt-2">Can create & edit entries (5-min window)</p>
            @else
              <span class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-blue-50 text-blue-700 rounded-lg text-xs font-semibold border border-blue-200">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                </svg>
                Viewer
              </span>
              <p class="text-xs text-gray-600 mt-2">View-only access to all entries</p>
            @endif
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection
