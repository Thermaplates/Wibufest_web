<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin Login — Wibufest</title>
  <link rel="icon" href="{{ asset('favicon.ico') }}">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <script>
    // Force dark mode permanently
    document.documentElement.classList.add('dark');
  </script>
  <script src="https://cdn.tailwindcss.com"></script>
  <script>tailwind.config = { darkMode: 'class' }</script>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="{{ asset('css/app.css') }}">
  <link rel="stylesheet" href="{{ asset('css/custom-inputs.css') }}">
  <style>
    body { font-family: 'Inter', sans-serif; min-height: 100vh; }
    ::selection { background: #ef4444; color: white; }
    .dark ::selection { background: #f87171; color: #111827; }
  </style>
  <script>
    document.addEventListener('DOMContentLoaded', function(){
      const input = document.getElementById('password');
      if(input){ input.focus(); }
    });
  </script>
  <script>if(window.history.replaceState){window.history.replaceState(null,null,window.location.href);}</script>
</head>
<body class="bg-gradient-to-br from-gray-50 to-gray-100 dark:from-gray-950 dark:to-gray-900 flex items-center justify-center p-4 transition-colors">
  <div class="w-full max-w-md">
    <!-- Logo & Brand -->
    <div class="text-center mb-8">
      <div class="inline-flex items-center justify-center w-16 h-16 rounded-2xl bg-gradient-to-br from-red-500 to-pink-600 shadow-lg mb-4">
        <img src="{{ asset('favicon.ico') }}" alt="Wibufest Logo" class="w-10 h-10 object-contain">
      </div>
      <h1 class="text-3xl font-bold bg-gradient-to-r from-red-600 to-pink-600 dark:from-red-400 dark:to-pink-400 bg-clip-text text-transparent mb-2">
        Admin Portal
      </h1>
      <p class="text-gray-600 dark:text-gray-400">Wibufest Film Festival</p>
    </div>

    <!-- Login Card -->
    <div class="glass-card animate-slide-up">
      <div class="mb-6">
        <h2 class="text-xl font-semibold text-gray-900 dark:text-gray-100">Masuk ke Dashboard</h2>
        <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Masukkan password untuk melanjutkan</p>
      </div>

      @if(session('error'))
        <div class="mb-6 glass-card bg-red-50/50 dark:bg-red-900/10 border-red-200 dark:border-red-800 animate-slide-up">
          <div class="flex items-center gap-3">
            <svg class="w-5 h-5 text-red-600 dark:text-red-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <p class="text-sm text-red-700 dark:text-red-300">{{ session('error') }}</p>
          </div>
        </div>
      @endif

      <form method="POST" action="{{ route('admin.login.submit') }}" class="space-y-5">
        @csrf

        <div>
          <label class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-300" for="password">
            Password Admin
          </label>
          <div class="relative">
            <input
              id="password"
              name="password"
              type="password"
              style="width: 100%; padding: 0.875rem 2.5rem 0.875rem 1.125rem; border: 2px solid #4b5563; border-radius: 0.875rem; background-color: #111827 !important; color: #ffffff !important; font-size: 0.9375rem; font-weight: 500; box-shadow: 0 2px 4px rgba(0,0,0,0.4);"
              placeholder="Masukkan password"
              required
              autocomplete="current-password">
            <button
              type="button"
              onclick="togglePassword()"
              class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 transition-colors">
              <svg id="eye-icon" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
              </svg>
            </button>
          </div>
        </div>

        <button
          type="submit"
          class="btn-primary w-full py-3 text-base font-semibold shadow-lg hover:shadow-xl">
          Masuk ke Dashboard
        </button>
      </form>

      <div class="mt-6 pt-6 border-t border-gray-200/50 dark:border-gray-700/50">
        <a href="{{ url('/') }}" class="text-sm text-gray-600 dark:text-gray-400 hover:text-red-600 dark:hover:text-red-400 transition-colors inline-flex items-center gap-2">
          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
          </svg>
          Kembali ke Website
        </a>
      </div>
    </div>

    <!-- Footer -->
    <div class="text-center mt-8 text-sm text-gray-500 dark:text-gray-400">
      <p>&copy; 2025 Wibufest. All rights reserved.</p>
    </div>
  </div>

  <script>
    function togglePassword() {
      const input = document.getElementById('password');
      const icon = document.getElementById('eye-icon');

      if (input.type === 'password') {
        input.type = 'text';
        icon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />';
      } else {
        input.type = 'password';
        icon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />';
      }
    }
  </script>
</body>
</html>


