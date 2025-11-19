<nav class="glass-nav sticky top-0 z-50 backdrop-blur-md bg-white/80 dark:bg-gray-900/80 border-b border-gray-200/50 dark:border-gray-800/50 transition-all">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="flex items-center justify-between h-16">
      <!-- Logo & Brand -->
      <div class="flex items-center space-x-3">
        <a href="{{ url('/') }}" class="flex items-center space-x-2 group">
          <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-red-500 to-pink-600 flex items-center justify-center shadow-lg transform group-hover:scale-105 transition-transform p-1.5">
            <img src="{{ asset('favicon.ico') }}" alt="Wibufest Logo" class="w-full h-full object-contain">
          </div>
          <span class="text-xl font-bold bg-gradient-to-r from-red-600 to-pink-600 dark:from-red-400 dark:to-pink-400 bg-clip-text text-transparent hidden sm:inline">Wibufest</span>
        </a>
      </div>

      <!-- Desktop Menu -->
      <div class="hidden md:flex items-center space-x-4">
        <a href="{{ url('/') }}" class="nav-link">Home</a>
        <a href="{{ url('/admin') }}" class="btn-primary-sm">Admin</a>
      </div>

      <!-- Mobile Menu Button -->
      <button id="mobileMenuBtn" class="md:hidden btn-icon" aria-label="Menu">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
        </svg>
      </button>
    </div>
  </div>

  <!-- Mobile Menu -->
  <div id="mobileMenu" class="hidden md:hidden border-t border-gray-200/50 dark:border-gray-800/50 bg-white/95 dark:bg-gray-900/95 backdrop-blur-md">
    <div class="px-4 py-4 space-y-2">
      <a href="{{ url('/') }}" class="block px-4 py-2.5 rounded-lg text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors">Home</a>
      <a href="{{ url('/admin') }}" class="block px-4 py-2.5 rounded-lg text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors">Admin</a>
    </div>
  </div>
</nav>

<script>
  (function(){
    // Force dark mode
    document.documentElement.classList.add('dark');

    document.addEventListener('DOMContentLoaded', function(){
      // Mobile menu
      const menuBtn = document.getElementById('mobileMenuBtn');
      const menu = document.getElementById('mobileMenu');
      if(menuBtn && menu){
        menuBtn.addEventListener('click', () => {
          menu.classList.toggle('hidden');
        });
      }
    });
  })();
</script>
