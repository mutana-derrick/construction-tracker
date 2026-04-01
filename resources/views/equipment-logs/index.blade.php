@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col md:flex-row items-start md:items-center justify-between gap-4">
      <div>
        <div class="flex items-center gap-3 mb-2">
          <svg class="w-8 h-8 text-primary-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
          </svg>
          <h1 class="text-3xl font-bold text-gray-900">Equipment Logs</h1>
        </div>
        <p class="text-gray-600 mt-2">Project: <span class="font-semibold">{{ $project->name }}</span></p>
      </div>
      @can('create', App\Models\EquipmentLog::class)
      <a href="{{ route('equipment-logs.create', ['project_id' => $project->id]) }}" class="inline-flex items-center gap-2 px-4 py-2.5 bg-primary-400 hover:bg-primary-500 text-gray-900 font-semibold rounded-lg transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-primary-400 focus:ring-offset-2 whitespace-nowrap">
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

    <!-- Equipment Logs Table -->
    <div class="bg-white rounded-xl border border-gray-200">
        @if($logs->count() > 0)
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-gray-200">
                        <th class="px-6 py-4 text-left font-semibold text-gray-900">Date</th>
                        <th class="px-6 py-4 text-left font-semibold text-gray-900">Equipment</th>
                        <th class="px-6 py-4 text-left font-semibold text-gray-900">ID</th>
                        <th class="px-6 py-4 text-left font-semibold text-gray-900">Activity</th>
                        <th class="px-6 py-4 text-left font-semibold text-gray-900">Output</th>
                        <th class="px-6 py-4 text-left font-semibold text-gray-900">Hours</th>
                        <th class="px-6 py-4 text-left font-semibold text-gray-900">Productivity</th>
                        <th class="px-6 py-4 text-left font-semibold text-gray-900">Recorded By</th>
                        <th class="px-6 py-4 text-right font-semibold text-gray-900">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach($logs as $log)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4 whitespace-nowrap font-medium text-gray-900">{{ \Carbon\Carbon::parse($log->date)->format('M d, Y') }}</td>
                        <td class="px-6 py-4 text-gray-600">{{ $log->equipment_type }}</td>
                        <td class="px-6 py-4 font-mono text-xs text-gray-500 bg-gray-50 px-2 py-1 rounded">{{ $log->equipment_id }}</td>
                        <td class="px-6 py-4 text-gray-600">{{ Str::limit($log->activity, 30) }}</td>
                        <td class="px-6 py-4 text-gray-900 font-medium">{{ number_format($log->actual_output, 2) }}</td>
                        <td class="px-6 py-4 text-gray-600">{{ number_format($log->working_hours, 1) }}h / {{ number_format($log->available_hours, 1) }}h</td>
                        <td class="px-6 py-4">
                            @if($log->working_hours > 0)
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold @if($log->productivity >= 0.8) bg-green-100 text-green-700 border border-green-200 @elseif($log->productivity >= 0.6) bg-yellow-100 text-yellow-700 border border-yellow-200 @else bg-red-100 text-red-700 border border-red-200 @endif">
                                <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                  <path fill-rule="evenodd" d="M12 7a1 1 0 110-2 1 1 0 010 2zM9 11a1 1 0 100-2 1 1 0 000 2zm6 0a1 1 0 100-2 1 1 0 000 2zM9 15a1 1 0 100-2 1 1 0 000 2zm6 0a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd"></path>
                                </svg>
                                {{ number_format($log->productivity * 100, 1) }}%
                            </span>
                            @else
                            <span class="text-gray-400 text-sm">—</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-gray-600">
                          <div class="inline-flex items-center gap-2">
                            <div class="w-6 h-6 bg-blue-100 rounded-full flex items-center justify-center text-xs font-semibold text-blue-600">
                              {{ substr($log->user->name, 0, 1) }}
                            </div>
                            {{ $log->user->name }}
                          </div>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <div class="flex items-center justify-end gap-3">
                                <a href="{{ route('equipment-logs.show', $log) }}" class="inline-flex items-center gap-1 text-primary-400 hover:text-primary-500 font-medium transition-colors text-sm">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                    </svg>
                                    View
                                </a>
                                @can('update', $log)
                                <a href="{{ route('equipment-logs.edit', $log) }}" class="inline-flex items-center gap-1 text-gray-600 hover:text-gray-900 font-medium transition-colors text-sm">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                    </svg>
                                    Edit
                                </a>
                                @else
                                <span class="text-gray-400 text-sm italic">Edit</span>
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
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
          </svg>
          <p class="text-gray-600 mb-4 font-medium">No equipment logs recorded yet</p>
          @can('create', App\Models\EquipmentLog::class)
          <a href="{{ route('equipment-logs.create', ['project_id' => $project->id]) }}" class="inline-flex items-center gap-2 px-4 py-2.5 bg-primary-50 text-primary-600 hover:bg-primary-100 rounded-lg font-medium transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
            </svg>
            Create first equipment log
          </a>
          @endcan
        </div>
        @endif
    </div>

    <!-- Back Link -->
    <div>
        <a href="{{ route('projects.show', $project) }}" class="inline-flex items-center gap-1 text-gray-600 hover:text-gray-900 font-medium transition-colors">
          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
          </svg>
          Back to Project
        </a>
    </div>
</div>
@endsection
