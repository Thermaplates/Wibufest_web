<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Booking;
use App\Models\Film;
use Illuminate\Support\Facades\DB;

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

    // Tampilkan gambar bukti bayar
    public function showPayment($id)
    {
        $booking = Booking::findOrFail($id);

        if (!$booking->payment_screenshot) {
            abort(404);
        }

        return response($booking->payment_screenshot)
               ->header('Content-Type', 'image/png');
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

    // Update film
    public function updateFilm(Request $r)
    {
        $r->validate([
            'id'=>'required|exists:films,id',
            'title'=>'required',
            'price'=>'required|numeric',
        ]);

        $film = Film::findOrFail($r->id);
        $film->title = $r->title;
        $film->price = $r->price;
        $film->is_active = $r->has('is_active');
        $film->save();

        return redirect()->route('admin.films')->with('success','Film berhasil diupdate');
    }
}
