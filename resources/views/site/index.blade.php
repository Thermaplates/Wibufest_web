<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Wibufest — Films</title>
  <link href="https://cdn.jsdelivr.net/npm/tailwindcss@3/dist/tailwind.min.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
  <style>
    body { font-family: 'Inter', sans-serif; }
  </style>
</head>
<body class="bg-gradient-to-br from-gray-50 to-gray-100 min-h-screen p-6 md:p-12">
  <div class="max-w-6xl mx-auto">
    <h1 class="text-3xl md:text-4xl font-bold mb-8 text-gray-800">Pilih Film</h1>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
      @foreach($films as $f)
      <div class="bg-white rounded-lg shadow-sm hover:shadow-md transition-shadow duration-300 overflow-hidden">
        <div class="p-6">
          <h2 class="text-xl font-semibold text-gray-800 mb-3">{{ $f['title'] }}</h2>
          <p class="text-gray-600 mb-4">Harga: <span class="font-medium text-gray-800">Rp {{ number_format($f['price'],0,',','.') }}</span></p>
          <a href="{{ route('film.seats', $f['id']) }}"
             class="block w-full text-center px-4 py-3 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-colors duration-200">
            Pilih Kursi
          </a>
        </div>
      </div>
      @endforeach
    </div>

    <footer class="mt-12 text-center">
      <p class="text-gray-500">
        Admin?
        <a href="/admin" class="text-blue-600 hover:text-blue-700 font-medium underline-offset-2 hover:underline transition-all">
          masuk admin
        </a>
      </p>
    </footer>
  </div>
</body>
</html>
