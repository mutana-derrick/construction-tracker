<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <meta name="csrf-token" content="{{ csrf_token() }}" />
  <title>Reset Password - ConstructTrack</title>
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

    <!-- Reset Password Form -->
    <div class="bg-white border border-gray-200 rounded-xl p-8 shadow-sm">
      <div class="mb-8">
        <h2 class="text-2xl font-bold text-gray-900">Reset your password</h2>
        <p class="mt-1 text-sm text-gray-600">Enter your new password below</p>
      </div>

      <form class="space-y-5" method="POST" action="{{ route('password.store') }}">
        @csrf

        <input type="hidden" name="token" value="{{ $request->route('token') }}">

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
            value="{{ old('email', $request->email) }}"
          />
          @error('email')
            <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
          @enderror
        </div>

        <div>
          <label for="password" class="block text-sm font-semibold text-gray-900 mb-2">
            New Password
          </label>
          <input
            id="password"
            name="password"
            type="password"
            autocomplete="new-password"
            required
            class="w-full px-4 py-2.5 bg-white border border-gray-300 rounded-lg text-gray-900 text-sm focus:outline-none focus:ring-2 focus:ring-yellow-400 focus:border-transparent placeholder-gray-500 transition-all shadow-sm"
            placeholder="••••••••"
          />
          @error('password')
            <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
          @enderror
        </div>

        <div>
          <label for="password_confirmation" class="block text-sm font-semibold text-gray-900 mb-2">
            Confirm Password
          </label>
          <input
            id="password_confirmation"
            name="password_confirmation"
            type="password"
            autocomplete="new-password"
            required
            class="w-full px-4 py-2.5 bg-white border border-gray-300 rounded-lg text-gray-900 text-sm focus:outline-none focus:ring-2 focus:ring-yellow-400 focus:border-transparent placeholder-gray-500 transition-all shadow-sm"
            placeholder="••••••••"
          />
          @error('password_confirmation')
            <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
          @enderror
        </div>

        <button
          type="submit"
          class="w-full bg-yellow-400 hover:bg-yellow-500 text-gray-900 font-semibold py-2.5 px-4 rounded-lg transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-yellow-400 focus:ring-offset-2 shadow-sm hover:shadow-md"
        >
          Reset password
        </button>
      </form>

      <div class="text-center pt-6 border-t border-gray-200">
        <a href="{{ route('login') }}" class="font-semibold text-yellow-600 hover:text-yellow-700 transition-colors text-sm">
          Back to login
        </a>
      </div>
    </div>

    <!-- Footer -->
    <p class="mt-8 text-center text-xs text-gray-500">
      © {{ date('Y') }} ConstructTrack. All rights reserved.
    </p>
  </div>

  <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</body>
</html>
