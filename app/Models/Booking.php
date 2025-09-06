<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'film_id',
        'payment_screenshot',
        'payment_status',
        'status',
    ];

    // Relasi ke Ticket
    public function tickets()
    {
        return $this->hasMany(Ticket::class, 'booking_id');
    }
}
