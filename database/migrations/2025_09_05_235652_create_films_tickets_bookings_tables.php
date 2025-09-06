<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        // Tabel film
        Schema::create('films', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->decimal('price', 10, 2);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // Tabel booking user
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email');
            $table->foreignId('film_id')->constrained()->onDelete('cascade');
            $table->decimal('total_price', 10, 2);
            $table->string('payment_screenshot')->nullable();
            $table->enum('payment_status', ['pending','paid','rejected'])->default('pending');
            $table->enum('status', ['active','completed','cancelled'])->default('active');
            $table->timestamps();
        });

        // Tabel kursi/tiket
        Schema::create('tickets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('film_id')->constrained()->onDelete('cascade');
            $table->string('seat_number');
            $table->enum('status',['available','booked'])->default('available');
            $table->foreignId('booking_id')->nullable()->constrained()->onDelete('set null');
            $table->string('ticket_number')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tickets');
        Schema::dropIfExists('bookings');
        Schema::dropIfExists('films');
    }
};
