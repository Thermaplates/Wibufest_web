<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title>Wibufest — Films</title>
  <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-50 p-6">
  <h1 class="text-2xl font-bold mb-4">Pilih Film</h1>
  <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
    @foreach($films as $f)
    <div class="p-4 bg-white rounded shadow">
      <h2 class="font-semibold">{{ $f['title'] }}</h2>
      <p>Harga: Rp {{ number_format($f['price'],0,',','.') }}</p>
      <a href="{{ route('film.seats', $f['id']) }}" class="inline-block mt-2 px-3 py-2 bg-blue-600 text-white rounded">Pilih Kursi</a>
    </div>
    @endforeach
  </div>
  <p class="mt-8 text-sm text-gray-500">Admin? <a href="/admin" class="text-blue-600">masuk admin</a></p>
</body>
</html>
