@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Header -->
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-gray-900">Create Material Cost Log</h1>
        <p class="text-gray-600 mt-1">{{ $project->name }}</p>
    </div>

    <!-- Info Box -->
    <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 mb-6 shadow-sm">
        <p class="text-sm text-yellow-900">
            <strong>ℹ️ Note:</strong> This record will be recorded for <strong>{{ now()->format('F d, Y') }}</strong> and assigned to your account.
        </p>
    </div>

    <!-- Time Warning -->
    <div class="bg-amber-50 border border-amber-200 rounded-lg p-4 mb-6 shadow-sm">
        <p class="text-sm text-amber-900">
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
    <form action="{{ route('material-costs.store') }}" method="POST" class="bg-white rounded-xl border border-gray-200 p-8 shadow-sm hover:shadow-md transition-shadow">
        @csrf

        <input type="hidden" name="project_id" value="{{ $project->id }}">

        <!-- Activity -->
        <div class="mb-8"
            x-data="{
                open: false,
                search: '',
                selectedId: '{{ old('activity_id') }}',
                selectedName: (function(){
                    const map = @js(collect($activities)->keyBy('id')->map(fn($a) => $a->name));
                    const id = '{{ old('activity_id') }}';
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

        <!-- Material Name -->
        <div class="mb-8">
            <label for="material_name" class="block text-sm font-semibold text-gray-900 mb-2">
                Material Name *
            </label>
            <input 
                type="text" 
                id="material_name" 
                name="material_name" 
                value="{{ old('material_name') }}"
                placeholder="e.g., Reinforcement Steel"
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-400 focus:border-transparent shadow-sm {{ $errors->has('material_name') ? 'border-red-500' : '' }}"
                required
            >
            @if ($errors->has('material_name'))
            <p class="text-red-600 text-sm mt-1">{{ $errors->first('material_name') }}</p>
            @endif
        </div>

        <!-- Material Cost Section -->
        <div class="bg-yellow-50 rounded-lg p-6 mb-8 border border-yellow-200 shadow-sm">
            <h3 class="font-semibold text-gray-900 mb-6">💰 Material Cost Calculation</h3>
            
            <div class="grid grid-cols-3 gap-6">
                <!-- Quantity Used -->
                <div>
                    <label for="used_qty" class="block text-sm font-semibold text-gray-900 mb-2">
                        Quantity Used *
                    </label>
                    <input 
                        type="number" 
                        id="used_qty" 
                        name="used_qty" 
                        value="{{ old('used_qty') }}"
                        step="0.01"
                        min="0"
                        placeholder="0.00"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-400 focus:border-transparent shadow-sm {{ $errors->has('used_qty') ? 'border-red-500' : '' }}"
                        required
                    >
                    @if ($errors->has('used_qty'))
                    <p class="text-red-600 text-sm mt-1">{{ $errors->first('used_qty') }}</p>
                    @endif
                </div>

                <!-- Cost per Item -->
                <div>
                    <label for="cost_per_item" class="block text-sm font-semibold text-gray-900 mb-2">
                        Cost per Item *
                    </label>
                    <div class="relative">
                        <span class="absolute left-3 top-2 text-gray-600">Rwf</span>
                        <input 
                            type="number" 
                            id="cost_per_item" 
                            name="cost_per_item" 
                            value="{{ old('cost_per_item') }}"
                            step="0.01"
                            min="0"
                            placeholder="0.00"
                            class="w-full pl-7 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-400 focus:border-transparent shadow-sm {{ $errors->has('cost_per_item') ? 'border-red-500' : '' }}"
                            required
                        >
                    </div>
                    @if ($errors->has('cost_per_item'))
                    <p class="text-red-600 text-sm mt-1">{{ $errors->first('cost_per_item') }}</p>
                    @endif
                </div>

                <!-- Total Cost -->
                <div>
                    <label class="block text-sm font-semibold text-gray-900 mb-2">
                        Total Cost
                    </label>
                    <div class="w-full px-4 py-2 border border-gray-300 rounded-lg bg-yellow-100 text-yellow-700 font-semibold shadow-sm">
                        Rwf<span id="totalCost"> &nbsp;0.00</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="flex items-center gap-4">
            <button type="submit" class="inline-flex items-center justify-center gap-2 px-6 py-2.5 bg-yellow-400 hover:bg-yellow-500 text-gray-900 font-semibold rounded-lg transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-yellow-400 focus:ring-offset-2 shadow-sm hover:shadow-md">
                ✓ Create Cost Log
            </button>
            <a href="{{ route('material-costs.index', ['project_id' => $project->id]) }}" class="inline-flex items-center justify-center gap-2 px-6 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-900 font-semibold rounded-lg transition-all duration-200">
                Cancel
            </a>
        </div>
    </form>
</div>

<script>
    function updateTotalCost() {
        const qty = parseFloat(document.getElementById('used_qty').value) || 0;
        const cost = parseFloat(document.getElementById('cost_per_item').value) || 0;
        const total = qty * cost;
        document.getElementById('totalCost').textContent = total.toFixed(2);
    }

    document.getElementById('used_qty').addEventListener('input', updateTotalCost);
    document.getElementById('cost_per_item').addEventListener('input', updateTotalCost);
    
    // Initialize on page load
    updateTotalCost();
</script>
@endsection
