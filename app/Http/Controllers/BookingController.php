<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Booking;
use App\Models\Ticket;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class BookingController extends Controller
{
    public function store(Request $r)
    {
        $r->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'film_id' => 'required|exists:films,id',
            'total_price' => 'required|numeric',
            'seats' => 'required|array|min:1',
            'payment_screenshot' => 'required|image|max:4096',
        ]);

        DB::beginTransaction();
        try {
           $booking = Booking::create([
    'name'   => $r->name,
    'email'  => $r->email,
    'film_id'=> $r->film_id,
    'payment_status'=>'pending',
    'status'=>'active',
]);

if($r->hasFile('payment_screenshot')){
    $file = $r->file('payment_screenshot');
    $binaryData = file_get_contents($file->getRealPath());
    $booking->payment_screenshot = $binaryData;
    $booking->save();
}


            // Tandai kursi sebagai booked
            foreach($r->seats as $seat){
                $ticket = Ticket::where('seat_number', $seat)
                                ->where('film_id', $r->film_id)
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
