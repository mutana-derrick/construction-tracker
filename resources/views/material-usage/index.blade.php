@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Header -->
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Material Usage Logs</h1>
            <p class="text-gray-600 mt-1">{{ $project->name }}</p>
        </div>
        <a href="{{ route('material-usage.create', ['project_id' => $project->id]) }}" class="btn-primary">
            + New Usage Log
        </a>
    </div>

    <!-- Flash Messages -->
    @if ($errors->any())
    <div class="rounded-md bg-red-50 p-4 mb-6">
        <div class="text-red-800 text-sm font-medium">Please fix the following errors:</div>
        <ul class="list-disc list-inside text-red-600 text-sm mt-2">
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    @if (session('success'))
    <div class="rounded-md bg-green-50 p-4 mb-6 border border-green-200">
        <p class="text-green-800">✓ {{ session('success') }}</p>
    </div>
    @endif

    @if (session('error'))
    <div class="rounded-md bg-red-50 p-4 mb-6 border border-red-200">
        <p class="text-red-800">✗ {{ session('error') }}</p>
    </div>
    @endif

    <!-- Summary Card -->
    <div class="card mb-6">
        <div class="grid grid-cols-4 gap-6">
            <div class="text-center">
                <p class="text-sm font-medium text-gray-600">Total Records</p>
                <p class="text-3xl font-bold text-primary-600 mt-2">{{ $logs->total() }}</p>
            </div>

            <div class="text-center">
                <p class="text-sm font-medium text-gray-600">Total Planned</p>
                <p class="text-3xl font-bold text-blue-600 mt-2">
                    @php
                        $totalPlanned = $logs->sum('planned_qty');
                    @endphp
                    {{ number_format($totalPlanned, 2) }}
                </p>
            </div>

            <div class="text-center">
                <p class="text-sm font-medium text-gray-600">Total Used</p>
                <p class="text-3xl font-bold text-orange-600 mt-2">
                    @php
                        $totalUsed = $logs->sum('used_qty');
                    @endphp
                    {{ number_format($totalUsed, 2) }}
                </p>
            </div>

            <div class="text-center">
                <p class="text-sm font-medium text-gray-600">Difference</p>
                <p class="text-3xl font-bold {{ ($totalPlanned - $totalUsed) >= 0 ? 'text-green-600' : 'text-red-600' }} mt-2">
                    @php
                        $totalDifference = $totalPlanned - $totalUsed;
                    @endphp
                    {{ number_format($totalDifference, 2) }}
                </p>
            </div>
        </div>
    </div>

    <!-- Logs Table -->
    <div class="card overflow-hidden">
        @if($logs->count() > 0)
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Date</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Material</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Activity</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Planned</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Used</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Difference</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Recorded By</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @foreach ($logs as $log)
                    @php
                        $diff = $log->planned_qty - $log->used_qty;
                        $diffColor = $diff >= 0 ? 'text-green-600' : 'text-red-600';
                    @endphp
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="text-sm font-semibold text-gray-900">{{ $log->date->format('M d, Y') }}</span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="text-sm font-medium text-gray-900">{{ $log->material_name }}</span>
                        </td>
                        <td class="px-6 py-4">
                            <span class="text-sm text-gray-600">{{ $log->activity }}</span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="text-sm font-semibold text-blue-600">{{ number_format($log->planned_qty, 2) }}</span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="text-sm font-semibold text-orange-600">{{ number_format($log->used_qty, 2) }}</span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="text-sm font-semibold {{ $diffColor }}">{{ number_format($diff, 2) }}</span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="text-sm text-gray-600">{{ $log->user->name }}</span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center gap-2">
                                <a href="{{ route('material-usage.show', $log) }}" class="inline-flex items-center px-3 py-1 rounded-md text-sm font-medium bg-blue-100 text-blue-700 hover:bg-blue-200 transition-colors">
                                    👁️ View
                                </a>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="bg-white px-6 py-4 border-t border-gray-200">
            {{ $logs->links() }}
        </div>
        @else
        <div class="text-center py-12">
            <p class="text-gray-600">No material usage logs recorded yet.</p>
            <a href="{{ route('material-usage.create', ['project_id' => $project->id]) }}" class="inline-block mt-4 btn-primary">
                + Create First Log
            </a>
        </div>
        @endif
    </div>

    <!-- Back Links -->
    <div class="mt-6 flex gap-4">
        <a href="{{ route('projects.show', $project) }}" class="btn-secondary">
            ← Back to Project
        </a>
    </div>
</div>
@endsection
