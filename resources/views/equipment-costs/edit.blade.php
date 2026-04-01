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
        $minutesElapsed = now()->diffInMinutes($cost->created_at);
        $minutesRemaining = 5 - $minutesElapsed;
    @endphp
    
    <div class="alert alert-warning mb-6">
        <p class="font-semibold">⏱️ Edit Time Window</p>
        <p class="text-sm mt-1">
            Created: <strong>{{ $cost->created_at->format('g:i A') }}</strong>
            | Time Elapsed: <strong>{{ $minutesElapsed }} minutes</strong>
            | Time Remaining: <strong>{{ max(0, $minutesRemaining) }} minutes</strong>
        </p>
    </div>

    <!-- Form -->
    <form action="{{ route('equipment-costs.update', $cost) }}" method="POST" class="card">
        @csrf
        @method('PUT')

        <!-- Error Messages -->
        @if ($errors->any())
        <div class="alert alert-error mb-6">
            <p class="font-semibold mb-2">Please fix the following errors:</p>
            <ul class="list-disc list-inside space-y-1">
                @foreach ($errors->all() as $error)
                <li class="text-sm">{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <!-- Date Notice -->
        <div class="alert alert-info mb-6">
            <p class="font-semibold">📅 Record Details</p>
            <p class="text-sm mt-1">Date Recorded: <strong>{{ \Carbon\Carbon::parse($cost->date)->format('F d, Y') }}</strong> | Recorded By: <strong>{{ $cost->user->name }}</strong></p>
        </div>

        <!-- Two Column Layout -->
        <div class="grid grid-cols-2 gap-6 mb-6">
            <!-- Equipment Type -->
            <div>
                <label for="equipment_type" class="form-label">Equipment Type *</label>
                <input type="text" id="equipment_type" name="equipment_type" class="form-input" 
                    value="{{ old('equipment_type', $cost->equipment_type) }}" required>
                @error('equipment_type') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
            </div>

            <!-- Activity -->
            <div>
                <label for="activity" class="form-label">Activity / Work Type *</label>
                <input type="text" id="activity" name="activity" class="form-input" 
                    value="{{ old('activity', $cost->activity) }}" required>
                @error('activity') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
            </div>
        </div>

        <!-- Units Section -->
        <div class="mb-6">
            <h3 class="font-semibold text-gray-900 mb-4 text-lg">📊 Units & Cost</h3>
            <div class="grid grid-cols-3 gap-6">
                <div>
                    <label for="units_done" class="form-label">Units Done *</label>
                    <input type="number" id="units_done" name="units_done" class="form-input" 
                        step="0.01" min="0"
                        value="{{ old('units_done', $cost->units_done) }}" required
                        @input="updateTotal()">
                    @error('units_done') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label for="cost_per_unit" class="form-label">Cost per Unit ($) *</label>
                    <input type="number" id="cost_per_unit" name="cost_per_unit" class="form-input" 
                        step="0.01" min="0"
                        value="{{ old('cost_per_unit', $cost->cost_per_unit) }}" required
                        @input="updateTotal()">
                    @error('cost_per_unit') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label for="total_cost" class="form-label">Total Cost (Calculated)</label>
                    <div class="form-input bg-gray-100 flex items-center font-bold text-lg text-green-600" id="total_cost_display">
                        ${{ number_format($cost->total_cost, 2) }}
                    </div>
                </div>
            </div>
            <p class="text-sm text-gray-500 mt-2">Total = Units Done × Cost per Unit (auto-calculated)</p>
        </div>

        <!-- Comments -->
        <div class="mb-6">
            <label for="comment" class="form-label">Comments - Optional</label>
            <textarea id="comment" name="comment" rows="4" class="form-input">{{ old('comment', $cost->comment) }}</textarea>
            @error('comment') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
        </div>

        <!-- Form Actions -->
        <div class="flex items-center gap-4 pt-4 border-t border-gray-200">
            <button type="submit" class="btn-primary">
                ✓ Save Changes
            </button>
            <a href="{{ route('equipment-costs.show', $cost) }}" class="btn-secondary">
                Cancel
            </a>
        </div>
    </form>

    <!-- Info Box -->
    <div class="mt-8 bg-yellow-50 border border-yellow-200 rounded-lg p-4">
        <p class="text-sm text-yellow-900">
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
