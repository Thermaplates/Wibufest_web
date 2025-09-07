<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Wibufest — Films</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <script>
    // enable dark mode class strategy
    tailwind.config = { darkMode: 'class' }
  </script>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
  <style>
    body { font-family: 'Inter', sans-serif; }
  </style>
</head>
<body class="bg-white dark:bg-gray-950 min-h-screen p-6 md:p-12 text-gray-800 dark:text-gray-100 transition-colors">
  @include('partials.navbar')

  {{-- Flash modal untuk menampilkan pesan success/error setelah submit --}}
  @if(session('success') || session('error'))
    <div id="flashModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
      <div class="bg-white rounded-lg shadow-lg max-w-lg w-full p-6 mx-4">
        <h3 class="text-lg font-semibold mb-2 text-gray-900">
          {{ session('success') ? 'Berhasil' : 'Terjadi Kesalahan' }}
        </h3>
        <p class="text-sm text-gray-700">
          {{ session('success') ?? session('error') }}
        </p>
        <div class="mt-4 flex justify-end">
          <button onclick="closeFlash()" class="px-3 py-2 bg-red-600 text-white rounded hover:bg-red-700">Tutup</button>
        </div>
      </div>
    </div>
  @endif

  <div class="max-w-6xl mx-auto">
    <h1 class="text-3xl md:text-4xl font-bold mb-8 text-red-600 dark:text-red-400">🎬 Pilih Film</h1>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
      @foreach($films as $f)
      <div class="bg-white dark:bg-gray-900 rounded-xl shadow-md hover:shadow-lg border border-gray-200 dark:border-gray-800 transition-all duration-300 overflow-hidden">
        <a href="{{ route('film.seats', $f['id']) }}" class="block">
          <!-- Poster: gunakan field poster jika ada, fallback ke placeholder -->
          <img
            src="{{ asset( $f['poster'] ?? 'images/poster.jpg' ) }}"
            alt="{{ $f['title'] }} poster"
            class="w-full h-auto object-contain"
            loading="lazy"
          >
          <div class="p-6">
            <h2 class="text-xl font-semibold text-gray-900 dark:text-gray-50 mb-3">{{ $f['title'] }}</h2>
            <p class="text-gray-600 dark:text-gray-300 mb-4">
              Harga:
              <span class="font-medium text-red-600 dark:text-red-400">Rp {{ number_format($f['price'],0,',','.') }}</span>
            </p>
            <span class="block w-full text-center px-4 py-3 bg-red-600 hover:bg-red-700 text-white font-semibold rounded-lg transition-colors duration-200">
              Pilih Kursi
            </span>
          </div>
        </a>
      </div>
      @endforeach
    </div>


  <script>
    function closeFlash(){
      const el = document.getElementById('flashModal');
      if(!el) return;
      // animasi fade out sederhana
      el.style.transition = 'opacity .25s ease';
      el.style.opacity = '0';
      setTimeout(()=> el.remove(), 250);
    }

    document.addEventListener('DOMContentLoaded', ()=>{
      // jika ada flash modal tampilkan dan auto-close
      const el = document.getElementById('flashModal');
      if(!el) return;
      // pastikan modal terlihat (jika ada class hidden)
      el.classList.remove('hidden');
      el.classList.add('flex');
      // auto close setelah 5 detik
      setTimeout(closeFlash, 5000);
      // juga beri fokus tombol tutup jika ada
      const btn = el.querySelector('button');
      if(btn) btn.focus();
    });
  </script>
</body>
</html>
