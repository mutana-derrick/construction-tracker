@extends('layouts.app')

@section('page-title', 'Edit Project')

@section('content')
  <div class="max-w-2xl mx-auto">
    <div class="mb-6">
      <a href="{{ route('projects.show', $project) }}" class="text-primary-600 hover:text-primary-700">
        ← Back to Project
      </a>
    </div>

    <div class="card">
      <h1 class="text-2xl font-bold text-gray-900 mb-6">Edit Project</h1>

      <form action="{{ route('projects.update', $project) }}" method="POST" class="space-y-6">
        @csrf
        @method('PUT')

        <!-- Project Name -->
        <div>
          <label for="name" class="form-label">Project Name</label>
          <input
            type="text"
            id="name"
            name="name"
            class="form-input @error('name') border-red-500 @enderror"
            placeholder="e.g., Highway Expansion Project"
            value="{{ old('name', $project->name) }}"
            required
          />
          @error('name')
            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
          @enderror
        </div>

        <!-- Location -->
        <div>
          <label for="location" class="form-label">Location</label>
          <input
            type="text"
            id="location"
            name="location"
            class="form-input @error('location') border-red-500 @enderror"
            placeholder="e.g., Route 5, Northern Region"
            value="{{ old('location', $project->location) }}"
            required
          />
          @error('location')
            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
          @enderror
        </div>

        <!-- Description -->
        <div>
          <label for="description" class="form-label">Description</label>
          <textarea
            id="description"
            name="description"
            rows="4"
            class="form-input @error('description') border-red-500 @enderror"
            placeholder="Project details, objectives, scope..."
          >{{ old('description', $project->description) }}</textarea>
          @error('description')
            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
          @enderror
        </div>

        <!-- Form Actions -->
        <div class="flex gap-4 pt-6 border-t border-gray-200">
          <button type="submit" class="btn-primary">
            ✓ Update Project
          </button>
          <a href="{{ route('projects.show', $project) }}" class="btn-secondary">
            Cancel
          </a>
        </div>
      </form>
    </div>
  </div>
@endsection
