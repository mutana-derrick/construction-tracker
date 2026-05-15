@extends('layouts.app')

@section('title', 'Daily Report')
@section('page-title', 'Daily Report')

@section('content')
<div class="space-y-6">

    <div class="flex flex-col md:flex-row items-start md:items-center justify-between gap-4">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Daily Site Report</h1>
            <p class="text-gray-600 mt-1">
                Preview daily activity costs before exporting.
            </p>
        </div>

        <a href="{{ route('projects.show', $project) }}"
           class="inline-flex items-center gap-2 px-4 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-900 font-semibold rounded-lg transition-all shadow-sm">
            ← Back to Project
        </a>
    </div>

    <div class="bg-white rounded-xl border border-gray-200 p-6 shadow-sm">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div>
                <p class="text-sm font-semibold text-gray-600 uppercase tracking-wide">Project</p>
                <p class="text-lg font-bold text-gray-900 mt-1">{{ $project->name }}</p>
            </div>

            <div>
                <p class="text-sm font-semibold text-gray-600 uppercase tracking-wide">Location</p>
                <p class="text-lg font-bold text-gray-900 mt-1">{{ $project->location ?? 'N/A' }}</p>
            </div>

            <div>
                <p class="text-sm font-semibold text-gray-600 uppercase tracking-wide">Report Date</p>
                <p class="text-lg font-bold text-gray-900 mt-1">
                    {{ \Carbon\Carbon::parse($selectedDate)->format('F d, Y') }}
                </p>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-xl border border-gray-200 p-6 shadow-sm">
        <form method="GET" action="{{ route('reports.daily') }}" class="grid grid-cols-1 md:grid-cols-3 gap-4 items-end">
            <input type="hidden" name="project_id" value="{{ $project->id }}">

            <div class="md:col-span-2">
                <label for="date" class="block text-sm font-semibold text-gray-900 mb-2">Select Report Date</label>
                <input
                    type="date"
                    id="date"
                    name="date"
                    value="{{ $selectedDate }}"
                    required
                    class="w-full px-4 py-2.5 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-yellow-400 focus:border-transparent shadow-sm"
                >
            </div>

            <button type="submit"
                    class="px-5 py-2.5 bg-yellow-400 hover:bg-yellow-500 text-gray-900 font-semibold rounded-lg transition-all shadow-sm">
                Preview Report
            </button>
        </form>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <div class="bg-white rounded-xl border border-gray-200 p-5 shadow-sm">
            <p class="text-sm font-semibold text-gray-600 uppercase">Equipment</p>
            <p class="text-2xl font-bold text-gray-900 mt-3">Rwf {{ number_format($equipmentCostTotal, 0) }}</p>
        </div>

        <div class="bg-white rounded-xl border border-gray-200 p-5 shadow-sm">
            <p class="text-sm font-semibold text-gray-600 uppercase">Labour</p>
            <p class="text-2xl font-bold text-gray-900 mt-3">Rwf {{ number_format($labourCostTotal, 0) }}</p>
        </div>

        <div class="bg-white rounded-xl border border-gray-200 p-5 shadow-sm">
            <p class="text-sm font-semibold text-gray-600 uppercase">Material</p>
            <p class="text-2xl font-bold text-gray-900 mt-3">Rwf {{ number_format($materialCostTotal, 0) }}</p>
        </div>

        <div class="bg-yellow-50 rounded-xl border border-yellow-200 p-5 shadow-sm">
            <p class="text-sm font-semibold text-yellow-800 uppercase">Grand Total</p>
            <p class="text-2xl font-bold text-gray-900 mt-3">Rwf {{ number_format($totalCost, 0) }}</p>
        </div>
    </div>

    <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
        <div class="px-6 py-5 border-b border-gray-100 flex items-center justify-between">
            <div>
                <h2 class="text-lg font-bold text-gray-900">Activity Cost Preview</h2>
                <p class="text-sm text-gray-600 mt-1">Grouped by activity for the selected project and date.</p>
            </div>
        </div>

        @if($activities->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="border-b border-gray-200 bg-gray-50">
                            <th class="px-4 py-3 text-left font-semibold text-gray-900">Activity</th>
                            <th class="px-4 py-3 text-right font-semibold text-gray-900">Equipment Cost</th>
                            <th class="px-4 py-3 text-right font-semibold text-gray-900">Labour Cost</th>
                            <th class="px-4 py-3 text-right font-semibold text-gray-900">Material Cost</th>
                            <th class="px-4 py-3 text-right font-semibold text-gray-900">Total</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach($activities as $activity)
                            <tr class="hover:bg-gray-50">
                                <td class="px-4 py-4 font-medium text-gray-900">{{ $activity['activity'] }}</td>
                                <td class="px-4 py-4 text-right text-gray-700">Rwf {{ number_format($activity['equipment_cost'], 0) }}</td>
                                <td class="px-4 py-4 text-right text-gray-700">Rwf {{ number_format($activity['labour_cost'], 0) }}</td>
                                <td class="px-4 py-4 text-right text-gray-700">Rwf {{ number_format($activity['material_cost'], 0) }}</td>
                                <td class="px-4 py-4 text-right font-bold text-gray-900">Rwf {{ number_format($activity['total_cost'], 0) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr class="bg-yellow-50 border-t border-yellow-200">
                            <td class="px-4 py-4 font-bold text-gray-900">Total</td>
                            <td class="px-4 py-4 text-right font-bold text-gray-900">Rwf {{ number_format($equipmentCostTotal, 0) }}</td>
                            <td class="px-4 py-4 text-right font-bold text-gray-900">Rwf {{ number_format($labourCostTotal, 0) }}</td>
                            <td class="px-4 py-4 text-right font-bold text-gray-900">Rwf {{ number_format($materialCostTotal, 0) }}</td>
                            <td class="px-4 py-4 text-right font-bold text-gray-900">Rwf {{ number_format($totalCost, 0) }}</td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        @else
            <div class="p-10 text-center">
                <p class="text-gray-600">No cost records found for this date.</p>
            </div>
        @endif
    </div>

    <div class="bg-white rounded-xl border border-gray-200 p-6 shadow-sm">
        <h3 class="text-lg font-bold text-gray-900 mb-4">Export Report</h3>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <form method="POST" action="{{ route('reports.daily.excel') }}">
                @csrf
                <input type="hidden" name="project_id" value="{{ $project->id }}">
                <input type="hidden" name="date" value="{{ $selectedDate }}">
                <button type="submit"
                        class="w-full px-4 py-2.5 bg-green-600 hover:bg-green-700 text-white font-semibold rounded-lg transition-all shadow-sm">
                    Export Excel
                </button>
            </form>

            <form method="POST" action="{{ route('reports.daily.pdf') }}">
                @csrf
                <input type="hidden" name="project_id" value="{{ $project->id }}">
                <input type="hidden" name="date" value="{{ $selectedDate }}">
                <button type="submit"
                        class="w-full px-4 py-2.5 bg-red-600 hover:bg-red-700 text-white font-semibold rounded-lg transition-all shadow-sm">
                    Export PDF
                </button>
            </form>
        </div>
    </div>

</div>
@endsection