@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Header -->
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-gray-900">Create Casual Labour Log</h1>
        <p class="text-gray-600 mt-1">{{ $project->name }}</p>
    </div>

    <!-- Info Box -->
    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6 shadow-sm">
        <p class="text-sm text-blue-900">
            <strong>ℹ️ Note:</strong> This record will be recorded for <strong>{{ now()->format('F d, Y') }}</strong> and assigned to your account.
        </p>
    </div>

    <!-- Time Warning -->
    <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 mb-6 shadow-sm">
        <p class="text-sm text-yellow-900">
            <strong>⏱️ Remember:</strong> You will have 5 minutes to edit this record after creation. Please ensure all information is accurate.
        </p>
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
    <form action="{{ route('casual-labour-logs.store') }}" method="POST" class="bg-white rounded-xl border border-gray-200 p-8 shadow-sm hover:shadow-md transition-shadow">
        @csrf

        <input type="hidden" name="project_id" value="{{ $project->id }}">

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
                    value="{{ old('activity') }}"
                    placeholder="e.g., Foundation Work"
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
                    value="{{ old('labour_classification') }}"
                    placeholder="e.g., Unskilled Labor"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-400 focus:border-transparent shadow-sm {{ $errors->has('labour_classification') ? 'border-red-500' : '' }}"
                    required
                >
                @if ($errors->has('labour_classification'))
                <p class="text-red-600 text-sm mt-1">{{ $errors->first('labour_classification') }}</p>
                @endif
            </div>
        </div>

        <!-- Labour Cost Section -->
        <div class="bg-gray-50 rounded-lg p-6 mb-8 border border-gray-200 shadow-sm">
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
                        value="{{ old('number_of_workers', 1) }}"
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
                        <span class="absolute left-3 top-2 text-gray-600">Rwf&nbsp;</span>
                        <input 
                            type="number" 
                            id="wage" 
                            name="wage" 
                            value="{{ old('wage') }}"
                            step="0.01"
                            min="0"
                            placeholder=" 0.00"
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
                    <div class="w-full px-4 py-2 border border-gray-300 rounded-lg bg-gray-100 text-gray-700 font-semibold shadow-sm">
                        Rwf<span id="totalCost">0.00</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="flex items-center gap-4">
            <button type="submit" class="inline-flex items-center gap-2 px-4 py-2.5 bg-yellow-400 hover:bg-yellow-500 text-gray-900 font-semibold rounded-lg transition-all duration-200 shadow-sm hover:shadow-md">
                ✓ Create Labour Log
            </button>
            <a href="{{ route('casual-labour-logs.index', ['project_id' => $project->id]) }}" class="inline-flex items-center gap-2 px-4 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-900 font-semibold rounded-lg transition-all duration-200">
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
