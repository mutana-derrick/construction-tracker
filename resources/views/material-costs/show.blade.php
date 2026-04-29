@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Header -->
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Material Cost Log Details</h1>
            <p class="text-gray-600 mt-1">{{ $project->name }}</p>
        </div>
        @if($canEdit)
        <a href="{{ route('material-costs.edit', $log) }}" class="inline-flex items-center gap-2 px-4 py-2.5 bg-yellow-400 hover:bg-yellow-500 text-gray-900 font-semibold rounded-lg transition-all duration-200 shadow-sm hover:shadow-md">
            ✎ Edit Record
        </a>
        @endif
    </div>

    <!-- 5-Minute Edit Status -->
    @php
        $editDeadline = $log->created_at->copy()->addMinutes(5);
        $isEditable = $editDeadline->isFuture();
        $minutesLeft = max(0, now()->diffInMinutes($editDeadline, false));
    @endphp

    <div class="mb-6 p-4 rounded-lg border 
        {{ $isEditable ? 'bg-green-50 border-green-200' : 'bg-red-50 border-red-200' }}">

        <p class="text-sm font-semibold 
            {{ $isEditable ? 'text-green-700' : 'text-red-700' }}">
            
            {{ $isEditable ? '⏱️ Edit Window Status: Active' : '⛔ Edit Window Expired' }}
        </p>

        <p class="text-sm mt-1 text-gray-600">
            Created at {{ $log->created_at->format('g:i A') }} • 
            {{ $isEditable ? $minutesLeft . ' minutes remaining' : 'No longer editable' }}
        </p>

    </div>

    <!-- Material Information Card -->
    <div class="bg-white rounded-xl border border-gray-200 p-6 mb-6 shadow-sm hover:shadow-md transition-shadow">
        <div class="grid grid-cols-2 gap-8">
            <div>
                <h3 class="font-semibold text-gray-900 mb-4">📦 Material Information</h3>
                <dl class="space-y-3">
                    <div>
                        <dt class="text-sm font-medium text-gray-600">Material Name</dt>
                        <dd class="text-lg font-semibold text-gray-900">{{ $log->material_name }}</dd>
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

    <!-- Cost Analysis Card -->
    <div class="bg-white rounded-xl border border-gray-200 p-6 mb-6 shadow-sm hover:shadow-md transition-shadow">
        <h3 class="font-semibold text-gray-900 mb-4">💰 Material Cost Analysis</h3>
        <div class="grid grid-cols-3 gap-6">
            <div class="bg-yellow-50 rounded-lg p-6 border border-yellow-200 shadow-sm">
                <p class="text-sm font-medium text-gray-600">Quantity Used</p>
                <p class="text-3xl font-bold text-yellow-600 mt-2">{{ number_format($log->used_qty, 2) }}</p>
            </div>

            <div class="bg-yellow-50 rounded-lg p-6 border border-yellow-200 shadow-sm">
                <p class="text-sm font-medium text-gray-600">Cost per Item</p>
                <p class="text-3xl font-bold text-yellow-700 mt-2">Rwf {{ number_format($log->cost_per_item, 2) }}</p>
            </div>

            <div class="bg-yellow-100 rounded-lg p-6 border border-yellow-300 shadow-sm">
                <p class="text-sm font-medium text-gray-600">Total Cost</p>
                <p class="text-3xl font-bold text-yellow-700 mt-2">Rwf {{ number_format($log->total, 2) }}</p>
            </div>
        </div>

        <!-- Cost Calculation Display -->
        <div class="mt-6 bg-yellow-50 border border-yellow-200 rounded-lg p-6 shadow-sm">
            <h4 class="font-semibold text-gray-900 mb-2">Cost Calculation</h4>
            <div class="flex items-center justify-center gap-4 text-lg">
                <span class="text-gray-900">{{ number_format($log->used_qty, 2) }} qty</span>
                <span class="text-2xl text-gray-400">×</span>
                <span class="text-yellow-600 font-semibold">Rwf {{ number_format($log->cost_per_item, 2) }}/item</span>
                <span class="text-2xl text-gray-400">=</span>
                <span class="text-yellow-700 font-bold text-2xl">Rwf {{ number_format($log->total, 2) }}</span>
            </div>
        </div>
    </div>

    <!-- Additional Information Card -->
    <div class="bg-white rounded-xl border border-gray-200 p-6 mb-6 shadow-sm hover:shadow-md transition-shadow">
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
        <a href="{{ route('material-costs.edit', $log) }}" class="bg-yellow-400 hover:bg-yellow-500 text-gray-900 font-semibold rounded-lg shadow-sm hover:shadow-md transition-all px-4 py-2">
            ✎ Edit Record
        </a>
        @endif
        <a href="{{ route('material-costs.index', ['project_id' => $project->id]) }}" class="bg-gray-100 hover:bg-gray-200 text-gray-900 font-semibold rounded-lg shadow-sm px-4 py-2">
            ← Back to Logs
        </a>
        <a href="{{ route('projects.show', $project) }}" class="bg-gray-100 hover:bg-gray-200 text-gray-900 font-semibold rounded-lg shadow-sm px-4 py-2">
            ← Back to Project
        </a>
    </div>
</div>
@endsection
