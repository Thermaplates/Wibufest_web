<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Wibufest — Film Festival Jogja</title>
  <link rel="icon" href="{{ asset('favicon.ico') }}">
  <script>
    // Force dark mode permanently
    document.documentElement.classList.add('dark');
  </script>
  <script src="https://cdn.tailwindcss.com"></script>
  <script>tailwind.config = { darkMode: 'class' }</script>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="{{ asset('css/app.css') }}">
  <style>
    body { font-family: 'Inter', sans-serif; }
    ::selection { background: #ef4444; color: white; }
    .dark ::selection { background: #f87171; color: #111827; }
  </style>
</head>
<body class="bg-gradient-to-br from-gray-50 to-gray-100 dark:from-gray-950 dark:to-gray-900 min-h-screen text-gray-800 dark:text-gray-100 transition-colors">
  @include('partials.navbar')

  {{-- Flash modal --}}
  @if(session('success') || session('error'))
    <div id="flashModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black/60 backdrop-blur-sm">
      <div class="glass-card max-w-md w-full p-6 mx-4 animate-slide-up">
        <div class="flex items-start space-x-3">
          <div class="flex-shrink-0">
            @if(session('success'))
              <div class="w-10 h-10 rounded-full bg-green-100 dark:bg-green-900/30 flex items-center justify-center">
                <svg class="w-6 h-6 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
              </div>
            @else
              <div class="w-10 h-10 rounded-full bg-red-100 dark:bg-red-900/30 flex items-center justify-center">
                <svg class="w-6 h-6 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
              </div>
            @endif
          </div>
          <div class="flex-1">
            <h3 class="text-lg font-semibold mb-1 text-gray-900 dark:text-gray-100">
              {{ session('success') ? 'Berhasil!' : 'Terjadi Kesalahan' }}
            </h3>
            <p class="text-sm text-gray-600 dark:text-gray-400">
              {{ session('success') ?? session('error') }}
            </p>
          </div>
        </div>
        <div class="mt-4 flex justify-end">
          <button onclick="closeFlash()" class="btn-primary">Tutup</button>
        </div>
      </div>
    </div>
  @endif

  <!-- Hero Section -->
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 sm:py-16">
    <div class="text-center mb-12 space-y-4">
      <h1 class="text-4xl sm:text-5xl lg:text-6xl font-bold bg-gradient-to-r from-red-600 via-pink-600 to-purple-600 dark:from-red-400 dark:via-pink-400 dark:to-purple-400 bg-clip-text text-transparent animate-gradient">
        Wibufest Film Festival
      </h1>
      <p class="text-lg sm:text-xl text-gray-600 dark:text-gray-400 max-w-2xl mx-auto">
        Nikmati pengalaman sinema terbaik di Jogja. Pilih filmmu dan pesan kursi sekarang!
      </p>
    </div>

    <!-- Films Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6 lg:gap-8">
      @foreach($films as $f)
      <a href="{{ route('film.seats', $f['id']) }}" class="group">
        <div class="film-card">
          <!-- Poster -->
          <div class="poster-container">
            <img
              src="{{ asset( $f['poster'] ?? 'images/poster.jpg' ) }}"
              alt="{{ $f['title'] }}"
              class="poster-image"
              loading="lazy"
            >
            <div class="poster-overlay">
              <span class="overlay-btn">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                </svg>
                Pilih Kursi
              </span>
            </div>
          </div>

          <!-- Info -->
          <div class="p-5 space-y-3">
            <h2 class="text-lg font-bold text-gray-900 dark:text-gray-50 line-clamp-2 group-hover:text-red-600 dark:group-hover:text-red-400 transition-colors">
              {{ $f['title'] }}
            </h2>
            <div class="flex items-center justify-between">
              <span class="text-sm text-gray-500 dark:text-gray-400">Harga</span>
              <span class="text-xl font-bold text-red-600 dark:text-red-400">
                Rp {{ number_format($f['price'],0,',','.') }}
              </span>
            </div>
          </div>
        </div>
      </a>
      @endforeach
    </div>

    @if(count($films) === 0)
    <div class="text-center py-16">
      <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-gray-200 dark:bg-gray-800 mb-4">
        <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 4v16M17 4v16M3 8h4m10 0h4M3 12h18M3 16h4m10 0h4M4 20h16a1 1 0 001-1V5a1 1 0 00-1-1H4a1 1 0 00-1 1v14a1 1 0 001 1z" />
        </svg>
      </div>
      <h3 class="text-xl font-semibold text-gray-700 dark:text-gray-300 mb-2">Belum Ada Film</h3>
      <p class="text-gray-500 dark:text-gray-400">Film akan segera ditambahkan. Silakan cek kembali nanti!</p>
    </div>
    @endif
  </div>

  <script>
    function closeFlash(){
      const el = document.getElementById('flashModal');
      if(!el) return;
      el.style.transition = 'opacity .3s ease';
      el.style.opacity = '0';
      setTimeout(()=> el.remove(), 300);
    }

    document.addEventListener('DOMContentLoaded', ()=>{
      const el = document.getElementById('flashModal');
      if(!el) return;
      el.classList.remove('hidden');
      el.classList.add('flex');
      setTimeout(closeFlash, 5000);
    });
  </script>
</body>
</html>
