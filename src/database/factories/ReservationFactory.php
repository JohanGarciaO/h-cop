<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Guest;
use App\Models\Room;
use App\Models\User;

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
        $scheduleCheckIn = $this->faker->dateTimeBetween('-10 days', 'now');
        $scheduleCheckOut = (clone $scheduleCheckIn)->modify('+'.rand(1,3).'days');

        $checkIn = null;
        $checkInBy = null;
        $checkOut = null;
        $checkOutBy = null;
        $user = User::first()->id;
        $createdBy = $user;
        $updatedBy = $user;

        $daily = $this->faker->randomFloat(2, 100, 500);

        $isCheckedIn = $this->faker->boolean(70);

        if ($isCheckedIn) {
            $checkIn = (clone $scheduleCheckIn)->setTime(rand(0, 23), rand(0, 59));
            $checkInBy = User::inRandomOrder()->first()->id;
            $updatedBy = $checkInBy;
            
            // Decide aleatoriamente se a reserva está sem check-out ou finalizada
            $isCheckedOut = $this->faker->boolean(90);

            if ($isCheckedOut) {
                $checkOut = (clone $checkIn)->modify('+'.rand(1, 5).' days');
                $checkOutBy = User::inRandomOrder()->first()->id;
                $updatedBy = $checkOutBy;
            }
        }

        return [
            'guest_id' => Guest::inRandomOrder()->first()?->id ?? Guest::factory(),
            'room_id' => Room::inRandomOrder()->first()?->id ?? Room::factory(),
            'daily_price' => $daily,
            'scheduled_check_in' => $scheduleCheckIn,
            'scheduled_check_out' => $scheduleCheckOut,
            'check_in_at' => $checkIn,
            'check_in_by' => $checkInBy,
            'check_out_at' => $checkOut,
            'check_out_by' => $checkOutBy,
            'created_by' => $createdBy,
            'updated_by' => $updatedBy,
        ];
    }
}
