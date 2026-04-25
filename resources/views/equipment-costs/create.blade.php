@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto">
    <!-- Header -->
    <div class="mb-6">
        <a href="{{ route('equipment-costs.index', ['project_id' => $project->id]) }}" class="inline-flex items-center gap-1 text-gray-600 hover:text-gray-900 font-medium transition-colors mb-3">
          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
          </svg>
          Back to Equipment Costs
        </a>
        <div class="flex items-center gap-3 mb-2">
          <svg class="w-8 h-8 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
          </svg>
          <h1 class="text-3xl font-bold text-gray-900">New Equipment Cost Record</h1>
        </div>
        <p class="text-gray-600 mt-2">Project: <span class="font-semibold">{{ $project->name }}</span></p>
    </div>

    <!-- Form -->
    <form action="{{ route('equipment-costs.store') }}" method="POST" class="bg-white rounded-xl border border-gray-200 p-8 shadow-sm hover:shadow-md transition-shadow">
        @csrf

        <!-- Hidden Project ID -->
        <input type="hidden" name="project_id" value="{{ $project->id }}">

        <!-- Error Messages -->
        @if ($errors->any())
        <div class="bg-red-50 border border-red-200 rounded-lg p-4 mb-6 shadow-sm">
            <div class="flex gap-3">
              <svg class="w-5 h-5 text-red-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4v.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
              </svg>
              <div>
                <p class="font-semibold text-red-800 mb-2">Please fix the following errors:</p>
                <ul class="list-disc list-inside space-y-1 text-sm text-red-700">
                    @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
              </div>
            </div>
        </div>
        @endif

        <!-- Date Notice -->
        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6 shadow-sm">
            <div class="flex gap-3">
              <svg class="w-5 h-5 text-blue-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
              </svg>
              <div>
                <p class="font-semibold text-blue-900">Date Information</p>
                <p class="text-sm text-blue-800 mt-1">This record will be recorded for <strong>{{ now()->format('F d, Y') }}</strong> (today)</p>
              </div>
            </div>
        </div>

        <!-- Equipment Section -->
        <div class="space-y-6 mb-8 pb-8 border-b border-gray-200">
          <h2 class="text-lg font-bold text-gray-900 flex items-center gap-2">
            <svg class="w-5 h-5 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
            </svg>
            Equipment Details
          </h2>

          <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Equipment Type -->
            <div>
                <label for="equipment_type" class="block text-sm font-semibold text-gray-900 mb-2">Equipment Type *</label>
                <input type="text" id="equipment_type" name="equipment_type" class="w-full px-4 py-2.5 border @error('equipment_type') border-red-500 @else border-gray-200 @enderror rounded-lg focus:outline-none focus:ring-2 focus:ring-yellow-400 focus:border-transparent transition-all shadow-sm" 
                    placeholder="e.g., Excavator, Bulldozer, Compactor" 
                    value="{{ old('equipment_type') }}" required>
                @error('equipment_type') <div class="flex items-center gap-2 text-red-600 text-sm mt-2"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4v.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>{{ $message }}</div> @enderror
            </div>

            <!-- Activity / Work Type -->
            <div>
                <label for="activity" class="block text-sm font-semibold text-gray-900 mb-2">Activity / Work Type *</label>
                <input type="text" id="activity" name="activity" class="w-full px-4 py-2.5 border @error('activity') border-red-500 @else border-gray-200 @enderror rounded-lg focus:outline-none focus:ring-2 focus:ring-yellow-400 focus:border-transparent transition-all shadow-sm" 
                    placeholder="e.g., Excavation, Foundation prep" 
                    value="{{ old('activity') }}" required>
                @error('activity') <div class="flex items-center gap-2 text-red-600 text-sm mt-2"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4v.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>{{ $message }}</div> @enderror
            </div>
          </div>
        </div>

        <!-- Cost Section -->
        <div class="space-y-6 mb-8 pb-8 border-b border-gray-200">
          <h2 class="text-lg font-bold text-gray-900 flex items-center gap-2">
            <svg class="w-5 h-5 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            Units & Cost Calculation
          </h2>
          
          <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- Units Done -->
            <div>
                <label for="units_done" class="block text-sm font-semibold text-gray-900 mb-2">Units Done *</label>
                <input type="number" id="units_done" name="units_done" class="w-full px-4 py-2.5 border @error('units_done') border-red-500 @else border-gray-200 @enderror rounded-lg focus:outline-none focus:ring-2 focus:ring-yellow-400 focus:border-transparent transition-all shadow-sm" 
                    placeholder="0.00" step="0.01" min="0"
                    value="{{ old('units_done') }}" required
                    x-data="{ units_done: {{ old('units_done', 0) }}, cost_per_unit: {{ old('cost_per_unit', 0) }} }"
                    @input="$dispatch('calculate-total')"
                    x-model.number="units_done">
                @error('units_done') <div class="flex items-center gap-2 text-red-600 text-sm mt-2"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4v.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>{{ $message }}</div> @enderror
            </div>

            <!-- Cost per Unit -->
            <div>
                <label for="cost_per_unit" class="block text-sm font-semibold text-gray-900 mb-2">Cost per Unit ($) *</label>
                <input type="number" id="cost_per_unit" name="cost_per_unit" class="w-full px-4 py-2.5 border @error('cost_per_unit') border-red-500 @else border-gray-200 @enderror rounded-lg focus:outline-none focus:ring-2 focus:ring-yellow-400 focus:border-transparent transition-all shadow-sm" 
                    placeholder="0.00" step="0.01" min="0"
                    value="{{ old('cost_per_unit') }}" required
                    @input="$dispatch('calculate-total')"
                    x-model.number="cost_per_unit">
                @error('cost_per_unit') <div class="flex items-center gap-2 text-red-600 text-sm mt-2"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4v.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>{{ $message }}</div> @enderror
            </div>

            <!-- Total Cost Display -->
            <div>
                <label for="total_cost" class="block text-sm font-semibold text-gray-900 mb-2">Total Cost (Auto-calculated)</label>
                <div class="w-full px-4 py-2.5 border border-gray-200 rounded-lg bg-gradient-to-r from-yellow-50 to-amber-50 flex items-center font-bold text-lg text-yellow-600 shadow-sm" id="total_cost_display">
                    Rwf0.00
                </div>
            </div>
          </div>
          <p class="text-sm text-gray-600 flex items-center gap-2">
            <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            Total = Units Done × Cost per Unit (automatically calculated)
          </p>
        </div>

        <!-- Comments -->
        <div class="mb-6">
            <label for="comment" class="block text-sm font-semibold text-gray-900 mb-2">Comments - Optional</label>
            <textarea id="comment" name="comment" rows="4" class="w-full px-4 py-2.5 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-yellow-400 focus:border-transparent transition-all shadow-sm" 
                placeholder="Add any additional notes about this cost record...">{{ old('comment') }}</textarea>
            @error('comment') <div class="flex items-center gap-2 text-red-600 text-sm mt-2"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4v.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>{{ $message }}</div> @enderror
        </div>

        <!-- Form Actions -->
        <div class="flex items-center gap-4 pt-6">
            <button type="submit" class="inline-flex items-center gap-2 px-6 py-2.5 bg-yellow-400 hover:bg-yellow-500 text-gray-900 font-semibold rounded-lg transition-all duration-200 shadow-sm hover:shadow-md">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                Create Cost Record
            </button>
            <a href="{{ route('equipment-costs.index', ['project_id' => $project->id]) }}" class="inline-flex items-center gap-2 px-6 py-2.5 border border-gray-300 hover:border-gray-400 text-gray-700 font-semibold rounded-lg transition-all duration-200 hover:bg-gray-50 shadow-sm">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
                Cancel
            </a>
        </div>
    </form>

    <!-- Tip Box -->
    <div class="mt-8 bg-linear-to-r from-amber-50 to-orange-50 border border-amber-200 rounded-lg p-4 flex gap-3 shadow-sm">
        <svg class="w-5 h-5 text-amber-600 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9 5h.01"></path>
        </svg>
        <p class="text-sm text-amber-900">
            <strong>💡 Tip:</strong> Once created, this record can only be edited within 5 minutes. Plan carefully before submitting!
        </p>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const unitsInput = document.getElementById('units_done');
        const costPerUnitInput = document.getElementById('cost_per_unit');
        const totalDisplay = document.getElementById('total_cost_display');

        function updateTotal() {
            const units = parseFloat(unitsInput.value) || 0;
            const cost = parseFloat(costPerUnitInput.value) || 0;
            const total = units * cost;
            totalDisplay.textContent = '$' + total.toFixed(2);
        }

        unitsInput.addEventListener('input', updateTotal);
        costPerUnitInput.addEventListener('input', updateTotal);
        updateTotal();
    });
</script>
@endsection
