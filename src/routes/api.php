<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\UtilsController;
use App\Models\State;
use App\Models\City;

Route::middleware(['auth'])->group(function () {

    Route::get('/states', function () {
        return State::orderBy('name')->get(['id', 'name', 'acronym']);
    })->name('brasil.states');

    Route::get('/states/{uf}/cities', function ($uf) {
        $state = State::where('id', $uf)->firstOrFail();
        return $state->cities()->orderBy('name')->get(['id', 'name']);
    })->name('brasil.states.cities');

});

Route::get('/available-between/{entity}', [UtilsController::class, 'showAvailableBetween'])->name('utils.show-available-between');