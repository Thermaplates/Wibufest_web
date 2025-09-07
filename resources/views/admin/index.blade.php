<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Admin Dashboard</title>
<link href="https://cdn.jsdelivr.net/npm/tailwindcss@2/dist/tailwind.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
</head>
<body class="p-6 bg-gray-50">
    +  @include('partials.navbar')
  <h1 class="text-2xl font-bold">Admin — Bookings</h1>

  @if(session('success'))
    <div class="p-2 bg-green-200">{{ session('success') }}</div>
  @endif

  <table class="w-full mt-4 bg-white border">
    <thead class="bg-gray-100">
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
      <tr class="border-t">
        <td class="p-2 border">{{ $b->id }}</td>
        <td class="p-2 border">{{ $b->name }}</td>
        <td class="p-2 border">{{ $b->email }}</td>
        <td class="p-2 border">{{ $b->tickets?->pluck('seat_number')->join(', ') ?? '-' }}</td>
        <td class="p-2 border space-x-2">
          <button onclick="showBooking({{ $b->id }})"
                  class="px-2 py-1 bg-blue-600 text-white rounded">
            Lihat Bukti
          </button>
          <form method="POST" action="{{ route('admin.booking.delete', $b->id) }}" style="display:inline">
            @csrf
            <button type="submit" class="px-2 py-1 bg-red-600 text-white rounded">
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
    <div class="bg-white p-4 rounded max-w-lg w-full">
      <h3 id="mname" class="font-bold"></h3>
      <p id="memail" class="text-sm text-gray-600"></p>
      <div id="mimgwrap" class="mt-3"></div>
      <button onclick="closeModal()"
              class="mt-3 px-3 py-2 bg-gray-700 text-white rounded">
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
