@extends('layouts.app')

@section('title', 'Daily Report')

@section('content')
<div class="py-12">
    <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 bg-white border-b border-gray-200">
                <h2 class="text-2xl font-bold text-gray-900 mb-6">📅 Daily Report</h2>

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
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="project_id" class="block text-sm font-medium text-gray-700 mb-2">
                                    Select Project
                                </label>
                                <select
                                    id="project_id"
                                    name="project_id"
                                    required
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent"
                                >
                                    <option value="">-- Choose a project --</option>
                                    @foreach ($projects as $project)
                                        <option value="{{ $project->id }}">{{ $project->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label for="date" class="block text-sm font-medium text-gray-700 mb-2">
                                    Select Date
                                </label>
                                <input
                                    type="date"
                                    id="date"
                                    name="date"
                                    required
                                    value="{{ old('date', date('Y-m-d')) }}"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent"
                                />
                            </div>
                        </div>

                        <div class="border-t border-gray-200 pt-6">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">Export Format</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <button
                                    type="submit"
                                    formaction="{{ route('reports.daily.excel') }}"
                                    formmethod="POST"
                                    class="px-4 py-2 bg-green-600 text-white font-medium rounded-lg hover:bg-green-700 transition-colors flex items-center justify-center"
                                >
                                    📊 Export to Excel
                                </button>
                                <button
                                    type="submit"
                                    formaction="{{ route('reports.daily.pdf') }}"
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
                <h3 class="text-lg font-semibold text-gray-900 mb-4">ℹ️ About Daily Reports</h3>
                <p class="text-gray-600 mb-3">
                    Daily reports provide a comprehensive overview of all construction activities for a selected date. The report includes:
                </p>
                <ul class="list-disc list-inside space-y-2 text-gray-600">
                    <li><strong>Equipment Logs</strong> - Hours worked and output produced</li>
                    <li><strong>Equipment Costs</strong> - Equipment usage costs</li>
                    <li><strong>Productivity Logs</strong> - Worker productivity metrics</li>
                    <li><strong>Labour Costs</strong> - Casual labour expenses</li>
                    <li><strong>Material Usage</strong> - Planned vs actual material consumption</li>
                    <li><strong>Material Costs</strong> - Material expenses</li>
                    <li><strong>Summary</strong> - Total costs and KPI metrics</li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection
