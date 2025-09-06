<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Film;
use App\Models\Ticket;

class SiteController extends Controller
{
    public function index()
    {
        $films = Film::where('is_active', 1)->get();
        return view('site.index', compact('films'));
    }

    public function seats($id)
    {
        $film = Film::findOrFail($id);
        $tickets = Ticket::where('film_id', $film->id)->orderBy('seat_number')->get();
        return view('site.seats', compact('film','tickets'));
    }
}
