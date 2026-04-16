<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <meta name="csrf-token" content="{{ csrf_token() }}" />
  <title>Register - ConstructTrack</title>
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

    <!-- Register Form -->
    <div class="bg-white border border-gray-200 rounded-xl p-8 shadow-sm">
      <div class="mb-8">
        <h2 class="text-2xl font-bold text-gray-900">Create your account</h2>
        <p class="mt-1 text-sm text-gray-600">Get started tracking construction productivity</p>
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

      <form class="space-y-5" method="POST" action="{{ route('register') }}">
        @csrf

        <div>
          <label for="name" class="block text-sm font-semibold text-gray-900 mb-2">
            Full Name
          </label>
          <input
            id="name"
            name="name"
            type="text"
            autocomplete="name"
            required
            class="w-full px-4 py-2.5 bg-white border border-gray-300 rounded-lg text-gray-900 text-sm focus:outline-none focus:ring-2 focus:ring-yellow-400 focus:border-transparent placeholder-gray-500 transition-all shadow-sm"
            placeholder="John Doe"
            value="{{ old('name') }}"
          />
          @error('name')
            <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
          @enderror
        </div>

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
          <label for="password" class="block text-sm font-semibold text-gray-900 mb-2">
            Password
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
          <p class="mt-1.5 text-xs text-gray-600">At least 8 characters, mix of uppercase & numbers</p>
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
          class="w-full mt-2 bg-yellow-400 hover:bg-yellow-500 text-gray-900 font-semibold py-2.5 px-4 rounded-lg transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-yellow-400 focus:ring-offset-2 shadow-sm hover:shadow-md"
        >
          Create account
        </button>
      </form>

      <div class="text-center pt-6 border-t border-gray-200">
        <p class="text-sm text-gray-600">
          Already have an account?
          <a href="{{ route('login') }}" class="font-semibold text-yellow-600 hover:text-yellow-700 transition-colors">
            Sign in
          </a>
        </p>
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
