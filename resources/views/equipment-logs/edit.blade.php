@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto">
    <!-- Header -->
    <div class="mb-6">
        <a href="{{ route('equipment-logs.show', $log) }}" class="inline-flex items-center gap-1 text-gray-600 hover:text-gray-900 font-medium transition-colors mb-3">
          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
          </svg>
          Back to Equipment Log
        </a>
        <div class="flex items-center gap-3 mb-2">
          <svg class="w-8 h-8 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
          </svg>
          <h1 class="text-3xl font-bold text-gray-900">Edit Equipment Log</h1>
        </div>
        <p class="text-gray-600 mt-2">
          <span class="font-semibold">{{ $log->equipment_type }}</span> 
          ({{ $log->equipment_id }})
        </p>
    </div>

    <!-- Form -->
    <form action="{{ route('equipment-logs.update', $log) }}" method="POST" class="bg-white rounded-xl border border-gray-200 p-8 shadow-sm hover:shadow-md transition-shadow">
        @csrf
        @method('PUT')

        <!-- Error Messages -->
        @if ($errors->any())
        <div class="bg-red-50 border border-red-200 rounded-lg p-4 mb-6">
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

        <!-- Edit Time Limit Warning -->
        @php
          $editDeadline = $log->created_at->copy()->addMinutes(5);
          $isEditable = $editDeadline->isFuture();
          $minutesLeft = max(0, now()->diffInMinutes($editDeadline, false));
        @endphp

        <div class="mb-8 p-4 rounded-lg border 
            {{ $isEditable ? 'bg-green-50 border-green-200' : 'bg-red-50 border-red-200' }}">

            <p class="text-sm font-semibold 
                {{ $isEditable ? 'text-green-700' : 'text-red-700' }}">
                
                {{ $isEditable ? '⏱️ Edit Window Active' : '⛔ Edit Window Expired' }}
            </p>

            <p class="text-sm mt-1 text-gray-600">
                Created at {{ $log->created_at->format('g:i A') }} • 
                {{ $isEditable ? $minutesLeft . ' minutes remaining' : 'Editing time expired' }}
            </p>
        </div>

        <!-- Equipment Section -->
        <div class="space-y-6 mb-8 pb-8 border-b border-gray-200">
          <h2 class="text-lg font-bold text-gray-900 flex items-center gap-2">
            <svg class="w-5 h-5 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            Equipment Details
          </h2>

          <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Equipment Type -->
            <div>
                <label for="equipment_type" class="block text-sm font-semibold text-gray-900 mb-2">Equipment Type *</label>
                <input type="text" id="equipment_type" name="equipment_type" class="w-full px-4 py-2.5 border @error('equipment_type') border-red-500 @else border-gray-200 @enderror rounded-lg focus:outline-none focus:ring-2 focus:ring-yellow-400 focus:border-transparent transition-all shadow-sm" 
                    placeholder="e.g., Excavator, Bulldozer, Compactor" 
                    value="{{ old('equipment_type', $log->equipment_type) }}" @if (!$canEdit) disabled @endif required>
                @error('equipment_type') <div class="flex items-center gap-2 text-red-600 text-sm mt-2"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4v.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>{{ $message }}</div> @enderror
            </div>

            <!-- Equipment ID -->
            <div>
                <label for="equipment_id" class="block text-sm font-semibold text-gray-900 mb-2">Equipment ID *</label>
                <input type="text" id="equipment_id" name="equipment_id" class="w-full px-4 py-2.5 border @error('equipment_id') border-red-500 @else border-gray-200 @enderror rounded-lg focus:outline-none focus:ring-2 focus:ring-yellow-400 focus:border-transparent transition-all shadow-sm" 
                    placeholder="e.g., EQ-001, CAT-336" 
                    value="{{ old('equipment_id', $log->equipment_id) }}" @if (!$canEdit) disabled @endif required>
                @error('equipment_id') <div class="flex items-center gap-2 text-red-600 text-sm mt-2"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4v.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>{{ $message }}</div> @enderror
            </div>
          </div>

          <!-- Activity -->
          <div>
              <label for="activity" class="block text-sm font-semibold text-gray-900 mb-2">Activity / Work Performed *</label>
              <input type="text" id="activity" name="activity" class="w-full px-4 py-2.5 border @error('activity') border-red-500 @else border-gray-200 @enderror rounded-lg focus:outline-none focus:ring-2 focus:ring-yellow-400 focus:border-transparent transition-all shadow-sm" 
                  placeholder="e.g., Site excavation, Foundation preparation" 
                  value="{{ old('activity', $log->activity) }}" @if (!$canEdit) disabled @endif required>
              @error('activity') <div class="flex items-center gap-2 text-red-600 text-sm mt-2"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4v.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>{{ $message }}</div> @enderror
          </div>
        </div>

        <!-- Output Section -->
        <div class="space-y-6 mb-8 pb-8 border-b border-gray-200">
          <h2 class="text-lg font-bold text-gray-900 flex items-center gap-2">
            <svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
            </svg>
            Output Metrics
          </h2>
          <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
              <div>
                  <label for="planned_output" class="block text-sm font-semibold text-gray-900 mb-2">Planned Output *</label>
                  <input type="number" id="planned_output" name="planned_output" class="w-full px-4 py-2.5 border @error('planned_output') border-red-500 @else border-gray-200 @enderror rounded-lg focus:outline-none focus:ring-2 focus:ring-yellow-400 focus:border-transparent transition-all shadow-sm" 
                      placeholder="0.00" step="0.01" min="0"
                      value="{{ old('planned_output', $log->planned_output) }}" @if (!$canEdit) disabled @endif required>
                  @error('planned_output') <div class="flex items-center gap-2 text-red-600 text-sm mt-2"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4v.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>{{ $message }}</div> @enderror
              </div>

              <div>
                  <label for="actual_output" class="block text-sm font-semibold text-gray-900 mb-2">Actual Output *</label>
                  <input type="number" id="actual_output" name="actual_output" class="w-full px-4 py-2.5 border @error('actual_output') border-red-500 @else border-gray-200 @enderror rounded-lg focus:outline-none focus:ring-2 focus:ring-yellow-400 focus:border-transparent transition-all shadow-sm" 
                      placeholder="0.00" step="0.01" min="0"
                      value="{{ old('actual_output', $log->actual_output) }}" @if (!$canEdit) disabled @endif required>
                  @error('actual_output') <div class="flex items-center gap-2 text-red-600 text-sm mt-2"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4v.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>{{ $message }}</div> @enderror
              </div>
          </div>
        </div>

        <!-- Hours Section -->
        <div class="space-y-6 mb-8 pb-8 border-b border-gray-200">
          <h2 class="text-lg font-bold text-gray-900 flex items-center gap-2">
            <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            Working Hours
          </h2>
          <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
              <div>
                  <label for="working_hours" class="block text-sm font-semibold text-gray-900 mb-2">Working Hours *</label>
                  <input type="number" id="working_hours" name="working_hours" class="w-full px-4 py-2.5 border @error('working_hours') border-red-500 @else border-gray-200 @enderror rounded-lg focus:outline-none focus:ring-2 focus:ring-yellow-400 focus:border-transparent transition-all shadow-sm" 
                      placeholder="0.0" step="0.1" min="0" max="24"
                      value="{{ old('working_hours', $log->working_hours) }}" @if (!$canEdit) disabled @endif required>
                  @error('working_hours') <div class="flex items-center gap-2 text-red-600 text-sm mt-2"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4v.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>{{ $message }}</div> @enderror
              </div>

              <div>
                  <label for="available_hours" class="block text-sm font-semibold text-gray-900 mb-2">Available Hours *</label>
                  <input type="number" id="available_hours" name="available_hours" class="w-full px-4 py-2.5 border @error('available_hours') border-red-500 @else border-gray-200 @enderror rounded-lg focus:outline-none focus:ring-2 focus:ring-yellow-400 focus:border-transparent transition-all shadow-sm" 
                      placeholder="0.0" step="0.1" min="0" max="24"
                      value="{{ old('available_hours', $log->available_hours) }}" @if (!$canEdit) disabled @endif required>
                  @error('available_hours') <div class="flex items-center gap-2 text-red-600 text-sm mt-2"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4v.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>{{ $message }}</div> @enderror
              </div>
          </div>
          <p class="text-sm text-gray-600 bg-gray-50 px-3 py-2 rounded-lg">📊 <strong>Productivity</strong> = Actual Output ÷ Working Hours</p>
        </div>

        <!-- Additional Fields -->
        <div class="space-y-6 mb-8">
          <!-- Fuel -->
          <div>
              <label for="fuel_used" class="block text-sm font-semibold text-gray-900 mb-2">Fuel Used (Liters) <span class="text-gray-500 font-normal">Optional</span></label>
              <input type="number" id="fuel_used" name="fuel_used" class="w-full px-4 py-2.5 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-yellow-400 focus:border-transparent transition-all shadow-sm" 
                  placeholder="0.0" step="0.01" min="0"
                  value="{{ old('fuel_used', $log->fuel_used) }}" @if (!$canEdit) disabled @endif>
              @error('fuel_used') <div class="flex items-center gap-2 text-red-600 text-sm mt-2"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4v.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>{{ $message }}</div> @enderror
          </div>

          <!-- Comments -->
          <div>
              <label for="comment" class="block text-sm font-semibold text-gray-900 mb-2">Comments <span class="text-gray-500 font-normal">Optional</span></label>
              <textarea id="comment" name="comment" rows="4" class="w-full px-4 py-2.5 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-yellow-400 focus:border-transparent transition-all resize-none shadow-sm" 
                  placeholder="Add any additional notes about this equipment log..." @if (!$canEdit) disabled @endif>{{ old('comment', $log->comment) }}</textarea>
              @error('comment') <div class="flex items-center gap-2 text-red-600 text-sm mt-2"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4v.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>{{ $message }}</div> @enderror
          </div>
        </div>

        <!-- Form Actions -->
        <div class="flex flex-col sm:flex-row gap-4 pt-6 border-t border-gray-200">
            @if ($canEdit)
            <button type="submit" class="inline-flex items-center justify-center gap-2 px-6 py-2.5 bg-yellow-400 hover:bg-yellow-500 text-gray-900 font-semibold rounded-lg transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-yellow-400 focus:ring-offset-2 shadow-sm hover:shadow-md">
              <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
              </svg>
              Save Changes
            </button>
            @else
            <button type="submit" disabled class="inline-flex items-center justify-center gap-2 px-6 py-2.5 bg-gray-200 text-gray-500 font-semibold rounded-lg cursor-not-allowed">
              <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
              </svg>
              Edit Window Closed
            </button>
            @endif
            <a href="{{ route('equipment-logs.show', $log) }}" class="inline-flex items-center justify-center gap-2 px-6 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-900 font-semibold rounded-lg transition-all duration-200">
              Cancel
            </a>
        </div>
    </form>

    <!-- Info Box -->
    <div class="mt-8 bg-blue-50 border border-blue-200 rounded-lg p-4">
        <p class="text-sm text-blue-900">
          <svg class="w-4 h-4 inline mr-2" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M18 5v8a2 2 0 01-2 2h-5l-5 4v-4H4a2 2 0 01-2-2V5a2 2 0 012-2h12a2 2 0 012 2z" clip-rule="evenodd"></path>
          </svg>
          <strong>Tip:</strong> Equipment logs can only be edited within 5 minutes of creation to ensure data integrity.
        </p>
    </div>
</div>
@endsection
