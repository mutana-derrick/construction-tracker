@extends('layouts.app')

@section('title', 'Monthly Report')

@section('content')
<div class="py-12">
    <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 bg-white border-b border-gray-200">
                <h2 class="text-2xl font-bold text-gray-900 mb-6">📆 Monthly Report</h2>

                @if (session('success'))
                    <div class="mb-4 p-4 bg-green-50 border border-green-200 text-green-800 rounded-lg">
                        {{ session('success') }}
                    </div>
                @endif

                @if ($projects->count() == 0)
                    <div class="p-4 bg-yellow-50 border border-yellow-200 text-yellow-800 rounded-lg">
                        No projects available. Please create a project first.
                    </div>
                @else
                    <form class="space-y-6">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div>
                                <label for="project_id" class="block text-sm font-medium text-gray-700 mb-2">
                                    Select Project
                                </label>
                                <select
                                    id="project_id"
                                    name="project_id"
                                    required
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-yellow-400 focus:border-transparent shadow-sm"
                                >
                                    <option value="">-- Choose a project --</option>
                                    @foreach ($projects as $project)
                                        <option value="{{ $project->id }}">{{ $project->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label for="month" class="block text-sm font-medium text-gray-700 mb-2">
                                    Select Month
                                </label>
                                <select
                                    id="month"
                                    name="month"
                                    required
                                    value="{{ old('month', date('m')) }}"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-yellow-400 focus:border-transparent shadow-sm"
                                >
                                    @for ($i = 1; $i <= 12; $i++)
                                        <option value="{{ $i }}" {{ date('m') == $i ? 'selected' : '' }}>
                                            {{ date('F', mktime(0, 0, 0, $i, 1)) }}
                                        </option>
                                    @endfor
                                </select>
                            </div>

                            <div>
                                <label for="year" class="block text-sm font-medium text-gray-700 mb-2">
                                    Select Year
                                </label>
                                <input
                                    type="number"
                                    id="year"
                                    name="year"
                                    min="2020"
                                    max="2099"
                                    required
                                    value="{{ old('year', date('Y')) }}"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-yellow-400 focus:border-transparent shadow-sm"
                                />
                            </div>
                        </div>

                        <div class="border-t border-gray-200 pt-6">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">Export Format</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <button
                                    type="submit"
                                    formaction="{{ route('reports.monthly.excel') }}"
                                    formmethod="POST"
                                    class="px-4 py-2 bg-green-600 text-white font-medium rounded-lg hover:bg-green-700 transition-colors flex items-center justify-center"
                                >
                                    📊 Export to Excel
                                </button>
                                <button
                                    type="submit"
                                    formaction="{{ route('reports.monthly.pdf') }}"
                                    formmethod="POST"
                                    class="px-4 py-2 bg-red-600 text-white font-medium rounded-lg hover:bg-red-700 transition-colors flex items-center justify-center"
                                >
                                    📄 Export to PDF
                                </button>
                            </div>
                        </div>

                        @csrf
                    </form>
                @endif
            </div>
        </div>

        <div class="mt-8 bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 bg-white border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">ℹ️ About Monthly Reports</h3>
                <p class="text-gray-600 mb-3">
                    Monthly reports provide an aggregated summary of all construction activities for a selected month. The report includes:
                </p>
                <ul class="list-disc list-inside space-y-2 text-gray-600">
                    <li><strong>Equipment Summary</strong> - Equipment usage totals grouped by type</li>
                    <li><strong>Labour Summary</strong> - Labour costs grouped by classification</li>
                    <li><strong>Material Summary</strong> - Material consumption grouped by material type</li>
                    <li><strong>Monthly Statistics</strong> - Total costs and aggregated KPIs</li>
                    <li><strong>Average Metrics</strong> - Per-unit average costs and consumption rates</li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection
