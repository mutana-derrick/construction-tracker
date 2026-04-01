@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Header -->
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Equipment Cost Records</h1>
            <p class="text-gray-600 mt-1">{{ $project->name }}</p>
        </div>
        @can('create', App\Models\EquipmentCost::class)
        <a href="{{ route('equipment-costs.create', ['project_id' => $project->id]) }}" class="btn-primary">
            + New Cost Record
        </a>
        @endcan
    </div>

    <!-- Flash Messages -->
    @if ($message = Session::get('success'))
    <div class="alert alert-success mb-6">
        {{ $message }}
    </div>
    @endif

    @if ($message = Session::get('error'))
    <div class="alert alert-error mb-6">
        {{ $message }}
    </div>
    @endif

    <!-- Equipment Cost Records Table -->
    <div class="card">
        @if($costs->count() > 0)
        <div class="table-container">
            <table class="table">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Equipment Type</th>
                        <th>Activity</th>
                        <th>Units Done</th>
                        <th>Cost per Unit</th>
                        <th>Total Cost</th>
                        <th>Recorded By</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($costs as $cost)
                    <tr>
                        <td class="whitespace-nowrap font-medium">{{ \Carbon\Carbon::parse($cost->date)->format('M d, Y') }}</td>
                        <td>{{ $cost->equipment_type }}</td>
                        <td>{{ Str::limit($cost->activity, 30) }}</td>
                        <td class="text-right">{{ number_format($cost->units_done, 2) }}</td>
                        <td class="text-right font-semibold text-primary-600">${{ number_format($cost->cost_per_unit, 2) }}</td>
                        <td class="text-right font-bold text-lg">
                            <span class="text-green-600">${{ number_format($cost->total_cost, 2) }}</span>
                        </td>
                        <td class="text-sm">{{ $cost->user->name }}</td>
                        <td class="text-right space-x-2">
                            <a href="{{ route('equipment-costs.show', $cost) }}" class="text-blue-600 hover:text-blue-900 font-medium text-sm">
                                View
                            </a>
                            @can('update', $cost)
                            <a href="{{ route('equipment-costs.edit', $cost) }}" class="text-primary-600 hover:text-primary-900 font-medium text-sm">
                                Edit
                            </a>
                            @else
                            <span class="text-gray-400 text-sm">Edit</span>
                            @endcan
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="mt-6">
            {{ $costs->links() }}
        </div>
        @else
        <div class="text-center py-12">
            <p class="text-gray-500 mb-4">No equipment cost records yet.</p>
            @can('create', App\Models\EquipmentCost::class)
            <a href="{{ route('equipment-costs.create', ['project_id' => $project->id]) }}" class="btn-primary">
                Create First Cost Record
            </a>
            @endcan
        </div>
        @endif
    </div>

    <!-- Summary Card -->
    @if($costs->count() > 0)
    <div class="card mt-6 bg-green-50 border-green-200">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-600">Total Equipment Costs (This Page)</p>
                <p class="text-3xl font-bold text-green-600 mt-1">${{ number_format($costs->sum('total_cost'), 2) }}</p>
            </div>
            <div class="text-right">
                <p class="text-sm text-gray-600">Average Cost per Unit</p>
                <p class="text-2xl font-bold text-gray-900 mt-1">
                    ${{ number_format($costs->count() > 0 ? $costs->average('cost_per_unit') : 0, 2) }}
                </p>
            </div>
        </div>
    </div>
    @endif

    <!-- Back Link -->
    <div class="mt-6">
        <a href="{{ route('projects.show', $project) }}" class="text-blue-600 hover:text-blue-900 font-medium">
            ← Back to Project
        </a>
    </div>
</div>
@endsection
