@extends('layouts.app')

@section('page-title', $project->name)

@section('content')
  <div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col md:flex-row items-start md:items-center justify-between gap-4">
      <div>
        {{-- <a href="{{ route('projects.index') }}" class="inline-flex items-center gap-1 text-gray-600 hover:text-gray-900 font-medium transition-colors mb-3">
          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
          </svg>
          Back to Projects
        </a> --}}
        <div class="flex items-center gap-3 mb-2">
          <svg class="w-8 h-8 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"></path>
          </svg>
          <h1 class="text-3xl font-bold text-gray-900">{{ $project->name }}</h1>
        </div>
        <div class="flex items-center gap-2 text-gray-600 mt-2">
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
          </svg>
          {{ $project->location }}
        </div>
      </div>
      {{-- @can('update', $project)
        <a href="{{ route('projects.edit', $project) }}" class="inline-flex items-center gap-2 px-4 py-2.5 bg-yellow-400 hover:bg-yellow-500 text-gray-900 font-semibold rounded-lg transition-all duration-200 shadow-sm hover:shadow-md">
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
          </svg>
          Edit Project
        </a>
      @endcan --}}
    </div>

    <!-- Project Details -->
    <div class="bg-white rounded-xl border border-gray-200 p-6 shadow-sm hover:shadow-md transition-shadow">
      <h2 class="text-lg font-bold text-gray-900 mb-6 flex items-center gap-2">
        <svg class="w-5 h-5 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
        </svg>
        Project Details
      </h2>
      <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div>
          <p class="text-xs font-semibold text-gray-600 uppercase tracking-wide">Created By</p>
          <div class="flex items-center gap-2 mt-3">
            <div class="w-8 h-8 bg-yellow-50 rounded-full flex items-center justify-center">
              <svg class="w-4 h-4 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
              </svg>
            </div>
            <p class="text-gray-900 font-semibold">{{ $project->creator->name }}</p>
          </div>
        </div>
        <div>
          <p class="text-xs font-semibold text-gray-600 uppercase tracking-wide">Created Date</p>
          <div class="flex items-center gap-2 mt-3">
            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
            </svg>
            <p class="text-gray-900 font-semibold">{{ $project->created_at->format('M d, Y') }}</p>
          </div>
        </div>
        <div class="md:col-span-2">
          <p class="text-xs font-semibold text-gray-600 uppercase tracking-wide">Description</p>
          <p class="text-gray-900 mt-3 leading-relaxed">{{ $project->description ?? 'No description provided' }}</p>
        </div>
      </div>
    </div>

    <!-- Project Stats -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
      <div class="bg-white rounded-xl border border-gray-200 p-6 hover:shadow-md transition-shadow shadow-sm">
        <div class="flex items-center justify-between">
          <div>
            <p class="text-xs font-semibold text-gray-600 uppercase tracking-wide">Equipment Logs</p>
            <p class="text-3xl font-bold text-gray-900 mt-3">{{ $project->equipmentLogs->count() }}</p>
          </div>
          <div class="w-12 h-12 bg-yellow-50 rounded-lg flex items-center justify-center border border-yellow-200 shadow-sm">
            <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
          </div>
        </div>
      </div>
      <div class="bg-white rounded-xl border border-gray-200 p-6 hover:shadow-md transition-shadow shadow-sm">
        <div class="flex items-center justify-between">
          <div>
            <p class="text-xs font-semibold text-gray-600 uppercase tracking-wide">Equipment Cost</p>
            <p class="text-3xl font-bold text-gray-900 mt-3">Rwf {{ number_format($project->equipmentCosts->sum('total_cost'), 0) }}</p>
          </div>
          <div class="w-12 h-12 bg-yellow-50 rounded-lg flex items-center justify-center border border-yellow-200 shadow-sm">
            <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
          </div>
        </div>
      </div>
      <div class="bg-white rounded-xl border border-gray-200 p-6 hover:shadow-md transition-shadow shadow-sm">
        <div class="flex items-center justify-between">
          <div>
            <p class="text-xs font-semibold text-gray-600 uppercase tracking-wide">Labour Cost</p>
            <p class="text-3xl font-bold text-gray-900 mt-3">Rwf {{ number_format($project->casualLabourLogs->sum('total_cost'), 0) }}</p>
          </div>
          <div class="w-12 h-12 bg-yellow-50 rounded-lg flex items-center justify-center border border-yellow-200 shadow-sm">
            <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292 4 4 0 010-5.292M15 12H9m6 0h-6m6 0a4 4 0 110 5.292m-6-5.292a4 4 0 010 5.292M9 20h6a2 2 0 002-2V7a2 2 0 00-2-2H9a2 2 0 00-2 2v11a2 2 0 002 2z"></path>
            </svg>
          </div>
        </div>
      </div>
    </div>

    <!-- Quick Actions -->
    <div class="bg-white rounded-xl border border-gray-200 p-6 shadow-sm hover:shadow-md transition-shadow">
      <h2 class="text-lg font-bold text-gray-900 mb-6 flex items-center gap-2">
        <svg class="w-5 h-5 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
        </svg>
        Quick Actions
      </h2>
      <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        @can('create', \App\Models\EquipmentLog::class)
          <a href="{{ route('equipment-logs.create', ['project_id' => $project->id]) }}" class="px-4 py-3 bg-yellow-400 hover:bg-yellow-500 text-gray-900 font-semibold rounded-lg transition-all inline-flex items-center justify-center gap-2 shadow-sm hover:shadow-md">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            Log Equipment
          </a>
        @endcan
        @can('create', \App\Models\ProductivityLog::class)
          <a href="{{ route('productivity-logs.create', ['project_id' => $project->id]) }}" class="px-4 py-3 bg-yellow-400 hover:bg-yellow-500 text-gray-900 font-semibold rounded-lg transition-all inline-flex items-center justify-center gap-2 shadow-sm hover:shadow-md">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
            </svg>
            Log Productivity
          </a>
        @endcan
        @can('create', \App\Models\MaterialUsage::class)
          <a href="{{ route('material-usage.create', ['project_id' => $project->id]) }}" class="px-4 py-3 bg-yellow-400 hover:bg-yellow-500 text-gray-900 font-semibold rounded-lg transition-all inline-flex items-center justify-center gap-2 shadow-sm hover:shadow-md">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10l8-4"></path>
            </svg>
            Log Material Usage
          </a>
        @endcan
      </div>
    </div>

    <!-- Tabs for Different Log Types -->
    <div x-data="{ activeTab: 'equipment' }" class="space-y-4">
      <!-- Tab Navigation -->
      <div class="bg-white rounded-xl border border-gray-200">
        <div class="flex gap-0 border-b border-gray-200">
          <button
            @click="activeTab = 'equipment'"
            :class="activeTab === 'equipment' ? 'border-b-2 border-yellow-400 text-yellow-600 bg-yellow-50' : 'text-gray-600 hover:text-gray-900'"
            class="flex-1 px-4 py-3 font-medium transition-all flex items-center justify-center gap-2"
          >
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            Equipment
          </button>
          <button
            @click="activeTab = 'productivity'"
            :class="activeTab === 'productivity' ? 'border-b-2 border-yellow-400 text-yellow-600 bg-yellow-50' : 'text-gray-600 hover:text-gray-900'"
            class="flex-1 px-4 py-3 font-medium transition-all flex items-center justify-center gap-2"
          >
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
            </svg>
            Productivity
          </button>
          <button
            @click="activeTab = 'labour'"
            :class="activeTab === 'labour' ? 'border-b-2 border-yellow-400 text-yellow-600 bg-yellow-50' : 'text-gray-600 hover:text-gray-900'"
            class="flex-1 px-4 py-3 font-medium transition-all flex items-center justify-center gap-2"
          >
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292 4 4 0 010-5.292M15 12H9m6 0h-6m6 0a4 4 0 110 5.292m-6-5.292a4 4 0 010 5.292M9 20h6a2 2 0 002-2V7a2 2 0 00-2-2H9a2 2 0 00-2 2v11a2 2 0 002 2z"></path>
            </svg>
            Labour
          </button>
          <button
            @click="activeTab = 'materials'"
            :class="activeTab === 'materials' ? 'border-b-2 border-yellow-400 text-yellow-600 bg-yellow-50' : 'text-gray-600 hover:text-gray-900'"
            class="flex-1 px-4 py-3 font-medium transition-all flex items-center justify-center gap-2"
          >
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10l8-4"></path>
            </svg>
            Materials
          </button>
        </div>
      </div>

      <!-- Equipment Logs Tab -->
      <div x-show="activeTab === 'equipment'" class="bg-white rounded-xl border border-gray-200 p-6">
        <h3 class="text-lg font-bold text-gray-900 mb-6">Equipment Logs</h3>
        @if($project->equipmentLogs->count() > 0)
          <div class="overflow-x-auto">
            <table class="w-full text-sm">
              <thead>
                <tr class="border-b border-gray-200">
                  <th class="px-4 py-3 text-left font-semibold text-gray-900">Date</th>
                  <th class="px-4 py-3 text-left font-semibold text-gray-900">Equipment</th>
                  <th class="px-4 py-3 text-left font-semibold text-gray-900">Activity</th>
                  <th class="px-4 py-3 text-left font-semibold text-gray-900">Output</th>
                  <th class="px-4 py-3 text-left font-semibold text-gray-900">Hours</th>
                </tr>
              </thead>
              <tbody class="divide-y divide-gray-100">
                @foreach($project->equipmentLogs as $log)
                  <tr class="hover:bg-gray-50">
                    <td class="px-4 py-3 text-gray-900 font-medium">{{ $log->date->format('M d, Y') }}</td>
                    <td class="px-4 py-3 text-gray-600">{{ $log->equipment_id }} ({{ $log->equipment_type }})</td>
                    <td class="px-4 py-3 text-gray-600">{{ $log->activity }}</td>
                    <td class="px-4 py-3 text-gray-600">{{ $log->actual_output }}</td>
                    <td class="px-4 py-3 text-gray-600">{{ $log->working_hours }}h</td>
                  </tr>
                @endforeach
              </tbody>
            </table>
          </div>
        @else
          <div class="text-center py-12">
            <svg class="w-12 h-12 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            <p class="text-gray-600 font-medium">No equipment logs yet</p>
          </div>
        @endif
      </div>

      <!-- Productivity Logs Tab -->
      <div x-show="activeTab === 'productivity'" class="bg-white rounded-xl border border-gray-200 p-6">
        <h3 class="text-lg font-bold text-gray-900 mb-6">Productivity Logs</h3>
        @if($project->productivityLogs->count() > 0)
          <div class="overflow-x-auto">
            <table class="w-full text-sm">
              <thead>
                <tr class="border-b border-gray-200">
                  <th class="px-4 py-3 text-left font-semibold text-gray-900">Date</th>
                  <th class="px-4 py-3 text-left font-semibold text-gray-900">Activity</th>
                  <th class="px-4 py-3 text-left font-semibold text-gray-900">Equipment</th>
                  <th class="px-4 py-3 text-left font-semibold text-gray-900">Workers</th>
                  <th class="px-4 py-3 text-left font-semibold text-gray-900">Output</th>
                </tr>
              </thead>
              <tbody class="divide-y divide-gray-100">
                @foreach($project->productivityLogs as $log)
                  <tr class="hover:bg-gray-50">
                    <td class="px-4 py-3 text-gray-900 font-medium">{{ $log->date->format('M d, Y') }}</td>
                    <td class="px-4 py-3 text-gray-600">{{ $log->activity }}</td>
                    <td class="px-4 py-3 text-gray-600">{{ $log->equipment_name }}</td>
                    <td class="px-4 py-3 text-gray-600">{{ $log->workers }}</td>
                    <td class="px-4 py-3 text-gray-600">{{ $log->output }}</td>
                  </tr>
                @endforeach
              </tbody>
            </table>
          </div>
        @else
          <div class="text-center py-12">
            <svg class="w-12 h-12 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
            </svg>
            <p class="text-gray-600 font-medium">No productivity logs yet</p>
          </div>
        @endif
      </div>

      <!-- Labour Logs Tab -->
      <div x-show="activeTab === 'labour'" class="bg-white rounded-xl border border-gray-200 p-6">
        <h3 class="text-lg font-bold text-gray-900 mb-6">Labour Cost Logs</h3>
        @if($project->casualLabourLogs->count() > 0)
          <div class="overflow-x-auto">
            <table class="w-full text-sm">
              <thead>
                <tr class="border-b border-gray-200">
                  <th class="px-4 py-3 text-left font-semibold text-gray-900">Date</th>
                  <th class="px-4 py-3 text-left font-semibold text-gray-900">Activity</th>
                  <th class="px-4 py-3 text-left font-semibold text-gray-900">Classification</th>
                  <th class="px-4 py-3 text-left font-semibold text-gray-900">Workers</th>
                  <th class="px-4 py-3 text-left font-semibold text-gray-900">Total Cost</th>
                </tr>
              </thead>
              <tbody class="divide-y divide-gray-100">
                @foreach($project->casualLabourLogs as $log)
                  <tr class="hover:bg-gray-50">
                    <td class="px-4 py-3 text-gray-900 font-medium">{{ $log->date->format('M d, Y') }}</td>
                    <td class="px-4 py-3 text-gray-600">{{ $log->activity }}</td>
                    <td class="px-4 py-3 text-gray-600">{{ $log->labour_classification }}</td>
                    <td class="px-4 py-3 text-gray-600">{{ $log->number_of_workers }}</td>
                    <td class="px-4 py-3 text-gray-900 font-semibold">Rwf {{ number_format($log->total_cost, 2) }}</td>
                  </tr>
                @endforeach
              </tbody>
            </table>
          </div>
        @else
          <div class="text-center py-12">
            <svg class="w-12 h-12 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292 4 4 0 010-5.292M15 12H9m6 0h-6m6 0a4 4 0 110 5.292m-6-5.292a4 4 0 010 5.292M9 20h6a2 2 0 002-2V7a2 2 0 00-2-2H9a2 2 0 00-2 2v11a2 2 0 002 2z"></path>
            </svg>
            <p class="text-gray-600 font-medium">No labour logs yet</p>
          </div>
        @endif
      </div>

      <!-- Materials Tab -->
      <div x-show="activeTab === 'materials'" class="bg-white rounded-xl border border-gray-200 p-6">
        <h3 class="text-lg font-bold text-gray-900 mb-6">Material Usage & Costs</h3>
        @if($project->materialUsage->count() > 0 || $project->materialCosts->count() > 0)
          <div class="overflow-x-auto">
            <table class="w-full text-sm">
              <thead>
                <tr class="border-b border-gray-200">
                  <th class="px-4 py-3 text-left font-semibold text-gray-900">Date</th>
                  <th class="px-4 py-3 text-left font-semibold text-gray-900">Material</th>
                  <th class="px-4 py-3 text-left font-semibold text-gray-900">Activity</th>
                  <th class="px-4 py-3 text-left font-semibold text-gray-900">Planned</th>
                  <th class="px-4 py-3 text-left font-semibold text-gray-900">Used</th>
                  <th class="px-4 py-3 text-left font-semibold text-gray-900">Cost</th>
                </tr>
              </thead>
              <tbody class="divide-y divide-gray-100">
                @forelse($project->materialUsage as $usage)
                  @php
                    $cost = $project->materialCosts
                      ->where('material_name', $usage->material_name)
                      ->where('date', $usage->date->format('Y-m-d'))
                      ->first();
                  @endphp
                  <tr class="hover:bg-gray-50">
                    <td class="px-4 py-3 text-gray-900 font-medium">{{ $usage->date->format('M d, Y') }}</td>
                    <td class="px-4 py-3 text-gray-600">{{ $usage->material_name }}</td>
                    <td class="px-4 py-3 text-gray-600">{{ $usage->activity }}</td>
                    <td class="px-4 py-3 text-gray-600">{{ $usage->planned_qty }}</td>
                    <td class="px-4 py-3 text-gray-600">{{ $usage->used_qty }}</td>
                    <td class="px-4 py-3 text-gray-900 font-semibold">Rwf {{ number_format($cost->total ?? 0, 2) }}</td>
                  </tr>
                @empty
                  <tr>
                    <td colspan="6" class="text-center text-gray-600 py-8">No material records yet</td>
                  </tr>
                @endforelse
              </tbody>
            </table>
          </div>
        @else
          <div class="text-center py-12">
            <svg class="w-12 h-12 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10l8-4"></path>
            </svg>
            <p class="text-gray-600 font-medium">No material records yet</p>
          </div>
        @endif
      </div>
    </div>
  </div>

  <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
@endsection
