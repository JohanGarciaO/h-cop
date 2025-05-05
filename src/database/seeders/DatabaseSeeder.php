<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            AdminSeeder::class,
            RoomSeeder::class,
            StateSeeder::class,
            CitySeeder::class,
            AddressSeeder::class,
            GuestSeeder::class,
            ReservationSeeder::class,
        ]);
    }
}
