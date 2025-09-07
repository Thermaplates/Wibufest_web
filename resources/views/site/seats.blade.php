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

      <!-- Denah gambar (gantikan images/seat-map.jpg dengan gambar denah Anda) -->
      <div class="mb-6">
        <p class="mb-2 font-medium">Denah Kursi</p>
        <img src="{{ asset('images/denah.jpg') }}" alt="Denah Kursi" class="w-full max-w-3xl border rounded mb-2">
        <p class="text-sm text-gray-600">Pilih kursi melalui daftar di bawah. Gunakan Ctrl / Shift untuk memilih beberapa baris pada desktop.</p>
      </div>

      <!-- Pilih kursi via dropdown (multi-select) -->
      <div class="mb-6">
        <label class="block mb-1 font-medium">Pilih Kursi</label>
        <select name="seats[]" id="seatsSelect" multiple size="10" class="w-full p-2 border rounded">
          @foreach($tickets as $t)
            <option value="{{ $t->seat_number }}" {{ $t->status === 'booked' ? 'disabled' : '' }}>
              {{ $t->seat_number }} {{ $t->status === 'booked' ? '(Sudah dipesan)' : '' }}
            </option>
          @endforeach
        </select>
        <p id="selectedInfo" class="mt-2 text-sm text-gray-700">Total: -</p>
      </div>

      <!-- Tombol Clear Selection (mengosongkan select) -->
      <button type="button" onclick="document.querySelectorAll('#seatsSelect option').forEach(o => o.selected = false); document.getElementById('seatsSelect').dispatchEvent(new Event('change'));" class="mb-4 px-4 py-2 bg-gray-300 text-gray-800 rounded hover:bg-gray-400 transition-colors">
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
        </div>

        <div>
          <label class="block mb-1 font-medium">Bukti Pembayaran</label>
          <input id="paymentScreenshot" type="file" name="payment_screenshot" accept="image/*" required class="w-full p-2 border border-gray-300 rounded">
          <p id="fileHelp" class="text-sm text-gray-600 mt-2">Batas upload: 5 MB. Format gambar: .jpg, .jpeg, .png</p>
          <p id="fileError" class="text-sm text-red-600 mt-2 hidden" role="alert" aria-live="polite"></p>
        </div>

        <button id="submitButton" type="submit" class="w-full mt-3 px-4 py-3 bg-red-600 hover:bg-red-700 text-white font-semibold rounded-lg transition-colors">
          Booking Sekarang
        </button>
      </div>
    </form>
  </div>

  <script>
    (function(){
      const select = document.getElementById('seatsSelect');
      const totalPriceInput = document.querySelector('input[name="total_price"]');
      const info = document.getElementById('selectedInfo');
      const pricePerSeat = Number({{ $film->price }});

      function formatCurrency(v){
        return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR' }).format(v);
      }

      function update(){
        const count = [...select.selectedOptions].length;
        const total = count * pricePerSeat;
        info.textContent = count ? `Terpilih ${count} kursi — Total: ${formatCurrency(total)}` : 'Total: -';
        totalPriceInput.value = total;
      }

      select.addEventListener('change', update);
      update();

      // Validasi ukuran file (maks 5 MB)
      const MAX_BYTES = 5 * 1024 * 1024; // 5 MB
      const fileInput = document.getElementById('paymentScreenshot');
      const fileError = document.getElementById('fileError');
      const submitBtn = document.getElementById('submitButton');
      const form = document.getElementById('bookingForm');

      function showFileError(msg){
        fileError.textContent = msg;
        fileError.classList.remove('hidden');
        submitBtn.disabled = true;
      }
      function clearFileError(){
        fileError.textContent = '';
        fileError.classList.add('hidden');
        submitBtn.disabled = false;
      }

      if (fileInput) {
        fileInput.addEventListener('change', () => {
          clearFileError();
          if (!fileInput.files || !fileInput.files[0]) return;
          const f = fileInput.files[0];
          if (f.size > MAX_BYTES) {
            showFileError('File terlalu besar. Maksimum 5 MB. Silakan pilih file lain.');
            fileInput.value = '';
            return;
          }
          // opsional: periksa tipe mime dasar
          const allowed = ['image/jpeg','image/png','image/jpg'];
          if (!allowed.includes(f.type)) {
            showFileError('Format tidak didukung. Gunakan .jpg atau .png.');
            fileInput.value = '';
            return;
          }
          clearFileError();
        });
      }

      if (form) {
        form.addEventListener('submit', (e) => {
          if (fileInput && fileInput.files && fileInput.files[0] && fileInput.files[0].size > MAX_BYTES) {
            e.preventDefault();
            showFileError('File terlalu besar. Maksimum 5 MB.');
          }
        });
      }
    })();
  </script>
</body>
</html>
