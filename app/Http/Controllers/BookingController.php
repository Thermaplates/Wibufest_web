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
            'total_price' => 'required|numeric',
            'seats' => 'required|array|min:1',
            'seats.*' => 'required|string',
            'payment_screenshot' => 'required|image|mimes:jpg,jpeg,png|max:5120', // 5 MB
        ]);

        DB::beginTransaction();
        try {
            $booking = Booking::create([
                'name'         => $request->name,
                'email'        => $request->email,
                'film_id'      => $request->film_id,
                'total_price'  => $request->total_price,
                'payment_status' => 'pending',
                'status'         => 'active',
            ]);

            // simpan file
            $path = $request->file('payment_screenshot')->store('payments', 'public');

            $booking->payment_screenshot = $path;
            $booking->save();

            // Tandai kursi sebagai booked
            foreach($request->seats as $seat){
                $ticket = Ticket::where('seat_number', $seat)
                                ->where('film_id', $request->film_id)
                                ->lockForUpdate()
                                ->first();

                if(!$ticket || $ticket->status === 'booked'){
                    DB::rollBack();
                    return back()->with('error', 'Kursi '.$seat.' sudah dibooking orang lain.');
                }

                $ticket->status = 'booked';
                $ticket->booking_id = $booking->id;
                $ticket->ticket_number = Str::upper(Str::random(8));
                $ticket->save();
            }

            DB::commit();
            return redirect()->route('home')->with('success','Booking berhasil, tunggu konfirmasi admin.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error','Error: '.$e->getMessage());
        }
    }
}
