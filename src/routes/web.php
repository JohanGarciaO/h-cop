<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\RoomController;
use App\Http\Controllers\GuestController;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\PdfController;
use App\Http\Controllers\CommitteesController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CleaningController;
use App\Models\State;
use App\Models\City;
use App\Models\User;

Route::middleware(['guest'])->group(function () {
    Route::view('/login', 'login.form')->name('login');
    Route::post('/auth', [LoginController::class, 'auth'])->name('auth.auth');
});

Route::middleware(['auth','force-password'])->group(function () {

    // Autenticação
    Route::get('/logout', [LoginController::class, 'logout'])->name('auth.logout');

    // Password redefined
    Route::view('/login/password-update', 'login.update-password')->name('auth.form.update-password');
    Route::post('/login/password-update', [LoginController::class, 'updatePassword'])->name('auth.update.password');

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
    Route::get('/reservations/{reservation}/receipt/download', [ReservationController::class, 'downloadReceipt'])->name('reservations.receipt.download');

    // Committees
    Route::resource('committees', CommitteesController::class);

    // Users
    Route::middleware(['can:viewAny,App\Models\User'])->group(function () {
        Route::resource('users', UserController::class);
        Route::patch('users/reset/{id}', [UserController::class, 'reset'])->name('users.reset');
    });

    // Cleanings
    Route::resource('cleanings', CleaningController::class);
});