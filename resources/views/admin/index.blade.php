<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Admin Dashboard</title>
<link rel="icon" href="{{ asset('favicon.ico') }}">
<script>
  (function(){
    const saved = localStorage.getItem('theme');
    if (saved === 'dark' || (!saved && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
      document.documentElement.classList.add('dark');
    } else {
      document.documentElement.classList.remove('dark');
    }
  })();
  </script>
<script src="https://cdn.tailwindcss.com"></script>
<script>
  tailwind.config = { darkMode: 'class' }
</script>
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
</head>
<body class="p-6 bg-gray-50 dark:bg-gray-950 dark:text-gray-100 transition-colors">
    @include('partials.navbar')
  <div class="flex items-center justify-between mb-4">
    <h1 class="text-2xl font-bold">Admin — Bookings</h1>
    <div class="flex items-center space-x-2">
      <a href="{{ route('admin.films') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 focus:outline-none">
        Tambah / Kelola Film
      </a>
      <form method="POST" action="{{ route('admin.bookings.clear') }}" onsubmit="return confirm('Hapus semua booking dan reset ID?');">
        @csrf
        <button type="submit" class="inline-flex items-center px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded focus:outline-none">Hapus Semua</button>
      </form>
    </div>
  </div>

  @if(session('success'))
    <div class="p-2 bg-green-200">{{ session('success') }}</div>
  @endif

  <table class="w-full mt-4 bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800">
    <thead class="bg-gray-100 dark:bg-gray-800">
      <tr>
        <th class="p-2 border">ID</th>
        <th class="p-2 border">Nama</th>
        <th class="p-2 border">Email</th>
        <th class="p-2 border">Seats</th>
        <th class="p-2 border">Aksi</th>
      </tr>
    </thead>
    <tbody>
      @foreach($bookings as $b)
      <tr class="border-t border-gray-200 dark:border-gray-800">
        <td class="p-2 border border-gray-200 dark:border-gray-800">{{ $b->id }}</td>
        <td class="p-2 border border-gray-200 dark:border-gray-800">{{ $b->name }}</td>
        <td class="p-2 border border-gray-200 dark:border-gray-800">{{ $b->email }}</td>
        <td class="p-2 border border-gray-200 dark:border-gray-800">{{ $b->tickets?->pluck('seat_number')->join(', ') ?? '-' }}</td>
        <td class="p-2 border border-gray-200 dark:border-gray-800 space-x-2">
          <button onclick="showBooking({{ $b->id }})"
                  class="px-2 py-1 bg-blue-600 hover:bg-blue-700 text-white rounded transition-colors">
            Lihat Bukti
          </button>
          <form method="POST" action="{{ route('admin.booking.delete', $b->id) }}" style="display:inline">
            @csrf
            <button type="submit" class="px-2 py-1 bg-red-600 hover:bg-red-700 text-white rounded transition-colors">
              Hapus/Set Selesai
            </button>
          </form>
        </td>
      </tr>
      @endforeach
    </tbody>
  </table>

  <!-- Modal -->
  <div id="modal" class="fixed inset-0 hidden items-center justify-center bg-black bg-opacity-50">
    <div class="bg-white dark:bg-gray-900 p-4 rounded max-w-lg w-full border border-gray-200 dark:border-gray-800">
      <h3 id="mname" class="font-bold"></h3>
      <p id="memail" class="text-sm text-gray-600 dark:text-gray-300"></p>
      <div id="mimgwrap" class="mt-3"></div>
      <button onclick="closeModal()"
              class="mt-3 px-3 py-2 bg-gray-700 hover:bg-gray-800 text-white rounded transition-colors">
        Tutup
      </button>
    </div>
  </div>

<script>
function showBooking(id){
  axios.get('/admin/booking/'+id).then(r=>{
    const d = r.data;
    document.getElementById('mname').innerText = d.name + ' — #' + d.id;
    document.getElementById('memail').innerText = d.email + ' | Seats: ' + d.tickets.join(', ');
    const wrap = document.getElementById('mimgwrap');

    if(d.has_payment){
      wrap.innerHTML = '<img src="/admin/booking/'+id+'/payment" class="max-w-full border rounded" />';
    } else {
      wrap.innerHTML = '<p class="text-red-500">Tidak ada bukti pembayaran</p>';
    }

    const modal = document.getElementById('modal');
    modal.classList.remove('hidden');
    modal.classList.add('flex');
  });
}

function closeModal(){
  const modal=document.getElementById('modal');
  modal.classList.add('hidden');
  modal.classList.remove('flex');
}
</script>

</body>
</html>
