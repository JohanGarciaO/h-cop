<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Enums\Gender;

class Guest extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 
        'document', 
        'gender',
        'committee_id',
        'phone', 
        'email', 
        'address_id'
    ];

    protected $casts = [
        'gender' => Gender::class,
    ];

    public function committee()
    {
        return $this->belongsTo(Committee::class);
    }

    public function address()
    {
        return $this->belongsTo(Address::class)->withDefault();
    }

    public function reservations()
    {
        return $this->hasMany(Reservation::class)->orderBy('created_at','desc');
    }

    public function activeReservations()
    {
        return $this->hasMany(Reservation::class)->whereNull('check_out_at');
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by');
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
}
