<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <title>@yield('title', 'ConstructTrack - Authentication')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
  </head>
  <body class="bg-gradient-to-br from-gray-50 to-gray-100">
    <div class="min-h-screen flex flex-col items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
      <!-- Logo and Branding -->
      <div class="mb-8 text-center">
        <h1 class="text-4xl font-bold text-gray-900">
          <span class="text-primary-400">⚙️</span> ConstructTrack
        </h1>
        <p class="mt-2 text-gray-600">Construction Productivity Tracking System</p>
      </div>

      <!-- Main Content -->
      <main class="w-full max-w-md">
        @yield('content')
      </main>

      <!-- Footer -->
      <footer class="mt-8 text-center text-sm text-gray-600">
        <p>&copy; {{ date('Y') }} ConstructTrack. All rights reserved.</p>
      </footer>
    </div>
  </body>
</html>
