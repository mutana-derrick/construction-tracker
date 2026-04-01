@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Header -->
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Material Usage Log Details</h1>
            <p class="text-gray-600 mt-1">{{ $project->name }}</p>
        </div>
        @if($canEdit)
        <a href="{{ route('material-usage.edit', $log) }}" class="btn-primary">
            ✎ Edit Record
        </a>
        @endif
    </div>

    <!-- 5-Minute Edit Status -->
    @php
        $minutesElapsed = now()->diffInMinutes($log->created_at);
        $canStillEdit = $minutesElapsed <= 5;
    @endphp

    <div class="card mb-6">
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

    <!-- Material Information Card -->
    <div class="card mb-6">
        <div class="grid grid-cols-2 gap-8">
            <div>
                <h3 class="font-semibold text-gray-900 mb-4">📦 Material Information</h3>
                <dl class="space-y-3">
                    <div>
                        <dt class="text-sm font-medium text-gray-600">Material Name</dt>
                        <dd class="text-lg font-semibold text-gray-900">{{ $log->material_name }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-600">Activity</dt>
                        <dd class="text-gray-900">{{ $log->activity }}</dd>
                    </div>
                </dl>
            </div>

            <div>
                <h3 class="font-semibold text-gray-900 mb-4">📋 Record Information</h3>
                <dl class="space-y-3">
                    <div>
                        <dt class="text-sm font-medium text-gray-600">Date Recorded</dt>
                        <dd class="text-lg font-semibold text-gray-900">{{ \Carbon\Carbon::parse($log->date)->format('F d, Y') }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-600">Recorded By</dt>
                        <dd class="text-gray-900">{{ $log->user->name }} ({{ $log->user->email }})</dd>
                    </div>
                </dl>
            </div>
        </div>
    </div>

    <!-- Usage Analysis Card -->
    @php
        $difference = $log->planned_qty - $log->used_qty;
        $diffColor = $difference > 0 ? 'green' : ($difference < 0 ? 'red' : 'blue');
        $usagePercent = $log->planned_qty > 0 ? ($log->used_qty / $log->planned_qty * 100) : 0;
    @endphp

    <div class="card mb-6">
        <h3 class="font-semibold text-gray-900 mb-4">📊 Material Usage Analysis</h3>
        <div class="grid grid-cols-3 gap-6">
            <div class="bg-gray-50 rounded-lg p-6 border border-gray-200">
                <p class="text-sm font-medium text-gray-600">Planned Quantity</p>
                <p class="text-3xl font-bold text-blue-600 mt-2">{{ number_format($log->planned_qty, 2) }}</p>
            </div>

            <div class="bg-gray-50 rounded-lg p-6 border border-gray-200">
                <p class="text-sm font-medium text-gray-600">Used Quantity</p>
                <p class="text-3xl font-bold text-orange-600 mt-2">{{ number_format($log->used_qty, 2) }}</p>
            </div>

            <div class="bg-{{ $diffColor }}-50 rounded-lg p-6 border border-{{ $diffColor }}-200">
                <p class="text-sm font-medium text-gray-600">Difference</p>
                <p class="text-3xl font-bold text-{{ $diffColor }}-600 mt-2">{{ number_format($difference, 2) }}</p>
            </div>
        </div>

        <!-- Usage Percentage & Status -->
        <div class="mt-6 grid grid-cols-2 gap-6">
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-6">
                <h4 class="font-semibold text-gray-900 mb-3">Usage Percentage</h4>
                <div class="flex items-center gap-4">
                    <div class="flex-1">
                        <div class="w-full bg-gray-200 rounded-full h-3">
                            <div class="bg-blue-600 h-3 rounded-full" style="width: {{ min($usagePercent, 100) }}%"></div>
                        </div>
                        <p class="text-sm text-gray-600 mt-2">{{ number_format($usagePercent, 1) }}% of planned used</p>
                    </div>
                </div>
            </div>

            <div class="bg-{{ $diffColor }}-50 border border-{{ $diffColor }}-200 rounded-lg p-6">
                <h4 class="font-semibold text-gray-900 mb-3">Budget Status</h4>
                <p class="text-lg font-semibold text-{{ $diffColor }}-700">
                    @if($difference > 0)
                        ✓ Under Budget
                        <span class="text-sm block mt-1">Surplus: {{ number_format($difference, 2) }}</span>
                    @elseif($difference < 0)
                        ⚠️ Over Budget
                        <span class="text-sm block mt-1">Shortage: {{ number_format(abs($difference), 2) }}</span>
                    @else
                        ◆ Exact Match
                        <span class="text-sm block mt-1">Perfect usage alignment</span>
                    @endif
                </p>
            </div>
        </div>

        <!-- Calculation Display -->
        <div class="mt-6 bg-blue-50 border border-blue-200 rounded-lg p-6">
            <h4 class="font-semibold text-gray-900 mb-2">Usage Calculation</h4>
            <div class="flex items-center justify-center gap-4 text-lg">
                <span class="text-gray-900">{{ number_format($log->planned_qty, 2) }} planned</span>
                <span class="text-2xl text-gray-400">−</span>
                <span class="text-orange-600 font-semibold">{{ number_format($log->used_qty, 2) }} used</span>
                <span class="text-2xl text-gray-400">=</span>
                <span class="text-{{ $diffColor }}-600 font-bold text-2xl">{{ number_format($difference, 2) }} difference</span>
            </div>
        </div>
    </div>

    <!-- Additional Information Card -->
    <div class="card mb-6">
        <h3 class="font-semibold text-gray-900 mb-4">📝 Additional Information</h3>
        <dl class="space-y-4">
            <div>
                <dt class="text-sm font-medium text-gray-600">Created At</dt>
                <dd class="text-gray-900">{{ $log->created_at->format('F d, Y g:i A') }}</dd>
            </div>

            <div>
                <dt class="text-sm font-medium text-gray-600">Last Updated</dt>
                <dd class="text-gray-900">{{ $log->updated_at->format('F d, Y g:i A') }}</dd>
            </div>
        </dl>
    </div>

    <!-- Actions -->
    <div class="flex items-center gap-4 mb-6">
        @if($canEdit)
        <a href="{{ route('material-usage.edit', $log) }}" class="btn-primary">
            ✎ Edit Record
        </a>
        @endif
        <a href="{{ route('material-usage.index', ['project_id' => $project->id]) }}" class="btn-secondary">
            ← Back to Logs
        </a>
        <a href="{{ route('projects.show', $project) }}" class="btn-secondary">
            ← Back to Project
        </a>
    </div>
</div>
@endsection
