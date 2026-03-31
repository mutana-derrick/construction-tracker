@extends('layouts.app')

@section('page-title', $project->name)

@section('content')
  <div class="space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
      <div>
        <a href="{{ route('projects.index') }}" class="text-primary-600 hover:text-primary-700 mb-2 inline-block">
          ← Back to Projects
        </a>
        <h1 class="text-3xl font-bold text-gray-900">{{ $project->name }}</h1>
        <p class="text-gray-600 mt-1">📍 {{ $project->location }}</p>
      </div>
      @can('update', $project)
        <a href="{{ route('projects.edit', $project) }}" class="btn-secondary">
          ✏️ Edit Project
        </a>
      @endcan
    </div>

    <!-- Project Details -->
    <div class="card">
      <h2 class="text-lg font-semibold text-gray-900 mb-4">Project Details</h2>
      <div class="grid grid-cols-2 gap-6">
        <div>
          <p class="text-gray-600 text-sm">Created By</p>
          <p class="text-gray-900 font-medium mt-1">{{ $project->creator->name }}</p>
        </div>
        <div>
          <p class="text-gray-600 text-sm">Created Date</p>
          <p class="text-gray-900 font-medium mt-1">{{ $project->created_at->format('M d, Y') }}</p>
        </div>
        <div class="col-span-2">
          <p class="text-gray-600 text-sm">Description</p>
          <p class="text-gray-900 mt-1">{{ $project->description ?? 'No description provided' }}</p>
        </div>
      </div>
    </div>

    <!-- Project Stats -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
      <div class="card">
        <p class="text-gray-600 text-sm">Equipment Logs</p>
        <p class="text-3xl font-bold text-gray-900 mt-2">{{ $project->equipmentLogs->count() }}</p>
      </div>
      <div class="card">
        <p class="text-gray-600 text-sm">Total Equipment Cost</p>
        <p class="text-3xl font-bold text-gray-900 mt-2">${{ number_format($project->equipmentCosts->sum('total_cost'), 0) }}</p>
      </div>
      <div class="card">
        <p class="text-gray-600 text-sm">Labour Cost</p>
        <p class="text-3xl font-bold text-gray-900 mt-2">${{ number_format($project->casualLabourLogs->sum('total_cost'), 0) }}</p>
      </div>
    </div>

    <!-- Quick Actions -->
    <div class="card">
      <h2 class="text-lg font-semibold text-gray-900 mb-4">Quick Actions</h2>
      <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        @can('create', \App\Models\EquipmentLog::class)
          <a href="{{ route('equipment-logs.create', ['project_id' => $project->id]) }}" class="btn-secondary block text-center">
            🚜 Log Equipment
          </a>
        @endcan
        @can('create', \App\Models\ProductivityLog::class)
          <a href="{{ route('productivity-logs.create', ['project_id' => $project->id]) }}" class="btn-secondary block text-center">
            📈 Log Productivity
          </a>
        @endcan
        @can('create', \App\Models\MaterialUsage::class)
          <a href="{{ route('material-usage.create', ['project_id' => $project->id]) }}" class="btn-secondary block text-center">
            📦 Log Material Usage
          </a>
        @endcan
      </div>
    </div>

    <!-- Tabs for Different Log Types -->
    <div x-data="{ activeTab: 'equipment' }" class="space-y-4">
      <!-- Tab Navigation -->
      <div class="card">
        <div class="flex gap-4 border-b border-gray-200">
          <button
            @click="activeTab = 'equipment'"
            :class="activeTab === 'equipment' ? 'border-b-2 border-primary-400 text-primary-600' : 'text-gray-600'"
            class="px-4 py-2 font-medium transition-colors"
          >
            🚜 Equipment
          </button>
          <button
            @click="activeTab = 'productivity'"
            :class="activeTab === 'productivity' ? 'border-b-2 border-primary-400 text-primary-600' : 'text-gray-600'"
            class="px-4 py-2 font-medium transition-colors"
          >
            📈 Productivity
          </button>
          <button
            @click="activeTab = 'labour'"
            :class="activeTab === 'labour' ? 'border-b-2 border-primary-400 text-primary-600' : 'text-gray-600'"
            class="px-4 py-2 font-medium transition-colors"
          >
            👥 Labour
          </button>
          <button
            @click="activeTab = 'materials'"
            :class="activeTab === 'materials' ? 'border-b-2 border-primary-400 text-primary-600' : 'text-gray-600'"
            class="px-4 py-2 font-medium transition-colors"
          >
            📦 Materials
          </button>
        </div>
      </div>

      <!-- Equipment Logs Tab -->
      <div x-show="activeTab === 'equipment'" class="card">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Equipment Logs</h3>
        @if($project->equipmentLogs->count() > 0)
          <div class="overflow-x-auto">
            <table class="table">
              <thead>
                <tr>
                  <th>Date</th>
                  <th>Equipment</th>
                  <th>Activity</th>
                  <th>Output</th>
                  <th>Hours</th>
                </tr>
              </thead>
              <tbody>
                @foreach($project->equipmentLogs as $log)
                  <tr>
                    <td>{{ $log->date->format('M d, Y') }}</td>
                    <td>{{ $log->equipment_id }} ({{ $log->equipment_type }})</td>
                    <td>{{ $log->activity }}</td>
                    <td>{{ $log->actual_output }}</td>
                    <td>{{ $log->working_hours }}</td>
                  </tr>
                @endforeach
              </tbody>
            </table>
          </div>
        @else
          <p class="text-gray-600 text-center py-8">No equipment logs yet</p>
        @endif
      </div>

      <!-- Productivity Logs Tab -->
      <div x-show="activeTab === 'productivity'" class="card">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Productivity Logs</h3>
        @if($project->productivityLogs->count() > 0)
          <div class="overflow-x-auto">
            <table class="table">
              <thead>
                <tr>
                  <th>Date</th>
                  <th>Activity</th>
                  <th>Equipment</th>
                  <th>Workers</th>
                  <th>Output</th>
                </tr>
              </thead>
              <tbody>
                @foreach($project->productivityLogs as $log)
                  <tr>
                    <td>{{ $log->date->format('M d, Y') }}</td>
                    <td>{{ $log->activity }}</td>
                    <td>{{ $log->equipment_name }}</td>
                    <td>{{ $log->workers }}</td>
                    <td>{{ $log->output }}</td>
                  </tr>
                @endforeach
              </tbody>
            </table>
          </div>
        @else
          <p class="text-gray-600 text-center py-8">No productivity logs yet</p>
        @endif
      </div>

      <!-- Labour Logs Tab -->
      <div x-show="activeTab === 'labour'" class="card">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Labour Cost Logs</h3>
        @if($project->casualLabourLogs->count() > 0)
          <div class="overflow-x-auto">
            <table class="table">
              <thead>
                <tr>
                  <th>Date</th>
                  <th>Activity</th>
                  <th>Classification</th>
                  <th>Workers</th>
                  <th>Total Cost</th>
                </tr>
              </thead>
              <tbody>
                @foreach($project->casualLabourLogs as $log)
                  <tr>
                    <td>{{ $log->date->format('M d, Y') }}</td>
                    <td>{{ $log->activity }}</td>
                    <td>{{ $log->labour_classification }}</td>
                    <td>{{ $log->number_of_workers }}</td>
                    <td>${{ number_format($log->total_cost, 2) }}</td>
                  </tr>
                @endforeach
              </tbody>
            </table>
          </div>
        @else
          <p class="text-gray-600 text-center py-8">No labour logs yet</p>
        @endif
      </div>

      <!-- Materials Tab -->
      <div x-show="activeTab === 'materials'" class="card">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Material Usage & Costs</h3>
        @if($project->materialUsage->count() > 0 || $project->materialCosts->count() > 0)
          <div class="overflow-x-auto">
            <table class="table">
              <thead>
                <tr>
                  <th>Date</th>
                  <th>Material</th>
                  <th>Activity</th>
                  <th>Planned</th>
                  <th>Used</th>
                  <th>Cost</th>
                </tr>
              </thead>
              <tbody>
                @forelse($project->materialUsage as $usage)
                  @php
                    $cost = $project->materialCosts
                      ->where('material_name', $usage->material_name)
                      ->where('date', $usage->date->format('Y-m-d'))
                      ->first();
                  @endphp
                  <tr>
                    <td>{{ $usage->date->format('M d, Y') }}</td>
                    <td>{{ $usage->material_name }}</td>
                    <td>{{ $usage->activity }}</td>
                    <td>{{ $usage->planned_qty }}</td>
                    <td>{{ $usage->used_qty }}</td>
                    <td>${{ number_format($cost->total ?? 0, 2) }}</td>
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
          <p class="text-gray-600 text-center py-8">No material records yet</p>
        @endif
      </div>
    </div>
  </div>

  <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
@endsection
