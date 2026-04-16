@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Header -->
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Equipment Log Details</h1>
            <p class="text-gray-600 mt-1">{{ $project->name }}</p>
        </div>
        @if($canEdit)
        <a href="{{ route('equipment-logs.edit', $log) }}" class="inline-flex items-center gap-2 px-4 py-2.5 bg-yellow-400 hover:bg-yellow-500 text-gray-900 font-semibold rounded-lg transition-all duration-200 shadow-sm hover:shadow-md">
            ✎ Edit Log
        </a>
        @endif
    </div>

    <!-- 5-Minute Edit Status -->
    @php
        $minutesElapsed = now()->diffInMinutes($log->created_at);
        $canStillEdit = $minutesElapsed <= 5;
    @endphp

    <div class="bg-white rounded-xl border border-gray-200 p-6 mb-6 shadow-sm hover:shadow-md transition-shadow">
        <div class="flex items-center justify-between">
            <div>
                <h3 class="font-semibold text-gray-900">⏱️ Edit Window Status</h3>
                <p class="text-sm text-gray-600 mt-1">Created at {{ $log->created_at->format('g:i A') }} • {{ $minutesElapsed }} minutes ago</p>
            </div>
            <div class="text-right">
                @if($canStillEdit)
                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                    ✓ Still Editable ({{ 5 - $minutesElapsed }} min left)
                </span>
                @else
                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-gray-100 text-gray-800">
                    🔒 No Longer Editable
                </span>
                @endif
            </div>
        </div>
    </div>

    <!-- Equipment Information Card -->
    <div class="bg-white rounded-xl border border-gray-200 p-6 mb-6 shadow-sm hover:shadow-md transition-shadow">
        <div class="grid grid-cols-2 gap-8">
            <div>
                <h3 class="font-semibold text-gray-900 mb-4">🚜 Equipment Information</h3>
                <dl class="space-y-3">
                    <div>
                        <dt class="text-sm font-medium text-gray-600">Equipment Type</dt>
                        <dd class="text-lg font-semibold text-gray-900">{{ $log->equipment_type }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-600">Equipment ID</dt>
                        <dd class="font-mono text-gray-900">{{ $log->equipment_id }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-600">Activity</dt>
                        <dd class="text-gray-900">{{ $log->activity }}</dd>
                    </div>
                </dl>
            </div>

            <div>
                <h3 class="font-semibold text-gray-900 mb-4">📋 Log Information</h3>
                <dl class="space-y-3">
                    <div>
                        <dt class="text-sm font-medium text-gray-600">Date Recorded</dt>
                        <dd class="text-lg font-semibold text-gray-900">{{ \Carbon\Carbon::parse($log->date)->format('F d, Y') }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-600">Recorded By</dt>
                        <dd class="text-gray-900">{{ $log->user->name }} ({{ $log->user->email }})</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-600">Created At</dt>
                        <dd class="text-gray-900">{{ $log->created_at->format('F d, Y g:i A') }}</dd>
                    </div>
                </dl>
            </div>
        </div>
    </div>

    <!-- Output Metrics Card -->
    <div class="bg-white rounded-xl border border-gray-200 p-6 mb-6 shadow-sm hover:shadow-md transition-shadow">
        <h3 class="font-semibold text-gray-900 mb-4">📊 Output Metrics</h3>
        <div class="grid grid-cols-2 gap-8">
            <div>
                <div class="bg-yellow-50 rounded-lg p-4 border border-yellow-200 shadow-sm">
                    <p class="text-sm font-medium text-gray-600">Planned Output</p>
                    <p class="text-3xl font-bold text-gray-900 mt-1">{{ number_format($log->planned_output, 2) }}</p>
                </div>
            </div>
            <div>
                <div class="bg-yellow-50 rounded-lg p-4 border border-yellow-200 shadow-sm">
                    <p class="text-sm font-medium text-gray-600">Actual Output</p>
                    <p class="text-3xl font-bold text-gray-900 mt-1">{{ number_format($log->actual_output, 2) }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Hours and Productivity Card -->
    <div class="bg-white rounded-xl border border-gray-200 p-6 mb-6 shadow-sm hover:shadow-md transition-shadow">
        <h3 class="font-semibold text-gray-900 mb-4">⏱️ Working Hours & Productivity</h3>
        <div class="grid grid-cols-3 gap-6">
            <div class="bg-yellow-50 rounded-lg p-4 border border-yellow-200 shadow-sm">
                <p class="text-sm font-medium text-gray-600">Working Hours</p>
                <p class="text-2xl font-bold text-gray-900 mt-1">{{ number_format($log->working_hours, 1) }}h</p>
            </div>
            <div class="bg-yellow-50 rounded-lg p-4 border border-yellow-200 shadow-sm">
                <p class="text-sm font-medium text-gray-600">Available Hours</p>
                <p class="text-2xl font-bold text-gray-900 mt-1">{{ number_format($log->available_hours, 1) }}h</p>
            </div>
            <div class="bg-yellow-50 rounded-lg p-4 border border-yellow-200 shadow-sm">
                <p class="text-sm font-medium text-gray-600">Utilization</p>
                <p class="text-2xl font-bold text-gray-900 mt-1">{{ number_format($log->utilization, 1) }}%</p>
            </div>
        </div>

        <!-- Productivity Display -->
        @if($log->working_hours > 0)
        <div class="mt-6 bg-yellow-50 border border-yellow-200 rounded-lg p-6 shadow-sm">
            <h4 class="font-semibold text-gray-900 mb-2">Productivity Analysis</h4>
            <div class="flex items-end gap-4">
                <div>
                    <p class="text-sm text-gray-600">Output per Hour</p>
                    <p class="text-4xl font-bold text-yellow-600 mt-1">{{ number_format($log->productivity, 2) }}</p>
                </div>
                <div class="flex-1">
                    <div class="h-2 bg-gray-200 rounded-full overflow-hidden">
                        <div class="h-full bg-yellow-500 rounded-full" style="width: {{ min(100, $log->productivity * 100) }}%"></div>
                    </div>
                </div>
                @if($log->productivity >= 0.8)
                <span class="text-green-600 font-semibold">✓ Excellent</span>
                @elseif($log->productivity >= 0.6)
                <span class="text-yellow-600 font-semibold">≈ Good</span>
                @else
                <span class="text-red-600 font-semibold">⚠ Low</span>
                @endif
            </div>
        </div>
        @endif
    </div>

    <!-- Additional Information Card -->
    <div class="bg-white rounded-xl border border-gray-200 p-6 mb-6 shadow-sm hover:shadow-md transition-shadow">
        <h3 class="font-semibold text-gray-900 mb-4">🛢️ Additional Information</h3>
        <dl class="space-y-4">
            @if($log->fuel_used)
            <div>
                <dt class="text-sm font-medium text-gray-600">Fuel Used</dt>
                <dd class="text-lg text-gray-900">{{ number_format($log->fuel_used, 2) }} Liters</dd>
            </div>
            @endif

            @if($log->comment)
            <div>
                <dt class="text-sm font-medium text-gray-600">Comments</dt>
                <dd class="text-gray-900 mt-1 p-3 bg-gray-50 rounded border border-gray-200 shadow-sm">{{ $log->comment }}</dd>
            </div>
            @else
            <p class="text-gray-500 italic">No additional comments</p>
            @endif
        </dl>
    </div>

    <!-- Actions -->
    <div class="flex items-center gap-4 mb-6">
        @if($canEdit)
        <a href="{{ route('equipment-logs.edit', $log) }}" class="bg-yellow-400 hover:bg-yellow-500 text-gray-900 font-semibold rounded-lg shadow-sm hover:shadow-md transition-all px-4 py-2">
            ✎ Edit Log
        </a>
        @endif
        <a href="{{ route('equipment-logs.index', ['project_id' => $project->id]) }}" class="bg-gray-100 hover:bg-gray-200 text-gray-900 font-semibold rounded-lg shadow-sm px-4 py-2">
            ← Back to Equipment Logs
        </a>
        <a href="{{ route('projects.show', $project) }}" class="bg-gray-100 hover:bg-gray-200 text-gray-900 font-semibold rounded-lg shadow-sm px-4 py-2">
            ← Back to Project
        </a>
    </div>
</div>
@endsection
