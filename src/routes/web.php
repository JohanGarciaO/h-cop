<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\RoomController;
use App\Http\Controllers\GuestController;
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

    Route::get('/states', function () {
        return State::orderBy('name')->get(['id', 'name', 'acronym']);
    })->name('brasil.states');

    Route::get('/states/{uf}/cities', function ($uf) {
        $state = State::where('id', $uf)->firstOrFail();
        return $state->cities()->orderBy('name')->get(['id', 'name']);
    })->name('brasil.states.cities');

});
