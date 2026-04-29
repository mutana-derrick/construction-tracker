@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Header -->
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-gray-900">Edit Equipment Cost Record</h1>
        <p class="text-gray-600 mt-1">{{ $project->name }}</p>
    </div>

    <!-- 5-Minute Edit Window Warning -->
    @php
        $editDeadline = $log->created_at->copy()->addMinutes(5);
        $isEditable = $editDeadline->isFuture();
        $minutesLeft = max(0, now()->diffInMinutes($editDeadline, false));
    @endphp
    
    <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 mb-6 shadow-sm">
        <p class="font-semibold text-yellow-900">⏱️ Edit Time Window</p>
        <p class="text-sm mt-1 text-yellow-800">
            Created: <strong>{{ $cost->created_at->format('g:i A') }}</strong>
            | Time Elapsed: <strong>{{ $minutesElapsed }} minutes</strong>
            | Time Remaining: <strong>{{ max(0, $minutesRemaining) }} minutes</strong>
        </p>
    </div>

    <!-- Form -->
    <form action="{{ route('equipment-costs.update', $cost) }}" method="POST" class="bg-white rounded-xl border border-gray-200 p-8 shadow-sm hover:shadow-md transition-shadow">
        @csrf
        @method('PUT')

        <!-- Error Messages -->
        @if ($errors->any())
        <div class="bg-red-50 border border-red-200 rounded-lg p-4 mb-6 shadow-sm">
            <p class="font-semibold text-red-800 mb-2">Please fix the following errors:</p>
            <ul class="list-disc list-inside space-y-1">
                @foreach ($errors->all() as $error)
                <li class="text-sm text-red-700">{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <!-- Date Notice -->
        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6 shadow-sm">
            <p class="font-semibold text-blue-900">📅 Record Details</p>
            <p class="text-sm mt-1 text-blue-800">Date Recorded: <strong>{{ \Carbon\Carbon::parse($cost->date)->format('F d, Y') }}</strong> | Recorded By: <strong>{{ $cost->user->name }}</strong></p>
        </div>

        <!-- Two Column Layout -->
        <div class="grid grid-cols-2 gap-6 mb-6">
            <!-- Equipment Type -->
            <div>
                <label for="equipment_type" class="block text-sm font-semibold text-gray-900 mb-2">Equipment Type *</label>
                <input type="text" id="equipment_type" name="equipment_type" class="w-full px-4 py-2.5 border @error('equipment_type') border-red-500 @else border-gray-200 @enderror rounded-lg focus:outline-none focus:ring-2 focus:ring-yellow-400 focus:border-transparent transition-all shadow-sm" 
                    value="{{ old('equipment_type', $cost->equipment_type) }}" required>
                @error('equipment_type') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
            </div>

            <!-- Activity -->
            <div>
                <label for="activity" class="block text-sm font-semibold text-gray-900 mb-2">Activity / Work Type *</label>
                <input type="text" id="activity" name="activity" class="w-full px-4 py-2.5 border @error('activity') border-red-500 @else border-gray-200 @enderror rounded-lg focus:outline-none focus:ring-2 focus:ring-yellow-400 focus:border-transparent transition-all shadow-sm" 
                    value="{{ old('activity', $cost->activity) }}" required>
                @error('activity') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
            </div>
        </div>

        <!-- Units Section -->
        <div class="mb-6">
            <h3 class="font-semibold text-gray-900 mb-4 text-lg flex items-center gap-2">
              <svg class="w-5 h-5 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
              </svg>
              📊 Units & Cost
            </h3>
            <div class="grid grid-cols-3 gap-6">
                <div>
                    <label for="units_done" class="block text-sm font-semibold text-gray-900 mb-2">Units Done *</label>
                    <input type="number" id="units_done" name="units_done" class="w-full px-4 py-2.5 border @error('units_done') border-red-500 @else border-gray-200 @enderror rounded-lg focus:outline-none focus:ring-2 focus:ring-yellow-400 focus:border-transparent transition-all shadow-sm" 
                        step="0.01" min="0"
                        value="{{ old('units_done', $cost->units_done) }}" required
                        @input="updateTotal()">
                    @error('units_done') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label for="cost_per_unit" class="block text-sm font-semibold text-gray-900 mb-2">Cost per Unit ($) *</label>
                    <input type="number" id="cost_per_unit" name="cost_per_unit" class="w-full px-4 py-2.5 border @error('cost_per_unit') border-red-500 @else border-gray-200 @enderror rounded-lg focus:outline-none focus:ring-2 focus:ring-yellow-400 focus:border-transparent transition-all shadow-sm" 
                        step="0.01" min="0"
                        value="{{ old('cost_per_unit', $cost->cost_per_unit) }}" required
                        @input="updateTotal()">
                    @error('cost_per_unit') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label for="total_cost" class="block text-sm font-semibold text-gray-900 mb-2">Total Cost (Calculated)</label>
                    <div class="w-full px-4 py-2.5 border border-gray-200 rounded-lg bg-yellow-100 flex items-center font-bold text-lg text-yellow-700 shadow-sm" id="total_cost_display">
                        Rwf {{ number_format($cost->total_cost, 2) }}
                    </div>
                </div>
            </div>
            <p class="text-sm text-gray-500 mt-2">Total = Units Done × Cost per Unit (auto-calculated)</p>
        </div>

        <!-- Comments -->
        <div class="mb-6">
            <label for="comment" class="block text-sm font-semibold text-gray-900 mb-2">Comments - Optional</label>
            <textarea id="comment" name="comment" rows="4" class="w-full px-4 py-2.5 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-yellow-400 focus:border-transparent transition-all resize-none shadow-sm">{{ old('comment', $cost->comment) }}</textarea>
            @error('comment') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
        </div>

        <!-- Form Actions -->
        <div class="flex items-center gap-4 pt-4 border-t border-gray-200">
            <button type="submit" class="inline-flex items-center justify-center gap-2 px-6 py-2.5 bg-yellow-400 hover:bg-yellow-500 text-gray-900 font-semibold rounded-lg transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-yellow-400 focus:ring-offset-2 shadow-sm hover:shadow-md">
                ✓ Save Changes
            </button>
            <a href="{{ route('equipment-costs.show', $cost) }}" class="inline-flex items-center justify-center gap-2 px-6 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-900 font-semibold rounded-lg transition-all duration-200">
                Cancel
            </a>
        </div>
    </form>

    <!-- Info Box -->
    <div class="mt-8 bg-amber-50 border border-amber-200 rounded-lg p-4 shadow-sm">
        <p class="text-sm text-amber-900">
            <strong>⚠️ Important:</strong> You can only edit this record for {{ max(0, $minutesRemaining) }} more minutes. Save your changes immediately if needed.
        </p>
    </div>
</div>

<script>
    function updateTotal() {
        const units = parseFloat(document.getElementById('units_done').value) || 0;
        const cost = parseFloat(document.getElementById('cost_per_unit').value) || 0;
        const total = units * cost;
        document.getElementById('total_cost_display').textContent = '$' + total.toFixed(2);
    }

    document.addEventListener('DOMContentLoaded', updateTotal);
</script>
@endsection
