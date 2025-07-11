<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            RoleSeeder::class,
            UserSeeder::class,
            RoomSeeder::class,
            StateSeeder::class,
            CitySeeder::class,
            AddressSeeder::class,
            CommitteeSeeder::class,
            GuestSeeder::class,
            ReservationSeeder::class,
            HousekeepersSeeder::class,
        ]);
    }
}
