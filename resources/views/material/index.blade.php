@extends('layouts.app')

@section('page-title', 'Material Management')

@section('content')
<div class="space-y-6" x-data="{ usageOpen: true, costOpen: false }">

    <!-- Header -->
    <div class="flex flex-col md:flex-row items-start md:items-center justify-between gap-4">
        <div>
            <div class="flex items-center gap-3 mb-2">
                <svg class="w-8 h-8 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10l8-4"></path>
                </svg>
                <h1 class="text-3xl font-bold text-gray-900">Material Management</h1>
            </div>
            <p class="text-gray-600 mt-2">
                Project: <span class="font-semibold">{{ $project->name }}</span>
            </p>
        </div>

        <a href="{{ route('projects.show', $project) }}"
           class="inline-flex items-center gap-2 px-4 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-900 font-semibold rounded-lg transition-all duration-200 shadow-sm hover:shadow-md whitespace-nowrap">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
            </svg>
            Back to Project
        </a>
    </div>

    <!-- Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-white rounded-xl border border-gray-200 p-6 hover:shadow-lg hover:border-yellow-300 transition-all duration-200">
            <p class="text-sm font-semibold text-gray-600 uppercase tracking-wide">Usage Records</p>
            <p class="text-3xl font-bold text-gray-900 mt-4">{{ $usages->total() }}</p>
        </div>

        <div class="bg-white rounded-xl border border-gray-200 p-6 hover:shadow-lg hover:border-yellow-300 transition-all duration-200">
            <p class="text-sm font-semibold text-gray-600 uppercase tracking-wide">Cost Records</p>
            <p class="text-3xl font-bold text-gray-900 mt-4">{{ $costs->total() }}</p>
        </div>

        <div class="bg-white rounded-xl border border-gray-200 p-6 hover:shadow-lg hover:border-yellow-300 transition-all duration-200">
            <p class="text-sm font-semibold text-gray-600 uppercase tracking-wide">Total Material Cost</p>
            <p class="text-3xl font-bold text-gray-900 mt-4">
                Rwf {{ number_format($totalMaterialCost, 0) }}
            </p>
        </div>
    </div>

    <!-- Material Usage -->
    <div class="bg-white rounded-xl border border-gray-200 shadow-sm hover:shadow-md transition-shadow">
        <button type="button" @click="usageOpen = !usageOpen" class="w-full px-6 py-5 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-yellow-100 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-yellow-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10"></path>
                    </svg>
                </div>
                <div class="text-left">
                    <h2 class="text-lg font-bold text-gray-900">Material Usage Records</h2>
                    <p class="text-sm text-gray-600">Track planned quantity, used quantity, and material differences</p>
                </div>
            </div>

            <svg class="w-5 h-5 text-gray-500 transition-transform" :class="{ 'rotate-180': usageOpen }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
            </svg>
        </button>

        <div x-show="usageOpen" x-transition class="border-t border-gray-100">
            <div class="px-6 py-4 flex justify-end">
                @can('create', \App\Models\MaterialUsage::class)
                    <a href="{{ route('material-usage.create', ['project_id' => $project->id]) }}"
                       class="inline-flex items-center gap-2 px-4 py-2.5 bg-yellow-400 hover:bg-yellow-500 text-gray-900 font-semibold rounded-lg transition-all duration-200 shadow-sm hover:shadow-md">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                        </svg>
                        Add Usage Record
                    </a>
                @endcan
            </div>

            @if($usages->count() > 0)
                <div class="overflow-x-auto px-6 pb-6">
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="border-b border-gray-200">
                                <th class="px-4 py-3 text-left font-semibold text-gray-900">Date</th>
                                <th class="px-4 py-3 text-left font-semibold text-gray-900">Material</th>
                                <th class="px-4 py-3 text-left font-semibold text-gray-900">Activity</th>
                                <th class="px-4 py-3 text-left font-semibold text-gray-900">Planned Qty</th>
                                <th class="px-4 py-3 text-left font-semibold text-gray-900">Used Qty</th>
                                <th class="px-4 py-3 text-left font-semibold text-gray-900">Difference</th>
                                <th class="px-4 py-3 text-right font-semibold text-gray-900">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @foreach($usages as $usage)
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="px-4 py-4 text-gray-600">{{ $usage->date->format('M d, Y') }}</td>
                                    <td class="px-4 py-4 font-medium text-gray-900">{{ $usage->material_name }}</td>
                                    <td class="px-4 py-4 text-gray-600">{{ $usage->activity }}</td>
                                    <td class="px-4 py-4 text-gray-600">{{ number_format($usage->planned_qty, 2) }}</td>
                                    <td class="px-4 py-4 text-gray-600">{{ number_format($usage->used_qty, 2) }}</td>
                                    <td class="px-4 py-4 font-medium text-gray-900">
                                        {{ number_format($usage->planned_qty - $usage->used_qty, 2) }}
                                    </td>
                                    <td class="px-4 py-4 text-right">
                                        <div class="inline-flex items-center gap-3">
                                            <a href="{{ route('material-usage.show', $usage) }}"
                                               class="text-yellow-600 hover:text-yellow-700 font-medium">
                                                View
                                            </a>

                                            @can('update', $usage)
                                                <a href="{{ route('material-usage.edit', $usage) }}"
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
                        {{ $usages->appends(['project_id' => $project->id])->links() }}
                    </div>
                </div>
            @else
                <div class="px-6 pb-8 text-center">
                    <p class="text-gray-600 mb-4">No material usage records created yet.</p>
                    @can('create', \App\Models\MaterialUsage::class)
                        <a href="{{ route('material-usage.create', ['project_id' => $project->id]) }}"
                           class="inline-flex items-center gap-2 px-4 py-2 bg-yellow-50 text-yellow-700 hover:bg-yellow-100 rounded-lg font-medium transition-colors text-sm">
                            Add first usage record
                        </a>
                    @endcan
                </div>
            @endif
        </div>
    </div>

    <!-- Material Cost -->
    <div class="bg-white rounded-xl border border-gray-200 shadow-sm hover:shadow-md transition-shadow">
        <button type="button" @click="costOpen = !costOpen" class="w-full px-6 py-5 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-yellow-100 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-yellow-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8V7m0 10v-1m9-4a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div class="text-left">
                    <h2 class="text-lg font-bold text-gray-900">Material Cost Records</h2>
                    <p class="text-sm text-gray-600">Track used quantity, cost per item, and total material cost</p>
                </div>
            </div>

            <svg class="w-5 h-5 text-gray-500 transition-transform" :class="{ 'rotate-180': costOpen }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
            </svg>
        </button>

        <div x-show="costOpen" x-transition class="border-t border-gray-100">
            <div class="px-6 py-4 flex justify-end">
                @can('create', \App\Models\MaterialCost::class)
                    <a href="{{ route('material-costs.create', ['project_id' => $project->id]) }}"
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
                                <th class="px-4 py-3 text-left font-semibold text-gray-900">Material</th>
                                <th class="px-4 py-3 text-left font-semibold text-gray-900">Activity</th>
                                <th class="px-4 py-3 text-left font-semibold text-gray-900">Used Qty</th>
                                <th class="px-4 py-3 text-left font-semibold text-gray-900">Cost / Item</th>
                                <th class="px-4 py-3 text-left font-semibold text-gray-900">Total</th>
                                <th class="px-4 py-3 text-right font-semibold text-gray-900">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @foreach($costs as $cost)
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="px-4 py-4 text-gray-600">{{ $cost->date->format('M d, Y') }}</td>
                                    <td class="px-4 py-4 font-medium text-gray-900">{{ $cost->material_name }}</td>
                                    <td class="px-4 py-4 text-gray-600">{{ $cost->activity ?? 'Unassigned Activity' }}</td>
                                    <td class="px-4 py-4 text-gray-600">{{ number_format($cost->used_qty, 2) }}</td>
                                    <td class="px-4 py-4 text-gray-600">Rwf {{ number_format($cost->cost_per_item, 0) }}</td>
                                    <td class="px-4 py-4 font-semibold text-gray-900">Rwf {{ number_format($cost->total, 0) }}</td>
                                    <td class="px-4 py-4 text-right">
                                        <div class="inline-flex items-center gap-3">
                                            <a href="{{ route('material-costs.show', $cost) }}"
                                               class="text-yellow-600 hover:text-yellow-700 font-medium">
                                                View
                                            </a>

                                            @can('update', $cost)
                                                <a href="{{ route('material-costs.edit', $cost) }}"
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
                    <p class="text-gray-600 mb-4">No material cost records created yet.</p>
                    @can('create', \App\Models\MaterialCost::class)
                        <a href="{{ route('material-costs.create', ['project_id' => $project->id]) }}"
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