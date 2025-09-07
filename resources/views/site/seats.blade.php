<!doctype html>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Pilih Kursi — {{ $film->title }}</title>
<script src="https://cdn.tailwindcss.com"></script>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
<style>
  body { font-family: 'Inter', sans-serif; }
</style>
</head>
<body class="p-6 bg-white text-gray-800">
  <div class="max-w-3xl mx-auto">
    <h1 class="text-2xl md:text-3xl font-bold mb-6 text-red-600">🎬 Pilih Kursi — {{ $film->title }}</h1>

    <form action="{{ route('book.store') }}" method="POST" enctype="multipart/form-data" id="bookingForm">
      @csrf
      <input type="hidden" name="film_id" value="{{ $film->id }}">
      <input type="hidden" name="total_price" value="{{ $film->price }}">

      <!-- Kursi -->
      <div class="grid grid-cols-6 gap-3 mb-6 max-w-md">
        @foreach($tickets as $t)
          @php $disabled = $t->status === 'booked' ? 'disabled' : ''; @endphp
          <label class="block relative">
            <input type="checkbox" name="seats[]" value="{{ $t->seat_number }}" class="peer sr-only" {{ $disabled }}>
            <div class="p-3 text-center border rounded cursor-pointer peer-checked:bg-red-600 peer-checked:text-white transition-colors duration-200 {{ $disabled ? 'bg-gray-200 cursor-not-allowed opacity-50' : 'hover:border-red-600' }}" title="{{ $disabled ? 'Kursi sudah dipesan' : 'Kursi tersedia' }}">
              {{ $t->seat_number }}
            </div>
          </label>
        @endforeach
      </div>

      <!-- Tombol Clear Selection -->
      <button type="button" onclick="document.querySelectorAll('input[name=\'seats[]\']').forEach(c => c.checked = false);" class="mb-4 px-4 py-2 bg-gray-300 text-gray-800 rounded hover:bg-gray-400 transition-colors">
        Clear Selection
      </button>

      <!-- Form Data -->
      <div class="space-y-4">
        <div>
          <label class="block mb-1 font-medium">Nama</label>
          <input type="text" name="name" class="w-full p-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-red-500" required>
        </div>

        <div>
          <label class="block mb-1 font-medium">Email</label>
          <input type="email" name="email" class="w-full p-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-red-500" required>
        </div>

        <div>
          <p class="mb-2 font-medium">Scan QR untuk bayar:</p>
        <img src="{{ asset('images/qrcode.jpg') }}" alt="QR" class="w-[300px] h-[400px] border rounded">


        <div>
          <label class="block mb-1 font-medium">Bukti Pembayaran</label>
          <input type="file" name="payment_screenshot" accept="image/*" required class="w-full p-2 border border-gray-300 rounded">
        </div>

        <button type="submit" class="w-full mt-3 px-4 py-3 bg-red-600 hover:bg-red-700 text-white font-semibold rounded-lg transition-colors">
          Booking Sekarang
        </button>
      </div>
    </form>
  </div>
</body>
</html>
