<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Pilih Kursi</title>
<link href="https://cdn.jsdelivr.net/npm/tailwindcss@2/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="p-6 bg-gray-50">
  <h1 class="text-xl font-bold mb-3">Pilih Kursi — {{ $film->title }}</h1>

  <form action="{{ route('book.store') }}" method="POST" enctype="multipart/form-data" id="bookingForm">
    @csrf
    <input type="hidden" name="film_id" value="{{ $film->id }}">
    <input type="hidden" name="total_price" value="{{ $film->price }}">

    <div class="grid grid-cols-6 gap-3 max-w-md">
      @foreach($tickets as $t)
        @php $disabled = $t->status === 'booked' ? 'disabled' : ''; @endphp
        <label class="block">
          <input type="checkbox" name="seats[]" value="{{ $t->seat_number }}" class="peer sr-only" {{ $disabled }}>
          <div class="p-2 text-center border rounded cursor-pointer peer-checked:bg-green-400 {{ $disabled ? 'opacity-40 cursor-not-allowed' : '' }}">
            {{ $t->seat_number }}
          </div>
        </label>
      @endforeach
    </div>

    <div class="mt-4 max-w-md">
      <label class="block mb-1">Nama</label>
      <input type="text" name="name" class="w-full p-2 border rounded" required>
      <label class="block mt-2 mb-1">Email</label>
      <input type="email" name="email" class="w-full p-2 border rounded" required>

      <div class="mt-4">
        <p>Scan QR untuk bayar:</p>
        <img src="{{ asset('images/qr-example.png') }}" alt="QR" class="w-44 h-44 border">
      </div>

      <div class="mt-2">
        <label>Bukti Pembayaran</label>
        <input type="file" name="payment_screenshot" accept="image/*" required>
      </div>

      <button type="submit" class="mt-3 px-4 py-2 bg-blue-600 text-white rounded">Booking Sekarang</button>
    </div>
  </form>
</body>
</html>
