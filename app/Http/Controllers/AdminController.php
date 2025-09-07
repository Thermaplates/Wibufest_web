<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Booking;
use App\Models\Film;
use App\Models\Ticket;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class AdminController extends Controller
{
    // Halaman utama admin: daftar booking
    public function index()
    {
        $bookings = Booking::with('tickets')->orderBy('created_at','desc')->get();
        return view('admin.index', compact('bookings'));
    }

    // Detail booking untuk modal
    public function showBooking($id)
    {
        $booking = Booking::with('tickets')->findOrFail($id);

        return response()->json([
            'id' => $booking->id,
            'name' => $booking->name,
            'email' => $booking->email,
            'tickets' => $booking->tickets->pluck('seat_number'),
            'has_payment' => !empty($booking->payment_screenshot),
        ]);
    }

    // Tampilkan gambar bukti bayar (mengambil langsung dari field DB, bukan file path)
    public function showPayment($id)
    {
        $booking = Booking::findOrFail($id);

        if (!$booking->payment_screenshot) {
            abort(404);
        }

        $img = $booking->payment_screenshot;

        // Jika disimpan sebagai data URI (data:image/png;base64,...)
        if (is_string($img) && strpos($img, 'data:image') === 0) {
            [$meta, $base64] = explode(',', $img, 2) + [null, null];
            preg_match('/data:(image\/[a-zA-Z0-9\-\+\.]+);base64/', $meta, $m);
            $mime = $m[1] ?? 'image/jpeg';
            $contents = base64_decode($base64);
            return response($contents, 200)
                ->header('Content-Type', $mime)
                ->header('Content-Length', strlen($contents));
        }

        // Jika disimpan sebagai binary BLOB (string)
        if (is_string($img) || is_resource($img)) {
            $contents = is_resource($img) ? stream_get_contents($img) : $img;
            if (function_exists('finfo_buffer')) {
                $finfo = new \finfo(FILEINFO_MIME_TYPE);
                $mime = $finfo->buffer($contents) ?: 'image/jpeg';
            } else {
                $mime = 'image/jpeg';
            }
            return response($contents, 200)
                ->header('Content-Type', $mime)
                ->header('Content-Length', strlen($contents));
        }

        abort(404);
    }

    // Hapus booking & kosongkan kursi
    public function deleteBooking($id)
    {
        DB::transaction(function() use ($id) {
            $booking = Booking::with('tickets')->findOrFail($id);

            foreach ($booking->tickets as $t) {
                $t->status = 'available';
                $t->booking_id = null;
                $t->ticket_number = null;
                $t->save();
            }

            $booking->delete();
        });

        return redirect()->route('admin.dashboard')->with('success','Booking dihapus.');
    }

    // Page film
    public function films()
    {
        $films = Film::all();
        return view('admin.films', compact('films'));
    }

    // Simpan film baru + cover + kursi
    public function storeFilm(Request $r)
    {
        $r->validate([
            'title'  => 'required|string|max:255',
            'price'  => 'required|numeric',
            'cover'  => 'nullable|image|mimes:jpg,jpeg,png|max:5120', // 5 MB
            'seats'  => 'nullable|string', // daftar nomor kursi, dipisah koma atau newline
            'is_active' => 'nullable|boolean',
        ]);

        DB::beginTransaction();
        try {
            // Upload cover ke public/images agar bisa diakses via asset('images/...')
            $coverPath = null;
            if ($r->hasFile('cover')) {
                $file = $r->file('cover');
                $safeName = time() . '_' . preg_replace('/[^A-Za-z0-9\-\_\.]/', '', $file->getClientOriginalName());
                $file->move(public_path('images'), $safeName);
                $coverPath = 'images/' . $safeName;
            }

            $film = Film::create([
                'title'     => $r->title,
                'price'     => $r->price,
                'poster'    => $coverPath, // sesuaikan nama kolom (poster) di model/migrasi
                'is_active' => $r->has('is_active') ? 1 : 0,
            ]);

            // Buat tiket/kursi jika ada input seats (pisah dengan koma atau newline)
            if ($r->filled('seats')) {
                $items = preg_split('/[\r\n,]+/', $r->seats);
                foreach ($items as $it) {
                    $seat = trim($it);
                    if (!$seat) continue;
                    Ticket::create([
                        'film_id'     => $film->id,
                        'seat_number' => $seat,
                        'status'      => 'available',
                    ]);
                }
            }

            DB::commit();
            return redirect()->route('admin.films')->with('success', 'Film dan kursi berhasil ditambahkan.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal menambahkan film: ' . $e->getMessage());
        }
    }

    // Update film
    public function updateFilm(Request $r)
    {
        $r->validate([
            'price'=>'required|numeric',
        ]);
 
        $film = Film::findOrFail($r->id);
        $film->title = $r->title;
        $film->price = $r->price;
        $film->is_active = $r->has('is_active') ? 1 : 0;
        $film->save();
 
        return redirect()->route('admin.films')->with('success','Film diperbarui.');
    }
}
