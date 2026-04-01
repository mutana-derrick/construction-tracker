@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Header -->
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Equipment Cost Record Details</h1>
            <p class="text-gray-600 mt-1">{{ $project->name }}</p>
        </div>
        @if($canEdit)
        <a href="{{ route('equipment-costs.edit', $cost) }}" class="btn-primary">
            ✎ Edit Record
        </a>
        @endif
    </div>

    <!-- 5-Minute Edit Status -->
    @php
        $minutesElapsed = now()->diffInMinutes($cost->created_at);
        $canStillEdit = $minutesElapsed <= 5;
    @endphp

    <div class="card mb-6">
        <div class="flex items-center justify-between">
            <div>
                <h3 class="font-semibold text-gray-900">⏱️ Edit Window Status</h3>
                <p class="text-sm text-gray-600 mt-1">Created at {{ $cost->created_at->format('g:i A') }} • {{ $minutesElapsed }} minutes ago</p>
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
    <div class="card mb-6">
        <div class="grid grid-cols-2 gap-8">
            <div>
                <h3 class="font-semibold text-gray-900 mb-4">🚜 Equipment Information</h3>
                <dl class="space-y-3">
                    <div>
                        <dt class="text-sm font-medium text-gray-600">Equipment Type</dt>
                        <dd class="text-lg font-semibold text-gray-900">{{ $cost->equipment_type }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-600">Activity</dt>
                        <dd class="text-gray-900">{{ $cost->activity }}</dd>
                    </div>
                </dl>
            </div>

            <div>
                <h3 class="font-semibold text-gray-900 mb-4">📋 Record Information</h3>
                <dl class="space-y-3">
                    <div>
                        <dt class="text-sm font-medium text-gray-600">Date Recorded</dt>
                        <dd class="text-lg font-semibold text-gray-900">{{ \Carbon\Carbon::parse($cost->date)->format('F d, Y') }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-600">Recorded By</dt>
                        <dd class="text-gray-900">{{ $cost->user->name }} ({{ $cost->user->email }})</dd>
                    </div>
                </dl>
            </div>
        </div>
    </div>

    <!-- Cost Analysis Card -->
    <div class="card mb-6">
        <h3 class="font-semibold text-gray-900 mb-4">💰 Cost Analysis</h3>
        <div class="grid grid-cols-3 gap-6">
            <div class="bg-gray-50 rounded-lg p-6 border border-gray-200">
                <p class="text-sm font-medium text-gray-600">Units Done</p>
                <p class="text-3xl font-bold text-gray-900 mt-2">{{ number_format($cost->units_done, 2) }}</p>
            </div>

            <div class="bg-gray-50 rounded-lg p-6 border border-gray-200">
                <p class="text-sm font-medium text-gray-600">Cost per Unit</p>
                <p class="text-3xl font-bold text-primary-600 mt-2">${{ number_format($cost->cost_per_unit, 2) }}</p>
            </div>

            <div class="bg-green-50 rounded-lg p-6 border border-green-200">
                <p class="text-sm font-medium text-gray-600">Total Cost</p>
                <p class="text-3xl font-bold text-green-600 mt-2">${{ number_format($cost->total_cost, 2) }}</p>
            </div>
        </div>

        <!-- Cost Calculation Display -->
        <div class="mt-6 bg-blue-50 border border-blue-200 rounded-lg p-6">
            <h4 class="font-semibold text-gray-900 mb-2">Cost Calculation</h4>
            <div class="flex items-center justify-center gap-4 text-lg">
                <span class="text-gray-900">{{ number_format($cost->units_done, 2) }} units</span>
                <span class="text-2xl text-gray-400">×</span>
                <span class="text-primary-600 font-semibold">${{ number_format($cost->cost_per_unit, 2) }}/unit</span>
                <span class="text-2xl text-gray-400">=</span>
                <span class="text-green-600 font-bold text-2xl">${{ number_format($cost->total_cost, 2) }}</span>
            </div>
        </div>
    </div>

    <!-- Additional Information Card -->
    <div class="card mb-6">
        <h3 class="font-semibold text-gray-900 mb-4">📝 Additional Information</h3>
        <dl class="space-y-4">
            @if($cost->comment)
            <div>
                <dt class="text-sm font-medium text-gray-600">Comments</dt>
                <dd class="text-gray-900 mt-1 p-3 bg-gray-50 rounded border border-gray-200">{{ $cost->comment }}</dd>
            </div>
            @else
            <p class="text-gray-500 italic">No additional comments</p>
            @endif

            <div>
                <dt class="text-sm font-medium text-gray-600">Created At</dt>
                <dd class="text-gray-900">{{ $cost->created_at->format('F d, Y g:i A') }}</dd>
            </div>

            <div>
                <dt class="text-sm font-medium text-gray-600">Last Updated</dt>
                <dd class="text-gray-900">{{ $cost->updated_at->format('F d, Y g:i A') }}</dd>
            </div>
        </dl>
    </div>

    <!-- Actions -->
    <div class="flex items-center gap-4 mb-6">
        @if($canEdit)
        <a href="{{ route('equipment-costs.edit', $cost) }}" class="btn-primary">
            ✎ Edit Record
        </a>
        @endif
        <a href="{{ route('equipment-costs.index', ['project_id' => $project->id]) }}" class="btn-secondary">
            ← Back to Cost Records
        </a>
        <a href="{{ route('projects.show', $project) }}" class="btn-secondary">
            ← Back to Project
        </a>
    </div>
</div>
@endsection
