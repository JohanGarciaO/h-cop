<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Builder;

class Room extends Model
{
    use HasFactory;

    protected $fillable = [
        'number', 
        'capacity',
        'daily_price'
    ];

    public function reservations()
    {
        return $this->hasMany(Reservation::class);
    }

    public function activeReservations()
    {
        return $this->hasMany(Reservation::class)->whereNull('check_out_at');
    }

    public function isAvailable()
    {
        return $this->capacity > $this->activeReservations()->count();
    }

    public function isAvailableBetween($checkIn, $checkOut)
    {
        return !$this->activeReservations()
            ->where(function ($query) use ($checkIn, $checkOut) {
                $query->where('scheduled_check_in', '<', $checkOut)
                    ->where('scheduled_check_out', '>', $checkIn);
            })
            ->exists();
    }

    public function scopeAvailableBetween($query, $checkIn, $checkOut, $exceptReservationId = null)
    {
        return $query->whereDoesntHave('reservations', function ($q) use ($checkIn, $checkOut, $exceptReservationId) {
            $q->whereNull('check_out_at')
            ->where('scheduled_check_in', '<', $checkOut)
            ->where('scheduled_check_out', '>', $checkIn);

            if ($exceptReservationId) {
                $q->where('id', '!=', $exceptReservationId);
            }
        });
    }

    public function cleanings()
    {
        return $this->hasMany(Cleaning::class);
    }

    public function lastCleaning()
    {
        // $cleanings = $this->relationLoaded('cleanings') ? $this->cleanings : $this->cleanings();
        // return $this->cleanings()->orderByDesc('created_at')->first();
        return $this->hasOne(Cleaning::class)->latestOfMany();
    }
}
