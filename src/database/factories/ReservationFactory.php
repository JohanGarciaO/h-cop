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
        $daily = $this->faker->randomFloat(2, 100, 500);

        // Decide aleatoriamente se a reserva está ativa (sem check-out) ou finalizada
        $isActive = $this->faker->boolean(70);

        $checkOut = $isActive ? null : (clone $checkIn)->modify('+'.rand(1, 5).' days');
        $days = $checkOut ? $checkIn->diff($checkOut)->days : 1;

        return [
            'guest_id' => Guest::inRandomOrder()->first()?->id ?? Guest::factory(),
            'room_id' => Room::inRandomOrder()->first()?->id ?? Room::factory(),
            'dialy_price' => $daily,
            'total_price' => $daily * $days,
            'check_in_at' => $checkIn,
            'check_out_at' => $checkOut,
        ];
    }
}
