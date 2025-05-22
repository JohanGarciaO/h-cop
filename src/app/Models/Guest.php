<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Guest extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 
        'document', 
        'phone', 
        'email', 
        'address_id'
    ];

    public function address()
    {
        return $this->belongsTo(Address::class);
    }

    public function reservations()
    {
        return $this->hasMany(Reservation::class);
    }

    public function activeReservations()
    {
        return $this->hasMany(Reservation::class)->whereNull('check_out_at');
    }

    public function scopeAvailableBetween($query, $checkIn, $checkOut)
    {
        return $query->whereDoesntHave('reservations', function ($q) use ($checkIn, $checkOut) {
            $q->whereNull('check_out_at')
            ->where('scheduled_check_in', '<', $checkOut)
            ->where('scheduled_check_out', '>', $checkIn);
        });
    }
}
