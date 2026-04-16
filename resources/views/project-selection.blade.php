<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <title>Select Project - ConstructTrack</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
  </head>
  <body class=" bg-gray-100">
  <div class="min-h-screen bg-gray-100 py-8 md:py-12 px-4 sm:px-6 lg:px-8">
    <!-- Header -->
    <div class="max-w-6xl mx-auto mb-8 md:mb-12">
      <div class="text-center mb-8">
        <div class="inline-flex items-center justify-center w-16 h-16 bg-yellow-400 rounded-xl mb-4 shadow-lg">
          <span class="text-3xl">⚙️</span>
        </div>
        <h1 class="text-3xl md:text-4xl font-bold text-gray-900 mb-2">ConstructTrack</h1>
        <p class="text-base md:text-lg text-gray-600">Construction Productivity Tracking</p>
      </div>

      <div class="bg-white border border-gray-200 rounded-2xl p-5 md:p-8 shadow-sm mb-2">
        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 mb-2">
          <div class="flex-1 min-w-0">
            <h2 class="text-xl md:text-3xl font-bold text-gray-900 truncate">Welcome, {{ auth()->user()->name }}! 👋</h2>
            <p class="text-gray-600 mt-1 text-sm md:text-base">Select a project to get started or create a new one</p>
          </div>
          <form method="POST" action="{{ route('logout') }}" class="shrink-0">
            @csrf
            <button
              type="submit"
              class="inline-flex items-center justify-center gap-2 px-4 py-2.5 text-gray-600 hover:text-gray-900 font-medium rounded-lg hover:bg-gray-100 transition-all whitespace-nowrap"
            >
              <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
              </svg>
              Logout
            </button>
          </form>
        </div>

        @if (session('success'))
          <div class="rounded-lg bg-green-50 border border-green-200 p-4 flex items-start gap-3">
            <svg class="w-5 h-5 text-green-600 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            <div>
              <p class="text-sm font-semibold text-green-900">Success</p>
              <p class="text-sm text-green-700 mt-0.5">{{ session('success') }}</p>
            </div>
          </div>
        @endif
      </div>
    </div>

    <!-- Projects Grid -->
    <div class="max-w-6xl mx-auto">
      @if ($projectCount > 0)
        <div class="mb-8">
          <div class="flex items-center justify-between gap-4 mb-4">
            <h3 class="text-xl md:text-2xl font-semibold text-gray-900">
              Your Projects <span class="text-sm font-normal text-gray-500">({{ $projectCount }})</span>
            </h3>
            @can('create', \App\Models\Project::class)
              <a
                href="{{ route('projects.create') }}"
                class="inline-flex items-center justify-center gap-2 px-4 py-2 bg-yellow-400 hover:bg-yellow-500 text-gray-900 font-semibold rounded-lg transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-yellow-400 focus:ring-offset-2 shadow-sm hover:shadow-md whitespace-nowrap shrink-0"
              >
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                <span class="hidden sm:inline">Create New Project</span>
                <span class="sm:hidden">Create</span>
              </a>
            @endcan
          </div>

          {{-- 1 col on xs, 2 col on sm+, 3 col on lg+ --}}
          <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 md:gap-6">
            @foreach ($projects as $project)
              <form action="{{ route('projects.select.post', $project) }}" method="POST" class="group h-full">
                @csrf
                <button type="submit" class="w-full text-left h-full">
                  <div class="bg-white border border-gray-200 rounded-xl p-5 hover:shadow-md transition-all hover:border-yellow-300 h-full flex flex-col">

                    <!-- Project Header -->
                    <div class="flex items-start gap-3 mb-3">
                      <div class="flex-1 min-w-0">
                        <h4 class="text-base font-semibold text-gray-900 group-hover:text-yellow-600 transition-colors leading-snug">
                          {{ $project->name }}
                        </h4>
                        <p class="text-xs text-gray-500 mt-1 flex items-center gap-1 truncate">
                          <span>📍</span>
                          <span class="truncate">{{ $project->location ?? 'No location' }}</span>
                        </p>
                      </div>
                      <div class="inline-flex items-center justify-center w-9 h-9 bg-yellow-50 rounded-lg group-hover:bg-yellow-100 transition-colors shrink-0">
                        <svg class="w-5 h-5 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"></path>
                        </svg>
                      </div>
                    </div>

                    <!-- Project Description -->
                    @if ($project->description)
                      <p class="text-sm text-gray-600 mb-4 line-clamp-2 leading-relaxed">{{ $project->description }}</p>
                    @else
                      <p class="text-sm text-gray-400 italic mb-4">No description provided</p>
                    @endif

                    <!-- Project Stats — flex row so labels never overlap -->
                    <div class="flex items-stretch divide-x divide-gray-100 border-t border-gray-100 pt-4 mb-4">
                      <div class="flex-1 flex flex-col items-center px-2 first:pl-0 last:pr-0">
                        <p class="text-lg font-bold text-gray-900 leading-none">{{ $project->equipmentLogs->count() }}</p>
                        <p class="text-xs text-gray-500 mt-1 text-center leading-tight">Equipment</p>
                      </div>
                      <div class="flex-1 flex flex-col items-center px-2">
                        <p class="text-lg font-bold text-gray-900 leading-none">{{ $project->equipmentCosts->count() }}</p>
                        <p class="text-xs text-gray-500 mt-1 text-center leading-tight">Cost Records</p>
                      </div>
                      <div class="flex-1 flex flex-col items-center px-2 first:pl-0 last:pr-0">
                        <p class="text-lg font-bold text-gray-900 leading-none">
                          ${{ number_format($project->equipmentCosts->sum('total_cost'), 0) }}
                        </p>
                        <p class="text-xs text-gray-500 mt-1 text-center leading-tight">Total</p>
                      </div>
                    </div>

                    <!-- Status Badge -->
                    <div class="flex items-center gap-2 pt-3 border-t border-gray-100 mt-auto">
                      <div class="w-2 h-2 bg-green-500 rounded-full shrink-0"></div>
                      <span class="text-xs text-gray-500 truncate">Created {{ $project->created_at->format('M d, Y') }}</span>
                    </div>
                  </div>
                </button>
              </form>
            @endforeach
          </div>
        </div>
      @else
        <!-- Empty State -->
        <div class="bg-white border border-gray-200 rounded-xl p-8 md:p-12 text-center shadow-sm">
          <div class="inline-flex items-center justify-center w-16 h-16 bg-blue-50 rounded-full mb-4">
            <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
            </svg>
          </div>
          <h3 class="text-xl font-semibold text-gray-900 mb-2">No projects yet</h3>
          <p class="text-gray-600 mb-6 max-w-md mx-auto">Create your first project to start tracking construction productivity and costs.</p>
        </div>
      @endif
    </div>

  </body>
</html>