<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Booking;
use App\Models\Ticket;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class BookingController extends Controller
{
    public function store(Request $request)
    {
        // Validasi dengan custom messages
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'film_id' => 'required|exists:films,id',
            'seats' => 'required|array|min:1',
            'seats.*' => 'required|string',
            'payment_screenshot' => 'required|image|mimes:jpg,jpeg,png|max:5120', // 5MB setelah kompresi
        ], [
            'payment_screenshot.required' => 'Bukti pembayaran harus diupload',
            'payment_screenshot.image' => 'File harus berupa gambar',
            'payment_screenshot.mimes' => 'Format gambar harus JPG, JPEG, atau PNG',
            'payment_screenshot.max' => 'Ukuran gambar maksimal 5 MB',
            'seats.required' => 'Pilih minimal 1 kursi',
            'seats.min' => 'Pilih minimal 1 kursi',
        ]);

        DB::beginTransaction();
        try {
            $booking = Booking::create([
                'name'         => $validated['name'],
                'email'        => $validated['email'],
                'film_id'      => $validated['film_id'],
                'payment_status' => 'pending',
                'status'         => 'active',
            ]);

            // Simpan file dengan error handling
            if ($request->hasFile('payment_screenshot') && $request->file('payment_screenshot')->isValid()) {
                $path = $request->file('payment_screenshot')->store('payments', 'public');
                $booking->payment_screenshot = $path;
                $booking->save();
            } else {
                DB::rollBack();
                return back()->withInput()->with('error', 'File bukti pembayaran tidak valid atau corrupt.');
            }

            // Tandai kursi sebagai booked
            foreach($validated['seats'] as $seat){
                $ticket = Ticket::where('seat_number', $seat)
                                ->where('film_id', $validated['film_id'])
                                ->lockForUpdate()
                                ->first();

                if(!$ticket || $ticket->status === 'booked'){
                    DB::rollBack();
                    return back()->withInput()->with('error', 'Kursi '.$seat.' sudah dibooking orang lain.');
                }

                $ticket->status = 'booked';
                $ticket->booking_id = $booking->id;
                $ticket->ticket_number = Str::upper(Str::random(8));
                $ticket->save();
            }

            DB::commit();
            return redirect()->route('home')->with('success','Booking berhasil! Tunggu konfirmasi admin via email.');
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Booking error: ' . $e->getMessage());
            return back()->withInput()->with('error','Gagal menyimpan booking. Silakan coba lagi. Error: '.$e->getMessage());
        }
    }
}