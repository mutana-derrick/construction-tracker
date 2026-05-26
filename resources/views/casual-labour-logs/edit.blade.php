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
            <div
                x-data="{
                    open: false,
                    search: '',
                    selectedId: '{{ old('activity_id', $log->activity_id) }}',
                    selectedName: (function(){
                        const map = @js(collect($activities)->keyBy('id')->map(fn($a) => $a->name));
                        const id = '{{ old('activity_id', $log->activity_id) }}';
                        return id && map[id] ? map[id] : '';
                    })(),
                    activities: @js($activities->map(fn($a) => ['id' => $a->id, 'name' => $a->name])->values()),
                    get filtered() {
                        const q = this.search.trim().toLowerCase();
                        if (!q) return this.activities;
                        return this.activities.filter(a => a.name.toLowerCase().includes(q));
                    },
                    choose(a) {
                        this.selectedId = String(a.id);
                        this.selectedName = a.name;
                        this.search = '';
                        this.open = false;
                    },
                    clear() {
                        this.selectedId = '';
                        this.selectedName = '';
                        this.search = '';
                    }
                }"
                class="relative"
            >
                <label class="block text-sm font-semibold text-gray-900 mb-2">
                    Activity *
                </label>

                <input type="hidden" name="activity_id" x-model="selectedId" required>

                <button
                    type="button"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg bg-white text-left focus:ring-2 focus:ring-yellow-400 focus:border-transparent shadow-sm {{ $errors->has('activity_id') ? 'border-red-500' : '' }}"
                    @click="open = !open"
                    @keydown.escape.window="open = false"
                >
                    <span x-show="selectedName" x-text="selectedName" class="text-gray-900"></span>
                    <span x-show="!selectedName" class="text-gray-500">Select an activity</span>
                </button>

                <div
                    x-show="open"
                    x-transition
                    @click.outside="open = false"
                    class="absolute z-20 mt-2 w-full bg-white border border-gray-200 rounded-lg shadow-lg"
                >
                    <div class="p-2 border-b border-gray-200">
                        <input
                            type="text"
                            x-model="search"
                            placeholder="Search activities..."
                            class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-yellow-400 focus:border-transparent"
                        />
                    </div>

                    <ul class="max-h-56 overflow-auto py-1">
                        <template x-for="a in filtered" :key="a.id">
                            <li>
                                <button
                                    type="button"
                                    class="w-full px-4 py-2 text-left hover:bg-yellow-50 flex items-center justify-between"
                                    @click="choose(a)"
                                >
                                    <span x-text="a.name" class="text-gray-900"></span>
                                    <span x-show="selectedId === String(a.id)" class="text-yellow-600 font-semibold">Selected</span>
                                </button>
                            </li>
                        </template>

                        <li x-show="filtered.length === 0" class="px-4 py-2 text-sm text-gray-500">
                            No activities found.
                        </li>
                    </ul>

                    <div class="p-2 border-t border-gray-200 flex justify-end">
                        <button type="button" class="text-sm text-gray-600 hover:text-gray-900" @click="clear()">
                            Clear
                        </button>
                    </div>
                </div>

                @if ($errors->has('activity_id'))
                <p class="text-red-600 text-sm mt-1">{{ $errors->first('activity_id') }}</p>
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
