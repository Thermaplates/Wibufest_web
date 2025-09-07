<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Wibufest — Films</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
  <style>
    body { font-family: 'Inter', sans-serif; }
  </style>
</head>
<body class="bg-black min-h-screen p-6 md:p-12 text-gray-100">
  <div class="max-w-6xl mx-auto">
    <h1 class="text-3xl md:text-4xl font-bold mb-8 text-green-400">🎬 Pilih Film</h1>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
      @foreach($films as $f)
      <div class="bg-neutral-900 rounded-xl shadow-lg hover:shadow-green-500/20 border border-neutral-800 transition-all duration-300 overflow-hidden">
        <div class="p-6">
          <h2 class="text-xl font-semibold text-green-300 mb-3">{{ $f['title'] }}</h2>
          <p class="text-gray-400 mb-4">
            Harga:
            <span class="font-medium text-green-400">Rp {{ number_format($f['price'],0,',','.') }}</span>
          </p>
          <a href="{{ route('film.seats', $f['id']) }}"
             class="block w-full text-center px-4 py-3 bg-green-500 hover:bg-green-600 text-black font-semibold rounded-lg transition-colors duration-200">
            Pilih Kursi
          </a>
        </div>
      </div>
      @endforeach
    </div>

    <footer class="mt-12 text-center border-t border-neutral-800 pt-6">
      <p class="text-gray-500">
        Admin?
        <a href="/admin" class="text-green-400 hover:text-green-300 font-medium underline-offset-2 hover:underline transition-all">
          masuk admin
        </a>
      </p>
    </footer>
  </div>
</body>
</html>
