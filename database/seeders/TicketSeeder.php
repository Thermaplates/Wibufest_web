<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Film;
use App\Models\Ticket;

class TicketSeeder extends Seeder
{
    public function run(): void
    {
        // Buat 1 film default
        $film = Film::create([
            'title' => 'Film Perdana',
            'price' => 40000,
            'is_active' => true,
        ]);

        // Generate kursi: A1 - F5 (total 30 kursi)
        $rows = ['A','B','C','D','E','F'];
        foreach ($rows as $row) {
            for ($i = 1; $i <= 5; $i++) {
                Ticket::create([
                    'film_id' => $film->id,
                    'seat_number' => $row.$i,
                    'status' => 'available',
                ]);
            }
        }
    }
}
