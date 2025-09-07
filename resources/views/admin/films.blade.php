<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Admin - Films</title>
<link href="https://cdn.jsdelivr.net/npm/tailwindcss@2/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="p-6 bg-gray-50">
  @include('partials.navbar')
  <h1 class="text-2xl font-bold mb-4">Admin — Film</h1>

  @if(session('success'))
    <div class="p-2 mb-4 bg-green-200">{{ session('success') }}</div>
  @endif
  @if(session('error'))
    <div class="p-2 mb-4 bg-red-200">{{ session('error') }}</div>
  @endif

  <!-- Form tambah film -->
  <div class="bg-white border rounded p-4 mb-6">
    <h2 class="font-semibold mb-2">Tambah Film Baru</h2>
    <form method="POST" action="{{ route('admin.films.store') }}" enctype="multipart/form-data" class="space-y-3">
      @csrf
      <div>
        <label class="block text-sm font-medium">Judul</label>
        <input type="text" name="title" class="w-full border p-2 rounded" required>
      </div>
      <div>
        <label class="block text-sm font-medium">Harga tiket (Rp)</label>
        <input type="number" name="price" class="w-full border p-2 rounded" step="1" required>
      </div>
      <div>
        <label class="block text-sm font-medium">Cover / Poster (jpg, png) - max 5MB</label>
        <input type="file" name="cover" accept="image/*" class="w-full border p-2 rounded">
      </div>
      <div>
        <label class="block text-sm font-medium">Daftar kursi (pisah dengan koma atau newline)</label>
        <textarea name="seats" rows="3" class="w-full border p-2 rounded" placeholder="Contoh: A1, A2, A3&#10;B1, B2"></textarea>
        <p class="text-xs text-gray-600 mt-1">Masukkan nomor kursi yang ingin dibuat untuk film ini.</p>
      </div>
      <div class="flex items-center space-x-3">
        <label class="flex items-center space-x-2">
          <input type="checkbox" name="is_active" class="form-checkbox">
          <span class="text-sm">Aktifkan film</span>
        </label>
        <button type="submit" class="ml-auto px-4 py-2 bg-green-600 text-white rounded">Tambah Film</button>
      </div>
    </form>
  </div>

  <!-- Daftar film (edit existing) -->
  <table class="w-full bg-white border">
    <thead class="bg-gray-100">
      <tr>
        <th class="p-2 border">ID</th>
        <th class="p-2 border">Cover</th>
        <th class="p-2 border">Judul</th>
        <th class="p-2 border">Harga</th>
        <th class="p-2 border">Aktif</th>
        <th class="p-2 border">Aksi</th>
      </tr>
    </thead>
    <tbody>
      @foreach($films as $f)
      <tr class="border-t">
        <form method="POST" action="{{ route('admin.films.update') }}">
          @csrf
          <td class="p-2 border">{{ $f->id }}<input type="hidden" name="id" value="{{ $f->id }}"></td>
          <td class="p-2 border">
            @if($f->poster)
              <img src="{{ asset($f->poster) }}" alt="cover" class="w-20 h-auto object-contain">
            @else
              <span class="text-xs text-gray-500">-</span>
            @endif
          </td>
          <td class="p-2 border"><input type="text" name="title" value="{{ $f->title }}" class="border p-1 w-full"></td>
          <td class="p-2 border"><input type="number" name="price" value="{{ $f->price }}" class="border p-1 w-full"></td>
          <td class="p-2 border"><input type="checkbox" name="is_active" {{ $f->is_active ? 'checked' : '' }}></td>
          <td class="p-2 border">
            <button type="submit" class="px-2 py-1 bg-blue-600 text-white rounded">Update</button>
          </td>
        </form>
      </tr>
      @endforeach
    </tbody>
  </table>
</body>
</html>
