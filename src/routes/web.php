<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;


Route::middleware(['guest'])->group(function () {
    Route::view('/login', 'login.form')->name('login');
    Route::post('/auth', [LoginController::class, 'auth'])->name('auth.login');
    Route::get('/teste', function () {
        return 'Eu sou o teste.';
    })->name('site.teste');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/logout', [LoginController::class, 'logout'])->name('auth.logout');
    Route::view('/', 'site.home')->name('site.home');
});
