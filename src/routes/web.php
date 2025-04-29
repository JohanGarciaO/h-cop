<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\RoomController;


Route::middleware(['guest'])->group(function () {
    Route::view('/login', 'login.form')->name('login');
    Route::post('/auth', [LoginController::class, 'auth'])->name('auth.login');
    Route::get('/teste', function () {
        return 'Eu sou o teste.';
    })->name('site.teste');
});

Route::middleware(['auth'])->group(function () {

    // Autenticação
    Route::get('/logout', [LoginController::class, 'logout'])->name('auth.logout');

    // Home
    Route::view('/', 'site.home')->name('site.home');
    
    // Rooms
    Route::resource('rooms', RoomController::class);

});
