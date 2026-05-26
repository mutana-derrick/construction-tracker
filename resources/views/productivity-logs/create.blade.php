@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto">
    <!-- Header -->
    <div class="mb-6">
        <a href="{{ route('productivity-logs.index', ['project_id' => $project->id]) }}" class="inline-flex items-center gap-1 text-gray-600 hover:text-gray-900 font-medium transition-colors mb-3">
          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
          </svg>
          Back to Productivity Logs
        </a>
        <div class="flex items-center gap-3 mb-2">
          <svg class="w-8 h-8 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
          </svg>
          <h1 class="text-3xl font-bold text-gray-900">New Productivity Log</h1>
        </div>
        <p class="text-gray-600 mt-2">Project: <span class="font-semibold">{{ $project->name }}</span></p>
    </div>

    <!-- Form -->
    <form action="{{ route('productivity-logs.store') }}" method="POST" class="bg-white rounded-xl border border-gray-200 p-8 shadow-sm hover:shadow-md transition-shadow">
        @csrf
        <input type="hidden" name="project_id" value="{{ $project->id }}">

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

        <!-- Date Notice -->
        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
            <div class="flex gap-3">
              <svg class="w-5 h-5 text-blue-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
              </svg>
              <div>
                <p class="font-semibold text-blue-900">Date Information</p>
                <p class="text-sm text-blue-800 mt-1">This log will be recorded for <strong>{{ now()->format('F d, Y') }}</strong> (today)</p>
              </div>
            </div>
        </div>

        <!-- Activity & Equipment Section -->
        <div class="space-y-6 mb-8 pb-8 border-b border-gray-200">
          <h2 class="text-lg font-bold text-gray-900 flex items-center gap-2">
            <svg class="w-5 h-5 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            Work Details
          </h2>

          <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Activity -->
      <div
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
        class="relative"
      >
        <label class="block text-sm font-semibold text-gray-900 mb-2">Activity *</label>

        <input type="hidden" name="activity_id" x-model="selectedId" required>

        <button
          type="button"
          class="w-full px-4 py-2.5 border @error('activity_id') border-red-500 @else border-gray-200 @enderror rounded-lg bg-white text-left focus:outline-none focus:ring-2 focus:ring-yellow-400 focus:border-transparent transition-all shadow-sm"
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

        @error('activity_id') <div class="flex items-center gap-2 text-red-600 text-sm mt-2"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4v.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>{{ $message }}</div> @enderror
      </div>

            <!-- Equipment -->
            <div>
                <label for="equipment_name" class="block text-sm font-semibold text-gray-900 mb-2">Equipment *</label>
                <input type="text" id="equipment_name" name="equipment_name" class="w-full px-4 py-2.5 border @error('equipment_name') border-red-500 @else border-gray-200 @enderror rounded-lg focus:outline-none focus:ring-2 focus:ring-yellow-400 focus:border-transparent transition-all shadow-sm" 
                    placeholder="e.g., CAT 336 Excavator" 
                    value="{{ old('equipment_name') }}" required>
                @error('equipment_name') <div class="flex items-center gap-2 text-red-600 text-sm mt-2"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4v.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>{{ $message }}</div> @enderror
            </div>
          </div>
        </div>

        <!-- Productivity Metrics Section -->
        <div class="space-y-6 mb-8 pb-8 border-b border-gray-200">
          <h2 class="text-lg font-bold text-gray-900 flex items-center gap-2">
            <svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
            </svg>
            Productivity Metrics
          </h2>
          
          <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
              <!-- Workers -->
              <div>
                  <label for="workers" class="block text-sm font-semibold text-gray-900 mb-2">Number of Workers *</label>
                  <input type="number" id="workers" name="workers" class="w-full px-4 py-2.5 border @error('workers') border-red-500 @else border-gray-200 @enderror rounded-lg focus:outline-none focus:ring-2 focus:ring-yellow-400 focus:border-transparent transition-all shadow-sm" 
                      placeholder="1" min="1" value="{{ old('workers', 1) }}" required>
                  @error('workers') <div class="flex items-center gap-2 text-red-600 text-sm mt-2"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4v.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>{{ $message }}</div> @enderror
              </div>

              <!-- Output -->
              <div>
                  <label for="output" class="block text-sm font-semibold text-gray-900 mb-2">Output/Production *</label>
                  <input type="number" id="output" name="output" class="w-full px-4 py-2.5 border @error('output') border-red-500 @else border-gray-200 @enderror rounded-lg focus:outline-none focus:ring-2 focus:ring-yellow-400 focus:border-transparent transition-all shadow-sm" 
                      placeholder="0.00" step="0.01" min="0" value="{{ old('output') }}" required>
                  @error('output') <div class="flex items-center gap-2 text-red-600 text-sm mt-2"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4v.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>{{ $message }}</div> @enderror
              </div>

              <!-- Output per Worker -->
              <div>
                  <label class="block text-sm font-semibold text-gray-900 mb-2">Output per Worker</label>
                  <div class="w-full px-4 py-2.5 border border-gray-200 rounded-lg bg-gray-50 text-gray-700 font-semibold">
                      <span id="outputPerWorker">0.00</span> per worker
                  </div>
              </div>
          </div>
        </div>

        <!-- Comments Section -->
        <div class="space-y-6 mb-8">
          <!-- Comments -->
          <div>
              <label for="comment" class="block text-sm font-semibold text-gray-900 mb-2">Comments <span class="text-gray-500 font-normal">Optional</span></label>
              <textarea id="comment" name="comment" rows="4" class="w-full px-4 py-2.5 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-yellow-400 focus:border-transparent transition-all resize-none shadow-sm" 
                  placeholder="Add any additional notes about this productivity log...">{{ old('comment') }}</textarea>
              @error('comment') <div class="flex items-center gap-2 text-red-600 text-sm mt-2"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4v.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>{{ $message }}</div> @enderror
          </div>
        </div>

        <!-- Form Actions -->
        <div class="flex flex-col sm:flex-row gap-4 pt-6 border-t border-gray-200">
            <button type="submit" class="inline-flex items-center justify-center gap-2 px-6 py-2.5 bg-yellow-400 hover:bg-yellow-500 text-gray-900 font-semibold rounded-lg transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-yellow-400 focus:ring-offset-2 shadow-sm hover:shadow-md">
              <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
              </svg>
              Create Productivity Log
            </button>
            <a href="{{ route('productivity-logs.index', ['project_id' => $project->id]) }}" class="inline-flex items-center justify-center gap-2 px-6 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-900 font-semibold rounded-lg transition-all duration-200">
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
          <strong>Tip:</strong> This log can only be edited within 5 minutes of creation. Plan carefully before submitting!
        </p>
    </div>
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