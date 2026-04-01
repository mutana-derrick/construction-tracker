@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Header -->
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-gray-900">Edit Productivity Log</h1>
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
    <form action="{{ route('productivity-logs.update', $log) }}" method="POST" class="card">
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
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent {{ $errors->has('activity') ? 'border-red-500' : '' }}"
                    required
                >
                @if ($errors->has('activity'))
                <p class="text-red-600 text-sm mt-1">{{ $errors->first('activity') }}</p>
                @endif
            </div>

            <!-- Right Column -->
            <div>
                <label for="equipment_name" class="block text-sm font-semibold text-gray-900 mb-2">
                    Equipment Name *
                </label>
                <input 
                    type="text" 
                    id="equipment_name" 
                    name="equipment_name" 
                    value="{{ old('equipment_name', $log->equipment_name) }}"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent {{ $errors->has('equipment_name') ? 'border-red-500' : '' }}"
                    required
                >
                @if ($errors->has('equipment_name'))
                <p class="text-red-600 text-sm mt-1">{{ $errors->first('equipment_name') }}</p>
                @endif
            </div>
        </div>

        <!-- Productivity Section -->
        <div class="bg-gray-50 rounded-lg p-6 mb-8 border border-gray-200">
            <h3 class="font-semibold text-gray-900 mb-6">📊 Productivity Metrics</h3>
            
            <div class="grid grid-cols-3 gap-6">
                <!-- Workers -->
                <div>
                    <label for="workers" class="block text-sm font-semibold text-gray-900 mb-2">
                        Number of Workers *
                    </label>
                    <input 
                        type="number" 
                        id="workers" 
                        name="workers" 
                        value="{{ old('workers', $log->workers) }}"
                        min="1"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent {{ $errors->has('workers') ? 'border-red-500' : '' }}"
                        required
                    >
                    @if ($errors->has('workers'))
                    <p class="text-red-600 text-sm mt-1">{{ $errors->first('workers') }}</p>
                    @endif
                </div>

                <!-- Output -->
                <div>
                    <label for="output" class="block text-sm font-semibold text-gray-900 mb-2">
                        Output/Production *
                    </label>
                    <input 
                        type="number" 
                        id="output" 
                        name="output" 
                        value="{{ old('output', $log->output) }}"
                        step="0.01"
                        min="0"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent {{ $errors->has('output') ? 'border-red-500' : '' }}"
                        required
                    >
                    @if ($errors->has('output'))
                    <p class="text-red-600 text-sm mt-1">{{ $errors->first('output') }}</p>
                    @endif
                </div>

                <!-- Output per Worker -->
                <div>
                    <label class="block text-sm font-semibold text-gray-900 mb-2">
                        Output per Worker
                    </label>
                    <div class="w-full px-4 py-2 border border-gray-300 rounded-lg bg-gray-100 text-gray-700 font-semibold">
                        <span id="outputPerWorker">0.00</span> per worker
                    </div>
                </div>
            </div>
        </div>

        <!-- Comments -->
        <div class="mb-8">
            <label for="comment" class="block text-sm font-semibold text-gray-900 mb-2">
                Comments (Optional)
            </label>
            <textarea 
                id="comment" 
                name="comment" 
                rows="4"
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent {{ $errors->has('comment') ? 'border-red-500' : '' }}"
            >{{ old('comment', $log->comment) }}</textarea>
            @if ($errors->has('comment'))
            <p class="text-red-600 text-sm mt-1">{{ $errors->first('comment') }}</p>
            @endif
        </div>

        <!-- Action Buttons -->
        <div class="flex items-center gap-4">
            <button type="submit" class="btn-primary">
                ✓ Save Changes
            </button>
            <a href="{{ route('productivity-logs.show', $log) }}" class="btn-secondary">
                Cancel
            </a>
        </div>
    </form>
</div>

<script>
    function updateProductivityDisplay() {
        const output = parseFloat(document.getElementById('output').value) || 0;
        const workers = parseFloat(document.getElementById('workers').value) || 1;
        const perWorker = workers > 0 ? (output / workers).toFixed(2) : 0;
        document.getElementById('outputPerWorker').textContent = perWorker;
    }

    document.getElementById('output').addEventListener('input', updateProductivityDisplay);
    document.getElementById('workers').addEventListener('input', updateProductivityDisplay);
    
    // Initialize on page load
    updateProductivityDisplay();
</script>
@endsection
