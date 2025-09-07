<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Admin - Films</title>
<link href="https://cdn.jsdelivr.net/npm/tailwindcss@2/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="p-6 bg-gray-50">
  @include('partials.navbar')
  <h1 class="text-2xl font-bold">Admin — Film</h1>

  @if(session('success'))
    <div class="p-2 bg-green-200">{{ session('success') }}</div>
  @endif

  <table class="w-full mt-4 bg-white border">
    <thead class="bg-gray-100">
      <tr>
        <th class="p-2 border">ID</th>
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
