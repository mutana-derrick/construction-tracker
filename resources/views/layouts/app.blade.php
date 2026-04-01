<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <title>@yield('title', 'Construction Tracker')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
  </head>
  <body class="bg-gray-50" x-data="{ sidebarOpen: false }">
    <div class="flex h-screen bg-gray-50">
      <!-- Sidebar -->
      <aside class="w-64 bg-white border-r border-gray-200 hidden md:flex flex-col">
        <!-- Logo -->
        <div class="p-6 border-b border-gray-200">
          <div class="flex items-center gap-3">
            <div class="inline-flex items-center justify-center w-10 h-10 bg-primary-400 rounded-lg">
              <svg class="w-6 h-6 text-gray-900" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
              </svg>
            </div>
            <div class="flex-1">
              <span class="text-lg font-bold text-gray-900 block">ConstructTrack</span>
              @php
                $projectId = request()->query('project_id') ?? session('selected_project_id');
                $currentProject = $projectId ? \App\Models\Project::find($projectId) : null;
              @endphp
              @if ($currentProject)
                <span class="text-xs text-gray-600 block truncate">{{ $currentProject->name }}</span>
              @endif
            </div>
          </div>
        </div>

        <!-- Navigation -->
        <nav class="flex-1 px-3 py-6 space-y-2 overflow-y-auto">
          <a
            href="{{ route('dashboard') }}"
            class="flex items-center gap-3 px-4 py-2.5 rounded-lg text-sm font-medium transition-all {{ request()->routeIs('dashboard') ? 'bg-primary-50 text-primary-700' : 'text-gray-700 hover:bg-gray-100' }}"
          >
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
            </svg>
            Dashboard
          </a>

          <!-- Projects Section -->
          <div class="pt-6">
            <h3 class="px-4 py-2 text-xs font-semibold text-gray-600 uppercase tracking-wider">
              Projects
            </h3>
            <a
              href="{{ route('projects.index') }}"
              class="flex items-center gap-3 px-4 py-2.5 rounded-lg text-sm font-medium transition-all {{ request()->routeIs('projects.*') ? 'bg-primary-50 text-primary-700' : 'text-gray-700 hover:bg-gray-100' }}"
            >
              <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"></path>
              </svg>
              Projects
            </a>
          </div>

          <!-- Daily Logs Section - Only show if in project context -->
          @php
            $projectId = request()->query('project_id') ?? (request()->route('project') ? request()->route('project')->id : null);
            $showLogs = $projectId !== null;
          @endphp

          @if ($showLogs || request()->routeIs(['equipment-logs.*', 'equipment-costs.*', 'productivity-logs.*', 'casual-labour-logs.*', 'material-usage.*', 'material-costs.*']))
            <div class="pt-6">
              <h3 class="px-4 py-2 text-xs font-semibold text-gray-600 uppercase tracking-wider">
                Daily Logs
              </h3>
              <a
                href="{{ route('equipment-logs.index', ['project_id' => $projectId]) }}"
                class="flex items-center gap-3 px-4 py-2.5 rounded-lg text-sm font-medium transition-all {{ request()->routeIs('equipment-logs.*') ? 'bg-primary-50 text-primary-700' : 'text-gray-700 hover:bg-gray-100' }}"
              >
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                </svg>
                Equipment
              </a>
              <a
                href="{{ route('equipment-costs.index', ['project_id' => $projectId]) }}"
                class="flex items-center gap-3 px-4 py-2.5 rounded-lg text-sm font-medium transition-all {{ request()->routeIs('equipment-costs.*') ? 'bg-primary-50 text-primary-700' : 'text-gray-700 hover:bg-gray-100' }}"
              >
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                Equipment Cost
              </a>
              <a
                href="{{ route('productivity-logs.index', ['project_id' => $projectId]) }}"
                class="flex items-center gap-3 px-4 py-2.5 rounded-lg text-sm font-medium transition-all {{ request()->routeIs('productivity-logs.*') ? 'bg-primary-50 text-primary-700' : 'text-gray-700 hover:bg-gray-100' }}"
              >
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                </svg>
                Productivity
              </a>
              <a
                href="{{ route('casual-labour-logs.index', ['project_id' => $projectId]) }}"
                class="flex items-center gap-3 px-4 py-2.5 rounded-lg text-sm font-medium transition-all {{ request()->routeIs('casual-labour-logs.*') ? 'bg-primary-50 text-primary-700' : 'text-gray-700 hover:bg-gray-100' }}"
              >
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292 4 4 0 010-5.292M15 12H9m6 0h-6m6 0a4 4 0 110 5.292m-6-5.292a4 4 0 010 5.292M9 20h6a2 2 0 002-2V7a2 2 0 00-2-2H9a2 2 0 00-2 2v11a2 2 0 002 2z"></path>
                </svg>
                Labour Cost
              </a>
              <a
                href="{{ route('material-usage.index', ['project_id' => $projectId]) }}"
                class="flex items-center gap-3 px-4 py-2.5 rounded-lg text-sm font-medium transition-all {{ request()->routeIs('material-usage.*') ? 'bg-primary-50 text-primary-700' : 'text-gray-700 hover:bg-gray-100' }}"
              >
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10L4 11"></path>
                </svg>
                Material Usage
              </a>
              <a
                href="{{ route('material-costs.index', ['project_id' => $projectId]) }}"
                class="flex items-center gap-3 px-4 py-2.5 rounded-lg text-sm font-medium transition-all {{ request()->routeIs('material-costs.*') ? 'bg-primary-50 text-primary-700' : 'text-gray-700 hover:bg-gray-100' }}"
              >
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                Material Cost
              </a>
            </div>
          @else
            <div class="pt-6 px-4 py-3 bg-blue-50 rounded-lg border border-blue-200">
              <p class="text-xs text-blue-700 font-medium">💡 Select a project to view and manage daily logs</p>
            </div>
          @endif

          <!-- Reports Section -->
          <div class="pt-6">
            <h3 class="px-4 py-2 text-xs font-semibold text-gray-600 uppercase tracking-wider">
              Reports
            </h3>
            <a
              href="{{ route('reports.daily') }}"
              class="flex items-center gap-3 px-4 py-2.5 rounded-lg text-sm font-medium transition-all {{ request()->routeIs('reports.daily') ? 'bg-primary-50 text-primary-700' : 'text-gray-700 hover:bg-gray-100' }}"
            >
              <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
              </svg>
              Daily Report
            </a>
            <a
              href="{{ route('reports.monthly') }}"
              class="flex items-center gap-3 px-4 py-2.5 rounded-lg text-sm font-medium transition-all {{ request()->routeIs('reports.monthly') ? 'bg-primary-50 text-primary-700' : 'text-gray-700 hover:bg-gray-100' }}"
            >
              <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
              </svg>
              Monthly Report
            </a>
          </div>
        </nav>

        <!-- Switch Project -->
        <div class="px-3 py-4 border-t border-gray-200">
          <a
            href="{{ route('projects.select') }}"
            class="flex items-center gap-3 px-4 py-2.5 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-100 transition-all"
          >
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V3"></path>
            </svg>
            Switch Project
          </a>
        </div>

        <!-- User Profile -->
        <div class="p-4 border-t border-gray-200">
          <div class="flex items-center justify-between px-4 py-3 bg-gray-50 rounded-lg">
            <div class="min-w-0">
              <p class="text-sm font-semibold text-gray-900 truncate">{{ auth()->user()->name }}</p>
              <p class="text-xs text-gray-600 truncate">{{ auth()->user()->email }}</p>
            </div>
            <form method="POST" action="{{ route('logout') }}" class="inline">
              @csrf
              <button
                type="submit"
                class="ml-2 text-gray-400 hover:text-gray-600 transition-colors"
                title="Logout"
              >
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                </svg>
              </button>
            </form>
          </div>
        </div>
      </aside>

      <!-- Main Content -->
      <main class="flex-1 flex flex-col overflow-hidden">
        <!-- Top Bar -->
        <header class="bg-white border-b border-gray-200 px-6 py-4">
          <div class="flex items-center justify-between">
            <div class="flex items-center gap-4">
              <button
                @click="sidebarOpen = !sidebarOpen"
                class="md:hidden text-gray-600 hover:text-gray-900 transition-colors"
              >
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                </svg>
              </button>
              <h1 class="text-2xl font-bold text-gray-900">
                @yield('page-title', 'Dashboard')
              </h1>
            </div>

            <div class="text-sm text-gray-600 flex items-center gap-2">
              <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
              </svg>
              {{ auth()->user()->name }}
            </div>
          </div>
        </header>

        <!-- Content Area -->
        <div class="flex-1 overflow-auto bg-gray-50">
          <div class="p-8 max-w-7xl mx-auto">
            <!-- Error Messages -->
            @if ($errors->any())
              <div class="mb-6 rounded-xl bg-red-50 border border-red-200 p-6">
                <h4 class="font-semibold text-red-900 mb-3">Please correct these errors:</h4>
                <ul class="space-y-2">
                  @foreach ($errors->all() as $error)
                    <li class="text-sm text-red-700">• {{ $error }}</li>
                  @endforeach
                </ul>
              </div>
            @endif

            <!-- Success Messages -->
            @if (session('success'))
              <div class="mb-6 rounded-xl bg-green-50 border border-green-200 p-4 text-sm text-green-700 font-medium flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                {{ session('success') }}
              </div>
            @endif

            <!-- Error Messages -->
            @if (session('error'))
              <div class="mb-6 rounded-xl bg-red-50 border border-red-200 p-4 text-sm text-red-700 font-medium flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                {{ session('error') }}
              </div>
            @endif

            @yield('content')
          </div>
        </div>
      </main>
    </div>

    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
  </body>
</html>
