@extends('layouts.app')

@section('page-title', 'Equipment Management')

@section('content')
<div class="space-y-6" x-data="{ usageOpen: true, costOpen: false }">

    <!-- Header -->
    <div class="flex flex-col md:flex-row items-start md:items-center justify-between gap-4">
        <div>
            <div class="flex items-center gap-3 mb-2">
                <svg class="w-8 h-8 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                </svg>
                <h1 class="text-3xl font-bold text-gray-900">Equipment Management</h1>
            </div>
            <p class="text-gray-600 mt-2">Project: <span class="font-semibold">{{ $project->name }}</span></p>
        </div>

        <a href="{{ route('projects.show', $project) }}" class="inline-flex items-center gap-2 px-4 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-900 font-semibold rounded-lg transition-all duration-200 shadow-sm hover:shadow-md whitespace-nowrap">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
            </svg>
            Back to Project
        </a>
    </div>

    <!-- Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-white rounded-xl border border-gray-200 p-6 hover:shadow-lg hover:border-yellow-300 transition-all duration-200">
            <p class="text-sm font-semibold text-gray-600 uppercase tracking-wide">Usage Logs</p>
            <p class="text-3xl font-bold text-gray-900 mt-4">{{ $logs->total() }}</p>
        </div>

        <div class="bg-white rounded-xl border border-gray-200 p-6 hover:shadow-lg hover:border-yellow-300 transition-all duration-200">
            <p class="text-sm font-semibold text-gray-600 uppercase tracking-wide">Cost Records</p>
            <p class="text-3xl font-bold text-gray-900 mt-4">{{ $costs->total() }}</p>
        </div>

        <div class="bg-white rounded-xl border border-gray-200 p-6 hover:shadow-lg hover:border-yellow-300 transition-all duration-200">
            <p class="text-sm font-semibold text-gray-600 uppercase tracking-wide">Total Equipment Cost</p>
            <p class="text-3xl font-bold text-gray-900 mt-4">Rwf {{ number_format($totalEquipmentCost, 0) }}</p>
        </div>
    </div>

    <!-- Equipment Usage Logs -->
    <div class="bg-white rounded-xl border border-gray-200 shadow-sm hover:shadow-md transition-shadow">
        <button type="button" @click="usageOpen = !usageOpen" class="w-full px-6 py-5 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-yellow-100 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-yellow-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div class="text-left">
                    <h2 class="text-lg font-bold text-gray-900">Equipment Usage Logs</h2>
                    <p class="text-sm text-gray-600">Track equipment activity, output, working hours, and productivity</p>
                </div>
            </div>

            <svg class="w-5 h-5 text-gray-500 transition-transform" :class="{ 'rotate-180': usageOpen }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
            </svg>
        </button>

        <div x-show="usageOpen" x-transition class="border-t border-gray-100">
            <div class="px-6 py-4 flex justify-end">
                               @can('create', \App\Models\EquipmentLog::class)
                    <a href="{{ route('equipment-logs.create', ['project_id' => $project->id]) }}"
                       class="inline-flex items-center gap-2 px-4 py-2.5 bg-yellow-400 hover:bg-yellow-500 text-gray-900 font-semibold rounded-lg transition-all duration-200 shadow-sm hover:shadow-md">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                        </svg>
                        Add Usage Log
                    </a>
                @endcan
            </div>

            @if($logs->count() > 0)
                <div class="overflow-x-auto px-6 pb-6">
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="border-b border-gray-200">
                                <th class="px-4 py-3 text-left font-semibold text-gray-900">Date</th>
                                <th class="px-4 py-3 text-left font-semibold text-gray-900">Equipment</th>
                                <th class="px-4 py-3 text-left font-semibold text-gray-900">Activity</th>
                                <th class="px-4 py-3 text-left font-semibold text-gray-900">Planned</th>
                                <th class="px-4 py-3 text-left font-semibold text-gray-900">Actual</th>
                                <th class="px-4 py-3 text-left font-semibold text-gray-900">Hours</th>
                                <th class="px-4 py-3 text-right font-semibold text-gray-900">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @foreach($logs as $log)
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="px-4 py-4 text-gray-600">{{ $log->date->format('M d, Y') }}</td>
                                    <td class="px-4 py-4 font-medium text-gray-900">
                                        {{ $log->equipment_type }}
                                        <div class="text-xs text-gray-500">{{ $log->equipment_id }}</div>
                                    </td>
                                    <td class="px-4 py-4 text-gray-600">{{ $log->activity }}</td>
                                    <td class="px-4 py-4 text-gray-600">{{ number_format($log->planned_output, 2) }}</td>
                                    <td class="px-4 py-4 text-gray-600">{{ number_format($log->actual_output, 2) }}</td>
                                    <td class="px-4 py-4 text-gray-600">{{ number_format($log->working_hours, 2) }}</td>
                                    <td class="px-4 py-4 text-right">
                                        <div class="inline-flex items-center gap-3">
                                            <a href="{{ route('equipment-logs.show', $log) }}"
                                               class="text-yellow-600 hover:text-yellow-700 font-medium">
                                                View
                                            </a>

                                            @can('update', $log)
                                                <a href="{{ route('equipment-logs.edit', $log) }}"
                                                   class="text-blue-600 hover:text-blue-700 font-medium">
                                                    Edit
                                                </a>
                                            @endcan
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                    <div class="mt-6">
                        {{ $logs->appends(['project_id' => $project->id])->links() }}
                    </div>
                </div>
            @else
                <div class="px-6 pb-8 text-center">
                    <p class="text-gray-600 mb-4">No equipment usage logs recorded yet.</p>
                    @can('create', \App\Models\EquipmentLog::class)
                        <a href="{{ route('equipment-logs.create', ['project_id' => $project->id]) }}"
                           class="inline-flex items-center gap-2 px-4 py-2 bg-yellow-50 text-yellow-700 hover:bg-yellow-100 rounded-lg font-medium transition-colors text-sm">
                            Add first usage log
                        </a>
                    @endcan
                </div>
            @endif
        </div>
    </div>

    <!-- Equipment Cost Records -->
    <div class="bg-white rounded-xl border border-gray-200 shadow-sm hover:shadow-md transition-shadow">
        <button type="button" @click="costOpen = !costOpen" class="w-full px-6 py-5 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-yellow-100 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-yellow-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8V7m0 10v-1m9-4a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div class="text-left">
                    <h2 class="text-lg font-bold text-gray-900">Equipment Cost Records</h2>
                    <p class="text-sm text-gray-600">Record completed units, cost per unit, and total equipment cost</p>
                </div>
            </div>

            <svg class="w-5 h-5 text-gray-500 transition-transform" :class="{ 'rotate-180': costOpen }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
            </svg>
        </button>

        <div x-show="costOpen" x-transition class="border-t border-gray-100">
            <div class="px-6 py-4 flex justify-end">
                @can('create', \App\Models\EquipmentCost::class)
                    <a href="{{ route('equipment-costs.create', ['project_id' => $project->id]) }}"
                       class="inline-flex items-center gap-2 px-4 py-2.5 bg-yellow-400 hover:bg-yellow-500 text-gray-900 font-semibold rounded-lg transition-all duration-200 shadow-sm hover:shadow-md">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                        </svg>
                        Add Cost Record
                    </a>
                @endcan
            </div>

            @if($costs->count() > 0)
                <div class="overflow-x-auto px-6 pb-6">
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="border-b border-gray-200">
                                <th class="px-4 py-3 text-left font-semibold text-gray-900">Date</th>
                                <th class="px-4 py-3 text-left font-semibold text-gray-900">Equipment</th>
                                <th class="px-4 py-3 text-left font-semibold text-gray-900">Activity</th>
                                <th class="px-4 py-3 text-left font-semibold text-gray-900">Units Done</th>
                                <th class="px-4 py-3 text-left font-semibold text-gray-900">Cost / Unit</th>
                                <th class="px-4 py-3 text-left font-semibold text-gray-900">Total Cost</th>
                                <th class="px-4 py-3 text-right font-semibold text-gray-900">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @foreach($costs as $cost)
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="px-4 py-4 text-gray-600">{{ $cost->date->format('M d, Y') }}</td>
                                    <td class="px-4 py-4 font-medium text-gray-900">{{ $cost->equipment_type }}</td>
                                    <td class="px-4 py-4 text-gray-600">{{ $cost->activity }}</td>
                                    <td class="px-4 py-4 text-gray-600">{{ number_format($cost->units_done, 2) }}</td>
                                    <td class="px-4 py-4 text-gray-600">Rwf {{ number_format($cost->cost_per_unit, 0) }}</td>
                                    <td class="px-4 py-4 font-semibold text-gray-900">Rwf {{ number_format($cost->total_cost, 0) }}</td>
                                    <td class="px-4 py-4 text-right">
                                        <div class="inline-flex items-center gap-3">
                                            <a href="{{ route('equipment-costs.show', $cost) }}"
                                               class="text-yellow-600 hover:text-yellow-700 font-medium">
                                                View
                                            </a>

                                            @can('update', $cost)
                                                <a href="{{ route('equipment-costs.edit', $cost) }}"
                                                   class="text-blue-600 hover:text-blue-700 font-medium">
                                                    Edit
                                                </a>
                                            @endcan
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                    <div class="mt-6">
                        {{ $costs->appends(['project_id' => $project->id])->links() }}
                    </div>
                </div>
            @else
                <div class="px-6 pb-8 text-center">
                    <p class="text-gray-600 mb-4">No equipment cost records created yet.</p>
                    @can('create', \App\Models\EquipmentCost::class)
                        <a href="{{ route('equipment-costs.create', ['project_id' => $project->id]) }}"
                           class="inline-flex items-center gap-2 px-4 py-2 bg-yellow-50 text-yellow-700 hover:bg-yellow-100 rounded-lg font-medium transition-colors text-sm">
                            Add first cost record
                        </a>
                    @endcan
                </div>
            @endif
        </div>
    </div>

</div>
@endsection