@extends('layouts.app')

@section('page-title', 'Edit Project')

@section('content')
  <div class="max-w-2xl mx-auto">
    <div class="mb-6">
      <a href="{{ route('projects.show', $project) }}" class="inline-flex items-center gap-1 text-gray-600 hover:text-gray-900 font-medium transition-colors">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
        </svg>
        Back to Project
      </a>
    </div>

    <div class="bg-white rounded-xl border border-gray-200 p-8 shadow-sm hover:shadow-md transition-shadow">
      <div class="mb-8">
        <div class="flex items-center gap-3 mb-2">
          <svg class="w-8 h-8 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
          </svg>
          <h1 class="text-3xl font-bold text-gray-900">Edit Project</h1>
        </div>
        <p class="text-gray-600 mt-2">Update project information and details.</p>
      </div>

      <form action="{{ route('projects.update', $project) }}" method="POST" class="space-y-6">
        @csrf
        @method('PUT')

        <!-- Project Name -->
        <div>
          <label for="name" class="block text-sm font-semibold text-gray-900 mb-2">Project Name *</label>
          <input
            type="text"
            id="name"
            name="name"
            class="w-full px-4 py-2.5 border @error('name') border-red-500 @else border-gray-200 @enderror rounded-lg focus:outline-none focus:ring-2 focus:ring-yellow-400 focus:border-transparent transition-all shadow-sm"
            placeholder="e.g., Highway Expansion Project"
            value="{{ old('name', $project->name) }}"
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
            value="{{ old('location', $project->location) }}"
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
          >{{ old('description', $project->description) }}</textarea>
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
          <button type="submit" class="inline-flex items-center justify-center gap-2 px-6 py-2.5 bg-yellow-400 hover:bg-yellow-500 text-gray-900 font-semibold rounded-lg transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-yellow-400 focus:ring-offset-2 shadow-sm hover:shadow-md">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
            </svg>
            Update Project
          </button>
          <a href="{{ route('projects.show', $project) }}" class="inline-flex items-center justify-center gap-2 px-6 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-900 font-semibold rounded-lg transition-all duration-200">
            Cancel
          </a>
        </div>
      </form>
    </div>
  </div>
@endsection
@endsection
