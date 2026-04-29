@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Header -->
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-gray-900">Edit Casual Labour Log</h1>
        <p class="text-gray-600 mt-1">{{ $project->name }}</p>
    </div>

    <!-- Countdown Timer -->
    @php
        $editDeadline = $log->created_at->copy()->addMinutes(5);
        $isEditable = $editDeadline->isFuture();
        $minutesLeft = max(0, now()->diffInMinutes($editDeadline, false));
    @endphp
    
    <div class="bg-yellow-50 border-2 border-yellow-400 rounded-lg p-4 mb-6 shadow-sm">
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
    <div class="rounded-md bg-red-50 p-4 mb-6 border border-red-200 shadow-sm">
        <div class="text-red-800 font-semibold text-sm">Please fix the following errors:</div>
        <ul class="list-disc list-inside text-red-600 text-sm mt-2">
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <!-- Form -->
    <form action="{{ route('casual-labour-logs.update', $log) }}" method="POST" class="bg-white rounded-xl border border-gray-200 p-8 shadow-sm hover:shadow-md transition-shadow">
        @csrf
        @method('PUT')

        <!-- 2-Column Layout -->
        <div class="grid grid-cols-2 gap-8 mb-8">
            <!-- Left Column -->
            <div>
                <label for="activity" class="block text-sm font-semibold text-gray-900 mb-2">
                    Activity *
                </label>
                <input 
                    type="text" 
                    id="activity" 
                    name="activity" 
                    value="{{ old('activity', $log->activity) }}"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-400 focus:border-transparent shadow-sm {{ $errors->has('activity') ? 'border-red-500' : '' }}"
                    required
                >
                @if ($errors->has('activity'))
                <p class="text-red-600 text-sm mt-1">{{ $errors->first('activity') }}</p>
                @endif
            </div>

            <!-- Right Column -->
            <div>
                <label for="labour_classification" class="block text-sm font-semibold text-gray-900 mb-2">
                    Labour Classification *
                </label>
                <input 
                    type="text" 
                    id="labour_classification" 
                    name="labour_classification" 
                    value="{{ old('labour_classification', $log->labour_classification) }}"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-400 focus:border-transparent shadow-sm {{ $errors->has('labour_classification') ? 'border-red-500' : '' }}"
                    required
                >
                @if ($errors->has('labour_classification'))
                <p class="text-red-600 text-sm mt-1">{{ $errors->first('labour_classification') }}</p>
                @endif
            </div>
        </div>

        <!-- Labour Cost Section -->
        <div class="bg-yellow-50 rounded-lg p-6 mb-8 border border-yellow-200 shadow-sm">
            <h3 class="font-semibold text-gray-900 mb-6">💰 Labour Cost Calculation</h3>
            
            <div class="grid grid-cols-3 gap-6">
                <!-- Number of Workers -->
                <div>
                    <label for="number_of_workers" class="block text-sm font-semibold text-gray-900 mb-2">
                        Number of Workers *
                    </label>
                    <input 
                        type="number" 
                        id="number_of_workers" 
                        name="number_of_workers" 
                        value="{{ old('number_of_workers', $log->number_of_workers) }}"
                        min="1"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-400 focus:border-transparent shadow-sm {{ $errors->has('number_of_workers') ? 'border-red-500' : '' }}"
                        required
                    >
                    @if ($errors->has('number_of_workers'))
                    <p class="text-red-600 text-sm mt-1">{{ $errors->first('number_of_workers') }}</p>
                    @endif
                </div>

                <!-- Wage -->
                <div>
                    <label for="wage" class="block text-sm font-semibold text-gray-900 mb-2">
                        Wage per Worker *
                    </label>
                    <div class="relative">
                        <span class="absolute left-3 top-2 text-gray-600">$</span>
                        <input 
                            type="number" 
                            id="wage" 
                            name="wage" 
                            value="{{ old('wage', $log->wage) }}"
                            step="0.01"
                            min="0"
                            class="w-full pl-7 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-400 focus:border-transparent shadow-sm {{ $errors->has('wage') ? 'border-red-500' : '' }}"
                            required
                        >
                    </div>
                    @if ($errors->has('wage'))
                    <p class="text-red-600 text-sm mt-1">{{ $errors->first('wage') }}</p>
                    @endif
                </div>

                <!-- Total Cost -->
                <div>
                    <label class="block text-sm font-semibold text-gray-900 mb-2">
                        Total Cost
                    </label>
                    <div class="w-full px-4 py-2 border border-gray-300 rounded-lg bg-yellow-100 text-yellow-700 font-semibold shadow-sm">
                        $<span id="totalCost">{{ number_format($log->total_cost, 2) }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="flex items-center gap-4">
            <button type="submit" class="inline-flex items-center justify-center gap-2 px-6 py-2.5 bg-yellow-400 hover:bg-yellow-500 text-gray-900 font-semibold rounded-lg transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-yellow-400 focus:ring-offset-2 shadow-sm hover:shadow-md">
                ✓ Save Changes
            </button>
            <a href="{{ route('casual-labour-logs.show', $log) }}" class="inline-flex items-center justify-center gap-2 px-6 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-900 font-semibold rounded-lg transition-all duration-200">
                Cancel
            </a>
        </div>
    </form>
</div>

<script>
    function updateTotalCost() {
        const workers = parseFloat(document.getElementById('number_of_workers').value) || 0;
        const wage = parseFloat(document.getElementById('wage').value) || 0;
        const total = workers * wage;
        document.getElementById('totalCost').textContent = total.toFixed(2);
    }

    document.getElementById('number_of_workers').addEventListener('input', updateTotalCost);
    document.getElementById('wage').addEventListener('input', updateTotalCost);
    
    // Initialize on page load
    updateTotalCost();
</script>
@endsection
