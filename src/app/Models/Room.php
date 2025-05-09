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
        'daily_capacity'
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
}
