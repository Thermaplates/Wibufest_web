<!doctype html>
<html lang="id">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Pilih Kursi — {{ $film->title }}</title>
<link rel="icon" href="{{ asset('favicon.ico') }}">
<script>
  // Force dark mode permanently
  document.documentElement.classList.add('dark');
</script>
<script src="https://cdn.tailwindcss.com"></script>
<script>tailwind.config = { darkMode: 'class' }</script>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="{{ asset('css/app.css') }}">
<link rel="stylesheet" href="{{ asset('css/custom-inputs.css') }}">
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

  <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Header -->
    <div class="mb-8">
      <a href="{{ url('/') }}" class="inline-flex items-center text-sm text-gray-600 dark:text-gray-400 hover:text-red-600 dark:hover:text-red-400 mb-4 transition-colors">
        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
        </svg>
        Kembali ke Daftar Film
      </a>
      <h1 class="text-3xl sm:text-4xl font-bold bg-gradient-to-r from-red-600 to-pink-600 dark:from-red-400 dark:to-pink-400 bg-clip-text text-transparent">
        {{ $film->title }}
      </h1>
      <p class="text-gray-600 dark:text-gray-400 mt-2">Pilih kursi dan lengkapi data untuk booking tiket Anda</p>
    </div>

    <form action="{{ route('book.store') }}" method="POST" enctype="multipart/form-data" id="bookingForm" class="space-y-6">
      @csrf
      <input type="hidden" name="film_id" value="{{ $film->id }}">

      <!-- Info Alert -->
      <div class="glass-card bg-blue-50/50 dark:bg-blue-900/10 border-blue-200 dark:border-blue-800">
        <div class="flex items-start space-x-3">
          <svg class="w-6 h-6 text-blue-600 dark:text-blue-400 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
          </svg>
          <div class="flex-1 text-sm">
            <p class="font-semibold text-blue-900 dark:text-blue-300 mb-2">Informasi Penting</p>
            <ul class="space-y-1 text-blue-800 dark:text-blue-400">
              <li>✓ E-ticket dikirim ke email maksimal 3x24 jam</li>
              <li>✓ Tiket bersifat non-refundable</li>
              <li>✓ Daftar ulang di lokasi event</li>
              <li>✓ Datang 30 menit sebelum pemutaran</li>
            </ul>
          </div>
        </div>
      </div>

      <!-- Seat Map -->
      <div class="glass-card">
        <h2 class="text-lg font-semibold mb-4">Denah Kursi</h2>
        <div class="overflow-x-auto">
          <img src="{{ asset('images/denah.jpg') }}" alt="Denah Kursi" class="w-full rounded-lg border border-gray-200 dark:border-gray-700 mb-3">
        </div>
        <p class="text-sm text-gray-600 dark:text-gray-400">
          💡 Tips: Gunakan Ctrl/Cmd atau Shift untuk memilih beberapa kursi sekaligus
        </p>
      </div>

      <!-- Seat Selection -->
      <div class="glass-card">
        <label class="block text-lg font-semibold mb-3">Pilih Kursi</label>
        <select name="seats[]" id="seatsSelect" multiple size="10"
          class="w-full p-3 border border-gray-300 dark:border-gray-700 rounded-lg bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-red-500/60 focus:border-transparent transition-all">
          @foreach($tickets as $t)
            <option value="{{ $t->seat_number }}" {{ $t->status === 'booked' ? 'disabled' : '' }}
              class="py-2 {{ $t->status === 'booked' ? 'text-gray-400 line-through' : 'hover:bg-red-50 dark:hover:bg-red-900/20' }}">
              {{ $t->seat_number }} {{ $t->status === 'booked' ? '(Sudah dipesan)' : '' }}
            </option>
          @endforeach
        </select>

        <div class="flex items-center justify-between mt-4">
          <p id="selectedInfo" class="text-sm font-medium text-gray-700 dark:text-gray-300">Pilih kursi untuk melihat total</p>
          <button type="button" onclick="document.querySelectorAll('#seatsSelect option').forEach(o => o.selected = false); document.getElementById('seatsSelect').dispatchEvent(new Event('change'));"
            class="btn-secondary text-sm">
            Reset Pilihan
          </button>
        </div>
      </div>

      <!-- Personal Info -->
      <div class="glass-card space-y-4">
        <h2 class="text-lg font-semibold mb-4">Data Pemesan</h2>

        <div>
          <label class="block mb-2 text-sm font-medium text-gray-300">Nama Lengkap</label>
          <input type="text" name="name" placeholder="Contoh: John Doe"
            style="width: 100%; padding: 0.875rem 1.125rem; border: 2px solid #4b5563; border-radius: 0.875rem; background-color: #111827 !important; color: #ffffff !important; font-size: 0.9375rem; font-weight: 500; box-shadow: 0 2px 4px rgba(0,0,0,0.4);"
            required>
        </div>

        <div>
          <label class="block mb-2 text-sm font-medium text-gray-300">Email</label>
          <input type="email" name="email" placeholder="email@example.com"
            style="width: 100%; padding: 0.875rem 1.125rem; border: 2px solid #4b5563; border-radius: 0.875rem; background-color: #111827 !important; color: #ffffff !important; font-size: 0.9375rem; font-weight: 500; box-shadow: 0 2px 4px rgba(0,0,0,0.4);"
            required>
        </div>
      </div>

      <!-- Payment Section -->
      <div class="glass-card space-y-4">
        <h2 class="text-lg font-semibold mb-4">Pembayaran</h2>

        <div class="flex justify-center">
          <div class="text-center">
            <p class="text-sm font-medium mb-3">Scan QR Code untuk Bayar</p>
            <div class="inline-block p-3 bg-white rounded-xl shadow-lg">
              <img src="{{ asset('images/qrcode.jpg') }}" alt="QR Payment" class="w-64 h-auto rounded-lg">
            </div>
          </div>
        </div>

        <div>
          <label class="block mb-2 text-sm font-medium text-gray-300">Upload Bukti Pembayaran</label>
          <input id="paymentScreenshot" type="file" name="payment_screenshot" accept="image/*" required
            style="width: 100%; padding: 0.625rem 0.875rem; border: 2px solid #4b5563; border-radius: 0.875rem; background-color: #111827 !important; color: #ffffff !important; font-size: 0.9375rem; box-shadow: 0 2px 4px rgba(0,0,0,0.4); cursor: pointer;">
          <p id="fileHelp" class="text-xs text-gray-400 mt-2">
            Format: JPG, JPEG, PNG • Max: 5 MB
          </p>
          <p id="fileError" class="text-sm text-red-400 mt-2 hidden" role="alert" aria-live="polite"></p>
        </div>
      </div>

      <!-- Submit Button -->
      <button id="submitButton" type="submit" class="btn-primary w-full py-4 text-lg font-semibold shadow-lg hover:shadow-xl">
        🎫 Booking Sekarang
      </button>
    </form>
  </div>


  <script>
    (function(){
      const select = document.getElementById('seatsSelect');

      const info = document.getElementById('selectedInfo');
      const pricePerSeat = Number({{ $film->price }});

      function formatCurrency(v){
        return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR' }).format(v);
      }

      function update(){
        const count = [...select.selectedOptions].length;
        const total = count * pricePerSeat;
        info.textContent = count ? `Terpilih ${count} kursi — Total: ${formatCurrency(total)}` : 'Total: -';

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

      function closeFlash(){
        const el = document.getElementById('flashModal');
        if(!el) return;
        el.style.transition = 'opacity .25s ease';
        el.style.opacity = '0';
        setTimeout(()=> el.remove(), 250);
      }

      document.addEventListener('DOMContentLoaded', ()=>{
        const el = document.getElementById('flashModal');
        if(!el) return;
        el.classList.remove('hidden');
        el.classList.add('flex');
        setTimeout(closeFlash, 5000);
      });
    })();
  </script>
</body>
</html>
