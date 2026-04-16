@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col md:flex-row items-start md:items-center justify-between gap-4">
      <div>
        <div class="flex items-center gap-3 mb-2">
          <svg class="w-8 h-8 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 8.646 4 4 0 010-8.646zM12 14a7 7 0 100 14 7 7 0 000-14z"></path>
          </svg>
          <h1 class="text-3xl font-bold text-gray-900">Casual Labour Logs</h1>
        </div>
        <p class="text-gray-600 mt-2">Project: <span class="font-semibold">{{ $project->name }}</span></p>
      </div>
      @can('create', App\Models\CasualLabourLog::class)
      <a href="{{ route('casual-labour-logs.create', ['project_id' => $project->id]) }}" class="inline-flex items-center gap-2 px-4 py-2.5 bg-yellow-400 hover:bg-yellow-500 text-gray-900 font-semibold rounded-lg transition-all duration-200 shadow-sm hover:shadow-md focus:outline-none focus:ring-2 focus:ring-yellow-400 focus:ring-offset-2 whitespace-nowrap">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
        </svg>
        New Log
      </a>
      @endcan
    </div>

    <!-- Flash Messages -->
    @if ($message = Session::get('success'))
    <div class="bg-green-50 border border-green-200 rounded-lg p-4 flex items-center gap-3">
      <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
      </svg>
      <span class="text-green-800">{{ $message }}</span>
    </div>
    @endif

    @if ($message = Session::get('error'))
    <div class="bg-red-50 border border-red-200 rounded-lg p-4 flex items-center gap-3">
      <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
      </svg>
      <span class="text-red-800">{{ $message }}</span>
    </div>
    @endif

    <!-- Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Total Records -->
        <div class="bg-white rounded-xl border border-gray-200 p-6 hover:shadow-lg hover:border-yellow-300 transition-all duration-200">
            <div class="flex items-start justify-between">
                <div class="flex-1">
                    <p class="text-sm font-semibold text-gray-600 uppercase tracking-wide">Total Records</p>
                    <p class="text-3xl font-bold text-gray-900 mt-4">{{ $logs->total() }}</p>
                </div>
                <div class="inline-flex items-center justify-center w-12 h-12 bg-yellow-100 rounded-lg group-hover:bg-yellow-200 transition-colors">
                    <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Total Workers -->
        <div class="bg-white rounded-xl border border-gray-200 p-6 hover:shadow-lg hover:border-yellow-300 transition-all duration-200">
            <div class="flex items-start justify-between">
                <div class="flex-1">
                    <p class="text-sm font-semibold text-gray-600 uppercase tracking-wide">Total Workers</p>
                    <p class="text-3xl font-bold text-gray-900 mt-4">
                        @php
                            $totalWorkers = $logs->sum('number_of_workers');
                        @endphp
                        {{ number_format($totalWorkers) }}
                    </p>
                </div>
                <div class="inline-flex items-center justify-center w-12 h-12 bg-yellow-100 rounded-lg group-hover:bg-yellow-200 transition-colors">
                    <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 8.646 4 4 0 010-8.646zM12 14a7 7 0 100 14 7 7 0 000-14z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Total Labour Cost -->
        <div class="bg-white rounded-xl border border-gray-200 p-6 hover:shadow-lg hover:border-yellow-300 transition-all duration-200">
            <div class="flex items-start justify-between">
                <div class="flex-1">
                    <p class="text-sm font-semibold text-gray-600 uppercase tracking-wide">Total Labour Cost</p>
                    <p class="text-3xl font-bold text-gray-900 mt-4">
                        @php
                            $totalCost = $logs->sum('total_cost');
                        @endphp
                        ${{ number_format($totalCost, 2) }}
                    </p>
                </div>
                <div class="inline-flex items-center justify-center w-12 h-12 bg-yellow-100 rounded-lg group-hover:bg-yellow-200 transition-colors">
                    <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Average Wage -->
        <div class="bg-white rounded-xl border border-gray-200 p-6 hover:shadow-lg hover:border-yellow-300 transition-all duration-200">
            <div class="flex items-start justify-between">
                <div class="flex-1">
                    <p class="text-sm font-semibold text-gray-600 uppercase tracking-wide">Average Wage</p>
                    <p class="text-3xl font-bold text-gray-900 mt-4">
                        @php
                            $avgWage = $logs->count() > 0 ? round($logs->sum('wage') / $logs->count(), 2) : 0;
                        @endphp
                        ${{ number_format($avgWage, 2) }}
                    </p>
                </div>
                <div class="inline-flex items-center justify-center w-12 h-12 bg-yellow-100 rounded-lg group-hover:bg-yellow-200 transition-colors">
                    <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Logs Table -->
    <div class="bg-white rounded-xl border border-gray-200 shadow-sm hover:shadow-md transition-shadow">
        @if($logs->count() > 0)
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-gray-200">
                        <th class="px-6 py-4 text-left font-semibold text-gray-900">Date</th>
                        <th class="px-6 py-4 text-left font-semibold text-gray-900">Activity</th>
                        <th class="px-6 py-4 text-left font-semibold text-gray-900">Classification</th>
                        <th class="px-6 py-4 text-left font-semibold text-gray-900">Workers</th>
                        <th class="px-6 py-4 text-left font-semibold text-gray-900">Wage</th>
                        <th class="px-6 py-4 text-left font-semibold text-gray-900">Total Cost</th>
                        <th class="px-6 py-4 text-left font-semibold text-gray-900">Recorded By</th>
                        <th class="px-6 py-4 text-right font-semibold text-gray-900">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach ($logs as $log)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4 whitespace-nowrap font-medium text-gray-900">{{ $log->date->format('M d, Y') }}</td>
                        <td class="px-6 py-4 text-gray-600">{{ $log->activity }}</td>
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 bg-yellow-100 text-yellow-700 rounded-md text-xs font-semibold border border-yellow-200">
                                {{ $log->labour_classification }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 bg-yellow-100 text-yellow-700 rounded-md text-xs font-semibold border border-yellow-200">
                                <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                  <path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM9 1.5A6.5 6.5 0 1 0 15.5 8 6.5 6.5 0 0 0 9 1.5z"></path>
                                </svg>
                                {{ $log->number_of_workers }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-gray-900 font-medium">${{ number_format($log->wage, 2) }}</td>
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 bg-yellow-100 text-yellow-700 rounded-md text-xs font-bold border border-yellow-200">
                                <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                  <path d="M10.5 1.5H5.75A2.25 2.25 0 003.5 3.75v12.5A2.25 2.25 0 005.75 18.5h8.5a2.25 2.25 0 002.25-2.25V6.75m-9-5v3.5m5-3.5v3.5m-8.5 0h10"></path>
                                </svg>
                                ${{ number_format($log->total_cost, 2) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-gray-600">
                          <div class="inline-flex items-center gap-2">
                            <div class="w-6 h-6 bg-yellow-100 rounded-full flex items-center justify-center text-xs font-semibold text-yellow-700">
                              {{ substr($log->user->name, 0, 1) }}
                            </div>
                            {{ $log->user->name }}
                          </div>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <div class="flex items-center justify-end gap-3">
                                <a href="{{ route('casual-labour-logs.show', $log) }}" class="inline-flex items-center gap-1 text-yellow-600 hover:text-yellow-700 font-medium transition-colors text-sm hover:bg-yellow-50 px-2 py-1 rounded">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                    </svg>
                                    View
                                </a>
                                @can('update', $log)
                                <a href="{{ route('casual-labour-logs.edit', $log) }}" class="inline-flex items-center gap-1 text-gray-600 hover:text-gray-900 font-medium transition-colors text-sm">
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
            {{ $logs->links() }}
        </div>
        @else
        <div class="text-center py-12 px-6">
            <svg class="w-12 h-12 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 8.646 4 4 0 010-8.646zM12 14a7 7 0 100 14 7 7 0 000-14z"></path>
            </svg>
            <p class="text-gray-600 mb-4 font-medium">No casual labour logs yet</p>
            @can('create', App\Models\CasualLabourLog::class)
            <a href="{{ route('casual-labour-logs.create', ['project_id' => $project->id]) }}" class="inline-flex items-center gap-2 px-4 py-2.5 bg-yellow-400 hover:bg-yellow-500 text-gray-900 font-semibold rounded-lg transition-all duration-200 shadow-sm hover:shadow-md">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                Create First Log
            </a>
            @endcan
        </div>
        @endif
    </div>
</div>
@endsection
