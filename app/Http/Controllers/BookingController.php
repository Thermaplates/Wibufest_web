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
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'film_id' => 'required|exists:films,id',
            'seats' => 'required|array|min:1',
            'seats.*' => 'required|string',
            'payment_screenshot' => 'required|image|mimes:jpg,jpeg,png|max:5120', // 5 MB
        ]);

        DB::beginTransaction();
        try {
            $booking = Booking::create([
                'name'           => $request->name,
                'email'          => $request->email,
                'film_id'        => $request->film_id,
                'payment_status' => 'pending',
                'status'         => 'active',
            ]);

            // simpan file payment
            $path = $request->file('payment_screenshot')->store('payments', 'public');
            $booking->payment_screenshot = $path;
            $booking->save();

            // Ambil semua kursi yang sudah ada untuk film ini
            $existingSeats = DB::table('tickets')
                ->where('film_id', $request->film_id)
                ->pluck('kursi')
                ->toArray();

            // Tentukan kursi baru untuk tiket yang dipilih
            $newSeats = [];
            foreach($request->seats as $seatNumber){
                $nextSeat = 1;
                while(in_array($nextSeat, $existingSeats) || in_array($nextSeat, $newSeats)){
                    $nextSeat++;
                }
                $newSeats[] = $nextSeat;
            }

            // Simpan tiket dengan kursi baru
            foreach($request->seats as $i => $seatNumber){
                DB::table('tickets')->insert([
                    'film_id'       => $request->film_id,
                    'booking_id'    => $booking->id,
                    'seat_number'   => $seatNumber,
                    'kursi'         => $newSeats[$i],
                    'ticket_number' => Str::upper(Str::random(8)),
                    'status'        => 'booked',
                    'created_at'    => now(),
                    'updated_at'    => now()
                ]);
            }

            DB::commit();
            return redirect()->route('home')->with('success','Booking berhasil, tunggu konfirmasi admin.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error','Error: '.$e->getMessage());
        }
    }
}