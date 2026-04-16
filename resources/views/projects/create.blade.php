@extends('layouts.app')

@section('page-title', 'New Project')

@section('content')
  <div class="max-w-2xl mx-auto">
    <div class="mb-6">
      <a href="{{ route('projects.index') }}" class="inline-flex items-center gap-1 text-gray-600 hover:text-gray-900 font-medium transition-colors">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
        </svg>
        Back to Projects
      </a>
    </div>

    <div class="bg-white rounded-xl border border-gray-200 p-8 shadow-sm hover:shadow-md transition-shadow">
      <div class="mb-8">
        <div class="flex items-center gap-3 mb-2">
          <svg class="w-8 h-8 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
          </svg>
          <h1 class="text-3xl font-bold text-gray-900">Create New Project</h1>
        </div>
        <p class="text-gray-600 mt-2">Set up a new construction project to track productivity, equipment, and costs.</p>
      </div>

      <form action="{{ route('projects.store') }}" method="POST" class="space-y-6">
        @csrf

        <!-- Project Name -->
        <div>
          <label for="name" class="block text-sm font-semibold text-gray-900 mb-2">Project Name *</label>
          <input
            type="text"
            id="name"
            name="name"
            class="w-full px-4 py-2.5 border @error('name') border-red-500 @else border-gray-200 @enderror rounded-lg focus:outline-none focus:ring-2 focus:ring-yellow-400 focus:border-transparent transition-all shadow-sm"
            placeholder="e.g., Highway Expansion Project"
            value="{{ old('name') }}"
            required
          />
          @error('name')
            <div class="flex items-center gap-2 text-red-600 text-sm mt-2">
              <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4v.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
              </svg>
              {{ $message }}
            </div>
          @enderror
        </div>

        <!-- Location -->
        <div>
          <label for="location" class="block text-sm font-semibold text-gray-900 mb-2">Location *</label>
          <input
            type="text"
            id="location"
            name="location"
            class="w-full px-4 py-2.5 border @error('location') border-red-500 @else border-gray-200 @enderror rounded-lg focus:outline-none focus:ring-2 focus:ring-yellow-400 focus:border-transparent transition-all shadow-sm"
            placeholder="e.g., Route 5, Northern Region"
            value="{{ old('location') }}"
            required
          />
          @error('location')
            <div class="flex items-center gap-2 text-red-600 text-sm mt-2">
              <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4v.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
              </svg>
              {{ $message }}
            </div>
          @enderror
        </div>

        <!-- Description -->
        <div>
          <label for="description" class="block text-sm font-semibold text-gray-900 mb-2">Description</label>
          <textarea
            id="description"
            name="description"
            rows="4"
            class="w-full px-4 py-2.5 border @error('description') border-red-500 @else border-gray-200 @enderror rounded-lg focus:outline-none focus:ring-2 focus:ring-yellow-400 focus:border-transparent transition-all resize-none shadow-sm"
            placeholder="Project details, objectives, scope..."
          >{{ old('description') }}</textarea>
          @error('description')
            <div class="flex items-center gap-2 text-red-600 text-sm mt-2">
              <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4v.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
              </svg>
              {{ $message }}
            </div>
          @enderror
        </div>

        <!-- Form Actions -->
        <div class="flex flex-col sm:flex-row gap-4 pt-6 border-t border-gray-200">
          <button type="submit" class="inline-flex items-center justify-center gap-2 px-6 py-2.5 bg-yellow-400 hover:bg-yellow-500 text-gray-900 font-semibold rounded-lg transition-all duration-200 shadow-sm hover:shadow-md focus:outline-none focus:ring-2 focus:ring-yellow-400 focus:ring-offset-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
            </svg>
            Create Project
          </button>
          <a href="{{ route('projects.index') }}" class="inline-flex items-center justify-center gap-2 px-6 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-900 font-semibold rounded-lg transition-all duration-200 shadow-sm">
            Cancel
          </a>
        </div>
      </form>
    </div>
  </div>
@endsection
