@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Header -->
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-gray-900">Edit Material Usage Log</h1>
        <p class="text-gray-600 mt-1">{{ $project->name }}</p>
    </div>

    <!-- Countdown Timer -->
    @php
        $minutesElapsed = now()->diffInMinutes($log->created_at);
        $minutesRemaining = max(0, 5 - $minutesElapsed);
    @endphp
    
    <div class="bg-yellow-50 border-2 border-yellow-400 rounded-lg p-4 mb-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-semibold text-yellow-900">⏱️ Time Remaining</p>
                <p class="text-gray-700 mt-1">This record can only be edited within 5 minutes of creation.</p>
            </div>
            <div class="text-right">
                <p class="text-4xl font-bold text-yellow-600">{{ $minutesRemaining }}</p>
                <p class="text-sm text-yellow-700">minute{{ $minutesRemaining != 1 ? 's' : '' }} left</p>
            </div>
        </div>
        <p class="text-xs text-yellow-700 mt-3">Created at {{ $log->created_at->format('g:i A') }} • Save your changes immediately</p>
    </div>

    <!-- Validation Errors -->
    @if ($errors->any())
    <div class="rounded-md bg-red-50 p-4 mb-6 border border-red-200">
        <div class="text-red-800 font-semibold text-sm">Please fix the following errors:</div>
        <ul class="list-disc list-inside text-red-600 text-sm mt-2">
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <!-- Form -->
    <form action="{{ route('material-usage.update', $log) }}" method="POST" class="card">
        @csrf
        @method('PUT')

        <!-- 2-Column Layout -->
        <div class="grid grid-cols-2 gap-8 mb-8">
            <!-- Left Column -->
            <div>
                <label for="material_name" class="block text-sm font-semibold text-gray-900 mb-2">
                    Material Name *
                </label>
                <input 
                    type="text" 
                    id="material_name" 
                    name="material_name" 
                    value="{{ old('material_name', $log->material_name) }}"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent {{ $errors->has('material_name') ? 'border-red-500' : '' }}"
                    required
                >
                @if ($errors->has('material_name'))
                <p class="text-red-600 text-sm mt-1">{{ $errors->first('material_name') }}</p>
                @endif
            </div>

            <!-- Right Column -->
            <div>
                <label for="activity" class="block text-sm font-semibold text-gray-900 mb-2">
                    Activity *
                </label>
                <input 
                    type="text" 
                    id="activity" 
                    name="activity" 
                    value="{{ old('activity', $log->activity) }}"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent {{ $errors->has('activity') ? 'border-red-500' : '' }}"
                    required
                >
                @if ($errors->has('activity'))
                <p class="text-red-600 text-sm mt-1">{{ $errors->first('activity') }}</p>
                @endif
            </div>
        </div>

        <!-- Material Usage Section -->
        <div class="bg-gray-50 rounded-lg p-6 mb-8 border border-gray-200">
            <h3 class="font-semibold text-gray-900 mb-6">📦 Material Usage Tracking</h3>
            
            <div class="grid grid-cols-3 gap-6">
                <!-- Planned Quantity -->
                <div>
                    <label for="planned_qty" class="block text-sm font-semibold text-gray-900 mb-2">
                        Planned Quantity *
                    </label>
                    <input 
                        type="number" 
                        id="planned_qty" 
                        name="planned_qty" 
                        value="{{ old('planned_qty', $log->planned_qty) }}"
                        step="0.01"
                        min="0"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent {{ $errors->has('planned_qty') ? 'border-red-500' : '' }}"
                        required
                    >
                    @if ($errors->has('planned_qty'))
                    <p class="text-red-600 text-sm mt-1">{{ $errors->first('planned_qty') }}</p>
                    @endif
                </div>

                <!-- Used Quantity -->
                <div>
                    <label for="used_qty" class="block text-sm font-semibold text-gray-900 mb-2">
                        Used Quantity *
                    </label>
                    <input 
                        type="number" 
                        id="used_qty" 
                        name="used_qty" 
                        value="{{ old('used_qty', $log->used_qty) }}"
                        step="0.01"
                        min="0"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent {{ $errors->has('used_qty') ? 'border-red-500' : '' }}"
                        required
                    >
                    @if ($errors->has('used_qty'))
                    <p class="text-red-600 text-sm mt-1">{{ $errors->first('used_qty') }}</p>
                    @endif
                </div>

                <!-- Difference -->
                <div>
                    <label class="block text-sm font-semibold text-gray-900 mb-2">
                        Difference (Planned - Used)
                    </label>
                    <div class="w-full px-4 py-2 border border-gray-300 rounded-lg bg-gray-100 text-gray-700 font-semibold">
                        <span id="difference">{{ number_format($log->planned_qty - $log->used_qty, 2) }}</span>
                    </div>
                </div>
            </div>

            <!-- Usage Efficiency Display -->
            <div class="mt-6 bg-blue-50 border border-blue-200 rounded-lg p-4">
                <p class="text-sm text-blue-900">
                    <strong>📊 Status:</strong> <span id="efficiencyStatus">{{ $log->planned_qty - $log->used_qty > 0 ? '✓ Under budget' : ($log->planned_qty - $log->used_qty < 0 ? '⚠️ Over budget' : '◆ Exact usage') }}</span>
                </p>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="flex items-center gap-4">
            <button type="submit" class="btn-primary">
                ✓ Save Changes
            </button>
            <a href="{{ route('material-usage.show', $log) }}" class="btn-secondary">
                Cancel
            </a>
        </div>
    </form>
</div>

<script>
    function updateDifference() {
        const planned = parseFloat(document.getElementById('planned_qty').value) || 0;
        const used = parseFloat(document.getElementById('used_qty').value) || 0;
        const diff = planned - used;
        document.getElementById('difference').textContent = diff.toFixed(2);
        
        // Update efficiency status
        const status = document.getElementById('efficiencyStatus');
        if (diff > 0) {
            status.textContent = '✓ Under budget (surplus material: ' + diff.toFixed(2) + ')';
            status.className = 'text-green-700 font-semibold';
        } else if (diff < 0) {
            status.textContent = '⚠️ Over budget (shortage: ' + Math.abs(diff).toFixed(2) + ')';
            status.className = 'text-red-700 font-semibold';
        } else {
            status.textContent = '◆ Exact usage (no surplus or shortage)';
            status.className = 'text-blue-700 font-semibold';
        }
    }

    document.getElementById('planned_qty').addEventListener('input', updateDifference);
    document.getElementById('used_qty').addEventListener('input', updateDifference);
    
    // Initialize on page load
    updateDifference();
</script>
@endsection
