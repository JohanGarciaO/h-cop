<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Guest;
use App\Models\Room;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Reservation>
 */
class ReservationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $checkIn = $this->faker->dateTimeBetween('-10 days', 'now');
        $checkOut = (clone $checkIn)->modify('+'.rand(1, 5).' days');
        $daily = $this->faker->randomFloat(2, 100, 500);

        return [
            'guest_id' => Guest::inRandomOrder()->first()?->id ?? Guest::factory(),
            'room_id' => Room::inRandomOrder()->first()?->id ?? Room::factory(),
            'dialy_price' => $daily,
            'total_price' => $daily * $checkIn->diff($checkOut)->days,
            'check_in_at' => $checkIn,
            'check_out_at' => $checkOut,
        ];
    }
}
