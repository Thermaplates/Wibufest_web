<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Booking;
use App\Models\Film;
use App\Models\Ticket;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Redirect;

class AdminController extends Controller
{
    // Login form
    public function loginForm()
    {
        return view('admin.login');
    }

    // Handle login submit
    public function loginSubmit(Request $request)
    {
        $request->validate([
            'password' => 'required|string',
        ]);

        $expected = (string) config('admin.password');
        if (!is_string($expected) || strlen((string)$expected) === 0) {
            $expected = 'admin123';
        }

        if (hash_equals((string)$expected, (string)$request->input('password'))) {
            $request->session()->put('admin_authenticated', true);
            return redirect()->route('admin.dashboard');
        }

        return redirect()->route('admin.login')->with('error', 'Password salah.');
    }

    // Logout
    public function logout(Request $request)
    {
        $request->session()->forget('admin_authenticated');
        return redirect()->route('admin.login');
    }
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

    // Tampilkan gambar bukti bayar dari path storage:public atau data-uri/blobs
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

        // Jika berupa path di disk 'public' (payments/xxx.jpg)
        if (is_string($img)) {
            if (Storage::disk('public')->exists($img)) {
                $contents = Storage::disk('public')->get($img);
                $root = config('filesystems.disks.public.root');
                $fullPath = rtrim($root, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . ltrim($img, DIRECTORY_SEPARATOR);
                $mime = File::mimeType($fullPath) ?: 'image/jpeg';
                return response($contents, 200)
                    ->header('Content-Type', $mime)
                    ->header('Content-Length', strlen($contents));
            }
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
