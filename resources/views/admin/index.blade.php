<!doctype html>
<html lang="id">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Admin Dashboard — Wibufest</title>
<link rel="icon" href="{{ asset('favicon.ico') }}">
<script>
  // Force dark mode permanently
  document.documentElement.classList.add('dark');
</script>
<script src="https://cdn.tailwindcss.com"></script>
<script>tailwind.config = { darkMode: 'class' }</script>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
<link rel="stylesheet" href="{{ asset('css/app.css') }}">
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js" defer></script>
<style>
  body { font-family: 'Inter', sans-serif; }
  ::selection { background: #ef4444; color: white; }
  .dark ::selection { background: #f87171; color: #111827; }
</style>
</head>
<body class="bg-gradient-to-br from-gray-50 to-gray-100 dark:from-gray-950 dark:to-gray-900 min-h-screen text-gray-800 dark:text-gray-100 transition-colors">
  @include('partials.navbar')

  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Header -->
    <div class="mb-8">
      <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
          <h1 class="text-3xl sm:text-4xl font-bold bg-gradient-to-r from-red-600 to-pink-600 dark:from-red-400 dark:to-pink-400 bg-clip-text text-transparent">
            Dashboard Admin
          </h1>
          <p class="text-gray-600 dark:text-gray-400 mt-2">Kelola booking dan film festival</p>
        </div>
        <div class="flex flex-wrap items-center gap-3">
          <a href="{{ route('admin.films') }}" class="btn-primary-sm inline-flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
            </svg>
            Kelola Film
          </a>
          <form method="POST" action="{{ route('admin.bookings.clear') }}" onsubmit="return confirm('Hapus semua booking dan reset ID?');" class="inline">
            @csrf
            <button type="submit" class="btn-secondary inline-flex items-center gap-2 bg-red-100 dark:bg-red-900/20 text-red-700 dark:text-red-400 border-red-200 dark:border-red-800 hover:bg-red-200 dark:hover:bg-red-900/30">
              <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
              </svg>
              Hapus Semua
            </button>
          </form>
          <form method="POST" action="{{ route('admin.logout') }}" class="inline">
            @csrf
            <button type="submit" class="btn-secondary inline-flex items-center gap-2">
              <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
              </svg>
              Logout
            </button>
          </form>
        </div>
      </div>
    </div>

    <!-- Alert -->
    @if(session('success'))
      <div class="glass-card bg-green-50/50 dark:bg-green-900/10 border-green-200 dark:border-green-800 mb-6 animate-slide-up">
        <div class="flex items-center gap-3">
          <svg class="w-5 h-5 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
          </svg>
          <p class="text-green-800 dark:text-green-300">{{ session('success') }}</p>
        </div>
      </div>
    @endif

    <!-- Bookings Table -->
    <div class="glass-card overflow-hidden">
      <div class="px-6 py-4 border-b border-gray-200/50 dark:border-gray-700/50">
        <div class="flex flex-col lg:flex-row lg:justify-between lg:items-start gap-4">
          <div>
            <h2 class="text-xl font-semibold">Daftar Booking</h2>
            <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Total: {{ count($bookings) }} booking</p>
          </div>
          <div class="text-right">
            <p class="text-sm text-gray-600 dark:text-gray-400">Total Pendapatan</p>
            <p class="text-2xl font-bold bg-gradient-to-r from-red-600 to-pink-600 dark:from-red-400 dark:to-pink-400 bg-clip-text text-transparent">
              Rp {{ number_format($totalRevenue ?? 0, 0, ',', '.') }}
            </p>
          </div>
        </div>

        <!-- Search Form -->
        <div class="mt-4">
          <form method="GET" action="{{ route('admin.dashboard') }}" class="flex gap-2">
            <div class="relative flex-1 max-w-md">
              <input 
                type="text" 
                name="search" 
                value="{{ $search ?? '' }}"
                placeholder="Cari berdasarkan ID, nama, atau email..."
                class="w-full pl-10 pr-4 py-2.5 text-sm border-2 border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 placeholder-gray-500 dark:placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-colors">
              <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
              </svg>
            </div>
            <button type="submit" class="px-4 py-2.5 bg-red-600 hover:bg-red-700 text-white text-sm font-medium rounded-lg transition-colors inline-flex items-center gap-2">
              <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
              </svg>
              Cari
            </button>
            @if($search ?? false)
              <a href="{{ route('admin.dashboard') }}" class="px-4 py-2.5 bg-gray-200 hover:bg-gray-300 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-300 text-sm font-medium rounded-lg transition-colors inline-flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
                Reset
              </a>
            @endif
          </form>
        </div>
      </div>

      <div class="overflow-x-auto">
        <table class="w-full">
          <thead class="bg-gray-50/50 dark:bg-gray-800/50">
            <tr>
              <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider">ID</th>
              <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider">Nama</th>
              <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider">Email</th>
              <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider">Kursi</th>
              <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider">Aksi</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-gray-200/50 dark:divide-gray-700/50">
            @forelse($bookings as $b)
            <tr class="hover:bg-gray-50/50 dark:hover:bg-gray-800/30 transition-colors">
              <td class="px-4 py-3 text-sm font-medium text-gray-900 dark:text-gray-100">#{{ $b->id }}</td>
              <td class="px-4 py-3 text-sm text-gray-700 dark:text-gray-300">{{ $b->name }}</td>
              <td class="px-4 py-3 text-sm text-gray-600 dark:text-gray-400">{{ $b->email }}</td>
              <td class="px-4 py-3 text-sm">
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 dark:bg-blue-900/30 text-blue-800 dark:text-blue-300">
                  {{ $b->tickets?->pluck('seat_number')->join(', ') ?? '-' }}
                </span>
              </td>
              <td class="px-4 py-3 text-sm">
                <div class="flex flex-wrap gap-2">
                  <button onclick="showBooking({{ $b->id }})" class="inline-flex items-center px-3 py-1.5 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-colors text-xs font-medium">
                    <svg class="w-3.5 h-3.5 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                    </svg>
                    Lihat
                  </button>
                  <form method="POST" action="{{ route('admin.booking.send-email', $b->id) }}" style="display:inline" onsubmit="return confirm('Kirim email tiket ke {{ $b->email }}?')">
                    @csrf
                    <button type="submit" class="inline-flex items-center px-3 py-1.5 bg-green-600 hover:bg-green-700 text-white rounded-lg transition-colors text-xs font-medium">
                      <svg class="w-3.5 h-3.5 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                      </svg>
                      Kirim Tiket
                    </button>
                  </form>
                  <form method="POST" action="{{ route('admin.booking.delete', $b->id) }}" style="display:inline" onsubmit="return confirm('Hapus booking ini?')">
                    @csrf
                    <button type="submit" class="inline-flex items-center px-3 py-1.5 bg-red-600 hover:bg-red-700 text-white rounded-lg transition-colors text-xs font-medium">
                      <svg class="w-3.5 h-3.5 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                      </svg>
                      Hapus
                    </button>
                  </form>
                </div>
              </td>
            </tr>
            @empty
            <tr>
              <td colspan="5" class="px-4 py-8 text-center text-gray-500 dark:text-gray-400">
                <svg class="w-12 h-12 mx-auto mb-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                </svg>
                Belum ada booking
              </td>
            </tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>
  </div>

  <!-- Modal -->
  <div id="modal" class="fixed inset-0 hidden items-center justify-center bg-black/60 backdrop-blur-sm z-50" onclick="if(event.target===this) closeModal()">
    <div class="glass-card max-w-lg w-full mx-4 max-h-[90vh] overflow-y-auto animate-slide-up">
      <div class="flex items-center justify-between mb-4 pb-4 border-b border-gray-200/50 dark:border-gray-700/50 sticky top-0 bg-white/90 dark:bg-gray-900/90 backdrop-blur-sm z-10">
        <div>
          <h3 id="mname" class="text-lg font-bold text-gray-900 dark:text-gray-100"></h3>
          <p id="memail" class="text-xs text-gray-600 dark:text-gray-400 mt-1"></p>
        </div>
        <button onclick="closeModal()" class="btn-icon">
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
          </svg>
        </button>
      </div>

      <div id="mimgwrap" class="mb-4"></div>

      <div class="flex justify-end sticky bottom-0 bg-white/90 dark:bg-gray-900/90 backdrop-blur-sm pt-4 border-t border-gray-200/50 dark:border-gray-700/50">
        <button onclick="closeModal()" class="btn-primary">Tutup</button>
      </div>
    </div>
  </div>

<script>
function showBooking(id){
  axios.get('/admin/booking/'+id).then(r=>{
    const d = r.data;
    document.getElementById('mname').innerText = d.name + ' (ID: #' + d.id + ')';
    document.getElementById('memail').innerText = d.email + ' • Kursi: ' + d.tickets.join(', ');
    const wrap = document.getElementById('mimgwrap');

    if(d.has_payment){
      wrap.innerHTML = '<div class="rounded-lg overflow-hidden border border-gray-200 dark:border-gray-700" style="max-width:100%;"><img src="/admin/booking/'+id+'/payment" class="w-full h-auto max-h-[60vh] object-contain mx-auto" style="max-width:100%; max-height:60vh;" loading="lazy" /></div>';
    } else {
      wrap.innerHTML = '<div class="flex items-center gap-3 p-4 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg"><svg class="w-5 h-5 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg><p class="text-red-700 dark:text-red-300">Tidak ada bukti pembayaran</p></div>';
    }

    const modal = document.getElementById('modal');
    modal.classList.remove('hidden');
    modal.classList.add('flex');
    document.body.style.overflow = 'hidden';
  });
}

function closeModal(){
  const modal = document.getElementById('modal');
  modal.classList.add('hidden');
  modal.classList.remove('flex');
  document.body.style.overflow = '';
}
</script>

</body>
</html>
