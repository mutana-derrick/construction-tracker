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
    <div class="flex h-screen bg-gray-100">
      <!-- Sidebar -->
      <aside class="w-64 bg-white border-r border-gray-200 hidden md:flex flex-col">
        <div class="p-6 border-b border-gray-200">
          <h1 class="text-xl font-bold text-gray-900">
            <span class="text-primary-400">⚙️</span> ConstructTrack
          </h1>
        </div>

        <nav class="flex-1 px-4 py-6 space-y-2">
          <a
            href="{{ route('dashboard') }}"
            class="block px-4 py-2 rounded-lg {{ request()->routeIs('dashboard') ? 'bg-primary-50 text-primary-600 font-medium' : 'text-gray-700 hover:bg-gray-100' }} transition-colors"
          >
            📊 Dashboard
          </a>

          <div class="pt-4 border-t border-gray-200">
            <p class="px-4 py-2 text-xs font-semibold text-gray-600 uppercase">
              Projects
            </p>
            <a
              href="{{ route('projects.index') }}"
              class="block px-4 py-2 rounded-lg {{ request()->routeIs('projects.*') ? 'bg-primary-50 text-primary-600 font-medium' : 'text-gray-700 hover:bg-gray-100' }} transition-colors"
            >
              📁 Projects
            </a>
          </div>

          <div class="pt-4 border-t border-gray-200">
            <p class="px-4 py-2 text-xs font-semibold text-gray-600 uppercase">
              Daily Logs
            </p>
            <a
              href="{{ route('equipment-logs.index') }}"
              class="block px-4 py-2 rounded-lg {{ request()->routeIs('equipment-logs.*') ? 'bg-primary-50 text-primary-600 font-medium' : 'text-gray-700 hover:bg-gray-100' }} transition-colors text-sm"
            >
              🚜 Equipment
            </a>
            <a
              href="{{ route('equipment-costs.index') }}"
              class="block px-4 py-2 rounded-lg {{ request()->routeIs('equipment-costs.*') ? 'bg-primary-50 text-primary-600 font-medium' : 'text-gray-700 hover:bg-gray-100' }} transition-colors text-sm"
            >
              💰 Equipment Cost
            </a>
            <a
              href="{{ route('productivity-logs.index') }}"
              class="block px-4 py-2 rounded-lg {{ request()->routeIs('productivity-logs.*') ? 'bg-primary-50 text-primary-600 font-medium' : 'text-gray-700 hover:bg-gray-100' }} transition-colors text-sm"
            >
              📈 Productivity
            </a>
            <a
              href="{{ route('casual-labour-logs.index') }}"
              class="block px-4 py-2 rounded-lg {{ request()->routeIs('casual-labour-logs.*') ? 'bg-primary-50 text-primary-600 font-medium' : 'text-gray-700 hover:bg-gray-100' }} transition-colors text-sm"
            >
              👥 Labour Cost
            </a>
            <a
              href="{{ route('material-usage.index') }}"
              class="block px-4 py-2 rounded-lg {{ request()->routeIs('material-usage.*') ? 'bg-primary-50 text-primary-600 font-medium' : 'text-gray-700 hover:bg-gray-100' }} transition-colors text-sm"
            >
              📦 Material Usage
            </a>
            <a
              href="{{ route('material-costs.index') }}"
              class="block px-4 py-2 rounded-lg {{ request()->routeIs('material-costs.*') ? 'bg-primary-50 text-primary-600 font-medium' : 'text-gray-700 hover:bg-gray-100' }} transition-colors text-sm"
            >
              💲 Material Cost
            </a>
          </div>

          <div class="pt-4 border-t border-gray-200">
            <p class="px-4 py-2 text-xs font-semibold text-gray-600 uppercase">
              Reports
            </p>
            <a
              href="{{ route('reports.daily') }}"
              class="block px-4 py-2 rounded-lg {{ request()->routeIs('reports.daily') ? 'bg-primary-50 text-primary-600 font-medium' : 'text-gray-700 hover:bg-gray-100' }} transition-colors text-sm"
            >
              📅 Daily Report
            </a>
            <a
              href="{{ route('reports.monthly') }}"
              class="block px-4 py-2 rounded-lg {{ request()->routeIs('reports.monthly') ? 'bg-primary-50 text-primary-600 font-medium' : 'text-gray-700 hover:bg-gray-100' }} transition-colors text-sm"
            >
              📆 Monthly Report
            </a>
          </div>
        </nav>

        <!-- User Menu -->
        <div class="p-4 border-t border-gray-200">
          <div class="flex items-center justify-between">
            <div class="flex-1">
              <p class="text-sm font-medium text-gray-900">{{ auth()->user()->name }}</p>
              <p class="text-xs text-gray-600">{{ auth()->user()->email }}</p>
            </div>
            <form method="POST" action="{{ route('logout') }}" class="inline">
              @csrf
              <button
                type="submit"
                class="text-gray-400 hover:text-gray-600"
                title="Logout"
              >
                🚪
              </button>
            </form>
          </div>
        </div>
      </aside>

      <!-- Main Content -->
      <main class="flex-1 flex flex-col overflow-hidden">
        <!-- Top Bar -->
        <header class="bg-white border-b border-gray-200 px-6 py-4 flex items-center justify-between">
          <div class="flex items-center gap-4">
            <button
              @click="sidebarOpen = !sidebarOpen"
              class="md:hidden text-gray-600 hover:text-gray-900"
            >
              ☰
            </button>
            <h2 class="text-lg font-semibold text-gray-900">
              @yield('page-title', 'Dashboard')
            </h2>
          </div>

          <div class="flex items-center gap-4">
            <span class="text-sm text-gray-600">
              @if(auth()->user())
                {{ auth()->user()->name }}
              @endif
            </span>
          </div>
        </header>

        <!-- Content Area -->
        <div class="flex-1 overflow-auto">
          <div class="p-6">
            <!-- Flash Messages -->
            @if ($errors->any())
              <div class="alert alert-error mb-6">
                <h4 class="font-medium mb-2">Please correct the following errors:</h4>
                <ul class="list-disc list-inside">
                  @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                  @endforeach
                </ul>
              </div>
            @endif

            @if (session('success'))
              <div class="alert alert-success mb-6">
                {{ session('success') }}
              </div>
            @endif

            @if (session('error'))
              <div class="alert alert-error mb-6">
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
