<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SiteController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\AdminController;
use Illuminate\Support\Facades\Route as FacadesRoute;

// User
Route::get('/', [SiteController::class,'index'])->name('home');
Route::get('/film/{id}/seats', [SiteController::class,'seats'])->name('film.seats');

Route::post('/book', [BookingController::class, 'store'])->name('book.store');

// Admin login/logout
Route::get('/admin/login', [AdminController::class, 'loginForm'])->name('admin.login');
Route::post('/admin/login', [AdminController::class, 'loginSubmit'])->name('admin.login.submit');
Route::post('/admin/logout', [AdminController::class, 'logout'])->name('admin.logout');

Route::prefix('admin')->middleware('admin.password')->group(function () {
    Route::get('/', [AdminController::class,'index'])->name('admin.dashboard');
    Route::get('/booking/{id}', [AdminController::class, 'showBooking']);
    Route::get('/booking/{id}/payment', [AdminController::class, 'showPayment']);
    Route::post('/booking/{id}/delete', [AdminController::class, 'deleteBooking'])->name('admin.booking.delete');

    Route::get('/films', [AdminController::class,'films'])->name('admin.films');
    Route::post('/films/store', [AdminController::class, 'storeFilm'])->name('admin.films.store');
    Route::post('/films/update', [AdminController::class,'updateFilm'])->name('admin.films.update');
});
