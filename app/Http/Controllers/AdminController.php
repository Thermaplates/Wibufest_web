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
use Illuminate\Support\Facades\Mail;
use App\Mail\BookingTicketMail;

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
    public function index(Request $request)
    {
        $search = $request->input('search');
        
        // Query dengan search
        $query = Booking::with(['tickets', 'film']);
        
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('id', 'LIKE', "%{$search}%")
                  ->orWhere('name', 'LIKE', "%{$search}%")
                  ->orWhere('email', 'LIKE', "%{$search}%");
            });
        }
        
        $bookings = $query->orderBy('created_at','desc')->get();
        
        // Hitung total pendapatan
        $totalRevenue = 0;
        foreach($bookings as $booking) {
            if ($booking->film) {
                $filmPrice = $booking->film->price;
                foreach($booking->tickets as $ticket) {
                    // Couple seat harganya 2x
                    if(str_contains($ticket->seat_number, 'Couple Set')) {
                        $totalRevenue += $filmPrice * 2;
                    } else {
                        $totalRevenue += $filmPrice;
                    }
                }
            }
        }
        
        return view('admin.index', compact('bookings', 'totalRevenue', 'search'));
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

    // Hapus booking & kosongkan kursi
    public function deleteBooking($id)
    {
        try {
            DB::beginTransaction();
            
            $booking = Booking::with('tickets')->findOrFail($id);

            // Kosongkan kursi
            foreach ($booking->tickets as $t) {
                $t->status = 'available';
                $t->booking_id = null;
                $t->ticket_number = null;
                $t->save();
            }

            // Hapus booking
            $booking->delete();

            DB::commit();
            return redirect()->route('admin.dashboard')->with('success','Booking berhasil dihapus.');
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Delete booking error: ' . $e->getMessage());
            return redirect()->route('admin.dashboard')->with('error','Gagal menghapus booking: ' . $e->getMessage());
        }
    }

    // Kirim email tiket ke customer
    public function sendTicketEmail($id)
    {
        try {
            $booking = Booking::with(['tickets', 'film'])->findOrFail($id);
            
            // Kirim email
            Mail::to($booking->email)->send(new BookingTicketMail($booking));
            
            return redirect()->route('admin.dashboard')->with('success', 'Email tiket berhasil dikirim ke ' . $booking->email);
        } catch (\Exception $e) {
            \Log::error('Send email error: ' . $e->getMessage());
            return redirect()->route('admin.dashboard')->with('error', 'Gagal mengirim email: ' . $e->getMessage());
        }
    }

    // Hapus semua booking & reset kursi + reset auto-increment
    public function clearAllBookings()
    {
        try {
            DB::transaction(function () {
                // Kosongkan relasi kursi terlebih dahulu
                DB::table('tickets')->whereNotNull('booking_id')->update([
                    'status' => 'available',
                    'booking_id' => null,
                    'ticket_number' => null,
                ]);

                // Hapus semua bookings
                DB::table('bookings')->delete();
                
                // Reset auto increment ke 1
                DB::statement('ALTER TABLE bookings AUTO_INCREMENT = 1');
            });

            return redirect()->route('admin.dashboard')->with('success','Semua booking dihapus dan ID direset ke 1.');
        } catch (\Exception $e) {
            \Log::error('Error clearing bookings: ' . $e->getMessage());
            return redirect()->route('admin.dashboard')->with('error', 'Gagal menghapus booking: ' . $e->getMessage());
        }
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
        try {
            DB::transaction(function() use ($id) {
                $film = Film::findOrFail($id);
                
                // Hapus poster jika ada DAN tidak digunakan oleh film lain
                if ($film->poster && file_exists(public_path($film->poster))) {
                    // Cek apakah ada film lain yang menggunakan poster yang sama
                    $otherFilmsWithSamePoster = Film::where('id', '!=', $id)
                        ->where('poster', $film->poster)
                        ->exists();
                    
                    // Hanya hapus file jika tidak ada film lain yang pakai
                    if (!$otherFilmsWithSamePoster) {
                        unlink(public_path($film->poster));
                    }
                }
                
                // Hapus semua tiket terkait
                Ticket::where('film_id', $id)->delete();
                
                // Hapus film
                $film->delete();
                
                // Reset auto-increment jika tidak ada film lagi
                $filmCount = Film::count();
                if ($filmCount === 0) {
                    DB::statement('ALTER TABLE films AUTO_INCREMENT = 1');
                }
            });

            return redirect()->route('admin.films')->with('success', 'Film berhasil dihapus.');
        } catch (\Exception $e) {
            \Log::error('Error deleting film: ' . $e->getMessage());
            return redirect()->route('admin.films')->with('error', 'Gagal menghapus film: ' . $e->getMessage());
        }
    }
}
