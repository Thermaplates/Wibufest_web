<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\BookingTicketMail;
use App\Models\Booking;

class TestEmailController extends Controller
{
    public function sendTest(Request $request)
    {
        $email = $request->input('email');
        $booking = Booking::first(); // Ambil booking pertama untuk test
        try {
            if (!$email || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
                return response()->json(['error' => 'Email tidak valid'], 400);
            }
            if (!$booking) {
                return response()->json(['error' => 'Data booking tidak ditemukan'], 404);
            }
            Mail::to($email)->send(new BookingTicketMail($booking));
            return response()->json(['success' => 'Email berhasil dikirim ke ' . $email]);
        } catch (\Exception $e) {
            \Log::error('Test email error: ' . $e->getMessage());
            return response()->json([
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ], 500);
        }
    }
}
