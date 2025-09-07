<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SiteController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\AdminController;

// User
Route::get('/', [SiteController::class,'index'])->name('home');
Route::get('/film/{id}/seats', [SiteController::class,'seats'])->name('film.seats');

Route::post('/book', [BookingController::class, 'store'])->name('book.store');

Route::prefix('admin')->group(function () {
    Route::get('/', [AdminController::class,'index'])->name('admin.dashboard');
    Route::get('/booking/{id}', [AdminController::class, 'showBooking']);
    Route::get('/booking/{id}/payment', [AdminController::class, 'showPayment']);
    Route::post('/booking/{id}/delete', [AdminController::class, 'deleteBooking'])->name('admin.booking.delete');

    Route::get('/films', [AdminController::class,'films'])->name('admin.films');
    Route::post('/films/update', [AdminController::class,'updateFilm'])->name('admin.films.update');
});
