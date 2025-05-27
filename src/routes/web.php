<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\RoomController;
use App\Http\Controllers\GuestController;
use App\Http\Controllers\ReservationController;
use App\Models\State;
use App\Models\City;

Route::middleware(['guest'])->group(function () {
    Route::view('/login', 'login.form')->name('login');
    Route::post('/auth', [LoginController::class, 'auth'])->name('auth.auth');
});

Route::middleware(['auth'])->group(function () {

    // Autenticação
    Route::get('/logout', [LoginController::class, 'logout'])->name('auth.logout');

    // Home
    Route::view('/', 'home.index')->name('home.index');
    
    // Rooms
    Route::resource('rooms', RoomController::class);
    // Guests
    Route::resource('guests', GuestController::class);
    // Reservations
    Route::resource('reservations', ReservationController::class);
    Route::post('/reservations/{reservation}/check-in', [ReservationController::class, 'checkIn'])->name('reservations.check-in');
    Route::post('/reservations/{reservation}/check-out', [ReservationController::class, 'checkOut'])->name('reservations.check-out');

});