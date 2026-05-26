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
        $editDeadline = $log->created_at->copy()->addMinutes(5);
        $isEditable = $editDeadline->isFuture();
        $minutesLeft = max(0, now()->diffInMinutes($editDeadline, false));
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
    <form action="{{ route('material-usage.update', $log) }}" method="POST" class="bg-white rounded-xl border border-gray-200 p-8 shadow-sm hover:shadow-md transition-shadow">
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
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-400 focus:border-transparent shadow-sm {{ $errors->has('material_name') ? 'border-red-500' : '' }}"
                    required
                >
                @if ($errors->has('material_name'))
                <p class="text-red-600 text-sm mt-1">{{ $errors->first('material_name') }}</p>
                @endif
            </div>

            <!-- Right Column -->
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
        </div>

        <!-- Material Usage Section -->
        <div class="bg-yellow-50 rounded-lg p-6 mb-8 border border-yellow-200 shadow-sm">
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
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-400 focus:border-transparent shadow-sm {{ $errors->has('planned_qty') ? 'border-red-500' : '' }}"
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
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-400 focus:border-transparent shadow-sm {{ $errors->has('used_qty') ? 'border-red-500' : '' }}"
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
            <div class="mt-6 bg-yellow-50 border border-yellow-200 rounded-lg p-4 shadow-sm">
                <p class="text-sm text-blue-900">
                    <strong>📊 Status:</strong> <span id="efficiencyStatus">{{ $log->planned_qty - $log->used_qty > 0 ? '✓ Under budget' : ($log->planned_qty - $log->used_qty < 0 ? '⚠️ Over budget' : '◆ Exact usage') }}</span>
                </p>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="flex items-center gap-4">
            <button type="submit" class="inline-flex items-center justify-center gap-2 px-6 py-2.5 bg-yellow-400 hover:bg-yellow-500 text-gray-900 font-semibold rounded-lg transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-yellow-400 focus:ring-offset-2 shadow-sm hover:shadow-md">
                ✓ Save Changes
            </button>
            <a href="{{ route('material-usage.show', $log) }}" class="inline-flex items-center justify-center gap-2 px-6 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-900 font-semibold rounded-lg transition-all duration-200">
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
