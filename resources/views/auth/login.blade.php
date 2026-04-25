<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <meta name="csrf-token" content="{{ csrf_token() }}" />
  <title>Login - ConstructTrack</title>
  @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50 min-h-screen flex items-center justify-center px-4 py-12">
  <div class="w-full max-w-md">
    <!-- Logo Section -->
    <div class="mb-12 text-center">
      <div class="inline-flex items-center justify-center w-14 h-14 bg-yellow-400 rounded-xl mb-4 shadow-sm">
        <span class="text-2xl">⚙️</span>
      </div>
      <h1 class="text-3xl font-bold text-gray-900 tracking-tight">
        ConstructTrack
      </h1>
      <p class="mt-2 text-sm text-gray-600">Construction Productivity Tracking</p>
    </div>

    <!-- Login Form -->
    <div class="bg-white border border-gray-200 rounded-xl p-8 shadow-sm">
      <div class="mb-8">
        <h2 class="text-2xl font-bold text-gray-900">Welcome back</h2>
        <p class="mt-1 text-sm text-gray-600">Sign in to your account to continue</p>
      </div>

      @if ($errors->any())
        <div class="mb-6 rounded-lg bg-red-50 border border-red-200 p-4">
          <p class="text-sm font-semibold text-red-900 mb-2">Please fix the following errors:</p>
          <ul class="text-sm text-red-800 space-y-1">
            @foreach ($errors->all() as $error)
              <li>• {{ $error }}</li>
            @endforeach
          </ul>
        </div>
      @endif

      <form class="space-y-5" method="POST" action="{{ route('login') }}" id="loginForm">
        @csrf

        <div>
          <label for="email" class="block text-sm font-semibold text-gray-900 mb-2">
            Email address
          </label>
          <input
            id="email"
            name="email"
            type="email"
            autocomplete="email"
            required
            class="w-full px-4 py-2.5 bg-white border border-gray-300 rounded-lg text-gray-900 text-sm focus:outline-none focus:ring-2 focus:ring-yellow-400 focus:border-transparent placeholder-gray-500 transition-all shadow-sm"
            placeholder="you@example.com"
            value="{{ old('email') }}"
          />
          @error('email')
            <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
          @enderror
        </div>

        <div>
          <div class="flex items-center justify-between mb-2">
            <label for="password" class="block text-sm font-semibold text-gray-900">
              Password
            </label>
            <a href="{{ route('password.request') }}" class="text-sm font-medium text-yellow-600 hover:text-yellow-700 transition-colors">
              Forgot?
            </a>
          </div>
          <input
            id="password"
            name="password"
            type="password"
            autocomplete="current-password"
            required
            class="w-full px-4 py-2.5 bg-white border border-gray-300 rounded-lg text-gray-900 text-sm focus:outline-none focus:ring-2 focus:ring-yellow-400 focus:border-transparent placeholder-gray-500 transition-all shadow-sm"
            placeholder="••••••••"
          />
          @error('password')
            <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
          @enderror
        </div>

        <div class="flex items-center pt-1">
          <input
            id="remember"
            name="remember"
            type="checkbox"
            class="w-4 h-4 rounded border-gray-300 text-yellow-400 focus:ring-yellow-400 cursor-pointer"
          />
          <label for="remember" class="ml-2.5 text-sm text-gray-700 cursor-pointer">
            Remember me 
          </label>
        </div>

        <button
          type="submit"
          class="w-full mt-2 bg-yellow-400 hover:bg-yellow-500 text-gray-900 font-semibold py-2.5 px-4 rounded-lg transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-yellow-400 focus:ring-offset-2 shadow-sm hover:shadow-md"
        >
          Sign in
        </button>
      </form>

      <!-- Divider -->
      <div class="my-6 relative">
        <div class="absolute inset-0 flex items-center">
          <div class="w-full border-t border-gray-200"></div>
        </div>
        <div class="relative flex justify-center text-sm">
          <span class="px-2 bg-white text-gray-600">Demo account</span>
        </div>
      </div>

      <!-- Demo Credentials -->
      <div class="bg-gray-50 rounded-lg p-4 border border-gray-200 mb-6 space-y-1.5">
        <p class="text-xs text-gray-600"><span class="font-semibold">Email:</span> recorder@example.com</p>
        <p class="text-xs text-gray-600"><span class="font-semibold">Password:</span> password</p>
      </div>
    </div>

    <!-- Footer -->
    <p class="mt-8 text-center text-xs text-gray-500">
      © {{ date('Y') }} ConstructTrack. All rights reserved.
    </p>
  </div>

  <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
  <script>
    document.getElementById('loginForm').addEventListener('submit', async (e) => {
      e.preventDefault();
      
      const formData = new FormData(e.target);
      
      try {
        const response = await fetch(e.target.action, {
          method: 'POST',
          headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json',
          },
          body: formData,
        });

        if (response.ok) {
          // Redirect to project selection
          window.location.href = '{{ route("projects.select") }}';
        } else {
          const errors = await response.json();
          console.error('Login failed:', errors);
        }
      } catch (error) {
        console.error('Error during login:', error);
      }
    });
  </script>
</body>
</html>
