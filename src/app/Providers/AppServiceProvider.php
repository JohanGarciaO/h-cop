<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use App\Models\Reservation;
use App\Models\Room;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        DB::statement("SET time_zone = '".env('APP_TIMEZONE', 'America/Sao_Paulo')."'");

        Gate::define('has-active-reservations', fn () => Reservation::active()->exists());

        Gate::define('has-rooms', fn() => Room::exists());
    }
}
