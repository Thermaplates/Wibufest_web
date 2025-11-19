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
            $expected = 'gedebongpisanghitam';
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

        $p = $booking->payment_screenshot;
        $hasPayment = false;
        if (!is_null($p)) {
            if (is_string($p)) {
                $hasPayment = strlen($p) > 0;
            } elseif (is_resource($p)) {
                // resource stream dianggap ada isinya
                $hasPayment = true;
            } else {
                $hasPayment = true; // tipe lain (mis. binary string) dianggap ada
            }
        }

        return response()->json([
            'id' => $booking->id,
            'name' => $booking->name,
            'email' => $booking->email,
            'tickets' => $booking->tickets->pluck('seat_number'),
            'has_payment' => $hasPayment,
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

        // 1) Data URI (data:image/png;base64,...)
        if (is_string($img) && strpos($img, 'data:image') === 0) {
            [$meta, $base64] = explode(',', $img, 2) + [null, null];
            preg_match('/data:(image\/[a-zA-Z0-9\-\+\.]+);base64/', $meta, $m);
            $mime = $m[1] ?? 'image/jpeg';
            $contents = base64_decode($base64);
            return response($contents, 200)
                ->header('Content-Type', $mime)
                ->header('Content-Length', strlen($contents));
        }

        // 2) Path di disk 'public' (payments/xxx.jpg)
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

        // 3) BLOB/LONGBLOB binary dari database
        //    Bisa datang sebagai resource stream atau string binary.
        if (is_resource($img)) {
            $contents = stream_get_contents($img) ?: '';
            if ($contents !== '') {
                $mime = function_exists('finfo_open')
                    ? (finfo_file(finfo_open(FILEINFO_MIME_TYPE), 'php://memory') ?: 'image/jpeg')
                    : 'image/jpeg';
                // Coba deteksi via getimagesizefromstring jika tersedia
                if (function_exists('getimagesizefromstring')) {
                    $info = @getimagesizefromstring($contents);
                    if ($info && isset($info['mime'])) {
                        $mime = $info['mime'];
                    }
                }
                return response($contents, 200)
                    ->header('Content-Type', $mime)
                    ->header('Content-Length', strlen($contents));
            }
        }

        if (is_string($img)) {
            // Jika bukan data-uri dan bukan path valid, asumsi ini string binary dari kolom BLOB
            $contents = $img;
            if ($contents !== '') {
                $mime = 'image/jpeg';
                if (function_exists('getimagesizefromstring')) {
                    $info = @getimagesizefromstring($contents);
                    if ($info && isset($info['mime'])) {
                        $mime = $info['mime'];
                    }
                }
                return response($contents, 200)
                    ->header('Content-Type', $mime)
                    ->header('Content-Length', strlen($contents));
            }
        }

        abort(404);
    }

    // Hapus booking & kosongkan kursi + auto decrement ID
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

            // Auto decrement booking IDs
            $bookingsToUpdate = Booking::where('id', '>', $id)->orderBy('id')->get();
            foreach ($bookingsToUpdate as $b) {
                $oldId = $b->id;
                $newId = $oldId - 1;
                
                // Update tickets terlebih dahulu
                DB::table('tickets')
                    ->where('booking_id', $oldId)
                    ->update(['booking_id' => $newId]);
                
                // Update booking ID
                DB::statement('UPDATE bookings SET id = ? WHERE id = ?', [$newId, $oldId]);
            }

            // Reset auto increment
            $maxId = Booking::max('id') ?? 0;
            DB::statement('ALTER TABLE bookings AUTO_INCREMENT = ' . ($maxId + 1));
        });

        return redirect()->route('admin.dashboard')->with('success','Booking dihapus dan ID diperbarui.');
    }

    // Hapus semua booking & reset kursi + reset auto-increment
    public function clearAllBookings()
    {
        DB::transaction(function () {
            // Kosongkan relasi kursi
            DB::table('tickets')->whereNotNull('booking_id')->update([
                'status' => 'available',
                'booking_id' => null,
                'ticket_number' => null,
            ]);

            // Hapus semua bookings dan reset auto increment agar mulai dari 1 lagi
            DB::statement('TRUNCATE TABLE bookings');
        });

        return redirect()->route('admin.dashboard')->with('success','Semua booking dihapus dan ID direset.');
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
            'price'  => 'required|numeric|min:0',
            'cover'  => 'nullable|image|mimes:jpg,jpeg,png|max:5120', // 5 MB
            'seats'  => 'nullable|string', // daftar nomor kursi, dipisah koma atau newline
            'is_active' => 'nullable',
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
                'title'     => $r->input('title'),
                'price'     => $r->input('price'),
                'poster'    => $coverPath, // sesuaikan nama kolom (poster) di model/migrasi
                'is_active' => $r->input('is_active') == '1' ? 1 : 0,
            ]);

            // Buat tiket/kursi jika ada input seats (pisah dengan koma atau newline)
            if ($r->filled('seats')) {
                $items = preg_split('/[\r\n,]+/', $r->input('seats'));
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
            return back()->withInput()->with('error', 'Gagal menambahkan film: ' . $e->getMessage());
        }
    }

    // Update film
    public function updateFilm(Request $r)
    {
        $r->validate([
            'id' => 'required|exists:films,id',
            'title' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
        ]);

        $film = Film::findOrFail($r->input('id'));
        $film->title = $r->input('title');
        $film->price = $r->input('price');
        $film->is_active = $r->input('is_active') == '1' ? 1 : 0;
        $film->save();

        return redirect()->route('admin.films')->with('success','Film diperbarui.');
    }

    // Hapus film
    public function deleteFilm($id)
    {
        DB::transaction(function() use ($id) {
            $film = Film::findOrFail($id);
            
            // Hapus poster jika ada
            if ($film->poster && file_exists(public_path($film->poster))) {
                unlink(public_path($film->poster));
            }
            
            // Hapus semua tiket terkait
            Ticket::where('film_id', $id)->delete();
            
            // Hapus film
            $film->delete();
        });

        return redirect()->route('admin.films')->with('success','Film berhasil dihapus.');
    }
}
