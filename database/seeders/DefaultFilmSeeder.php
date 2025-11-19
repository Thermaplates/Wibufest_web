<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Film;
use App\Models\Ticket;
use Illuminate\Support\Facades\DB;

class DefaultFilmSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::transaction(function () {
            // Buat film default
            $film = Film::create([
                'title' => 'Jujutsu Kaisen Movie Shibuya Incident x Culling Game',
                'price' => 50000,
                'poster' => 'images/poster1.jpg',
                'is_active' => true,
            ]);

            // Array untuk menyimpan semua kursi
            $seats = [];

            // Baris J (14 kursi)
            for ($i = 1; $i <= 14; $i++) {
                $seats[] = "J{$i}";
            }

            // Baris H (14 kursi)
            for ($i = 1; $i <= 14; $i++) {
                $seats[] = "H{$i}";
            }

            // Baris G (14 kursi)
            for ($i = 1; $i <= 14; $i++) {
                $seats[] = "G{$i}";
            }

            // Baris F (14 kursi)
            for ($i = 1; $i <= 14; $i++) {
                $seats[] = "F{$i}";
            }

            // Baris E (14 kursi)
            for ($i = 1; $i <= 14; $i++) {
                $seats[] = "E{$i}";
            }

            // Baris D (14 kursi)
            for ($i = 1; $i <= 14; $i++) {
                $seats[] = "D{$i}";
            }

            // Baris C (14 kursi)
            for ($i = 1; $i <= 14; $i++) {
                $seats[] = "C{$i}";
            }

            // Baris B (14 kursi)
            for ($i = 1; $i <= 14; $i++) {
                $seats[] = "B{$i}";
            }

            // Baris A (17 unit: 14 kursi reguler + 3 couple set)
            // A1
            $seats[] = "A1";

            // Couple Set A3 (A2-A3)
            $seats[] = "Couple Set A3 (A2-A3)";

            // Couple Set A2 (A4-A5)
            $seats[] = "Couple Set A2 (A4-A5)";

            // A6-A18 (kursi reguler)
            for ($i = 6; $i <= 18; $i++) {
                $seats[] = "A{$i}";
            }

            // Couple Set A1 (A19-A20)
            $seats[] = "Couple Set A1 (A19-A20)";

            // Buat tiket untuk setiap kursi
            foreach ($seats as $seat) {
                Ticket::create([
                    'film_id' => $film->id,
                    'seat_number' => $seat,
                    'status' => 'available',
                ]);
            }

            $totalSeats = count($seats);
            $this->command->info("✅ Film '{$film->title}' berhasil dibuat dengan {$totalSeats} kursi");
        });
    }
}
