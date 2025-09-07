<nav class="bg-white dark:bg-gray-900 border-b border-gray-200 dark:border-gray-800 transition-colors">
  <div class="max-w-6xl mx-auto px-4">
    <div class="flex items-center justify-between h-16">
      <div class="flex items-center space-x-4">
        <a href="{{ url('/') }}" class="flex items-center space-x-2">
          <img src="{{ asset('favicon.ico') }}" alt="Logo" class="w-8 h-8">
          <span class="text-xl font-bold text-red-600 dark:text-red-400">Wibufest Jogja</span>
        </a>
        <a href="{{ url('/') }}" class="text-gray-600 hover:text-gray-900 dark:text-gray-300 dark:hover:text-white">Home</a>
      </div>

      <div class="flex items-center space-x-4">
        <button id="themeToggle" class="text-sm px-3 py-1 bg-gray-100 dark:bg-gray-800 dark:text-gray-200 rounded hover:bg-gray-200 dark:hover:bg-gray-700 transition-colors">Toggle Tema</button>
        <a href="{{ url('/admin') }}" class="text-sm px-3 py-1 bg-gray-100 dark:bg-gray-800 dark:text-gray-200 rounded hover:bg-gray-200 dark:hover:bg-gray-700 transition-colors">Admin</a>
      </div>
    </div>
  </div>
</nav>
<script>
  (function(){
    const storageKey = 'theme';
    const root = document.documentElement;
    const saved = localStorage.getItem(storageKey);
    if (saved === 'dark' || (!saved && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
      root.classList.add('dark');
    }
    document.addEventListener('DOMContentLoaded', function(){
      const btn = document.getElementById('themeToggle');
      if(!btn) return;
      btn.addEventListener('click', function(){
        const isDark = root.classList.toggle('dark');
        localStorage.setItem(storageKey, isDark ? 'dark' : 'light');
      });
    });
  })();
  </script>
