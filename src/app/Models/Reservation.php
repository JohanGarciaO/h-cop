<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Reservation extends Model
{
    use HasFactory;

    protected $fillable = [
        'guest_id', 
        'room_id', 
        'dialy_price', 
        'total_price',
        'check_in_at', 
        'check_out_at'
    ];

    public function guest()
    {
        return $this->belongsTo(Guest::class);
    }

    public function room()
    {
        return $this->belongsTo(Room::class);
    }

    public function isActive()
    {
        return is_null($this->check_out_at);
    }

    public function scopeActive($query)
    {
        return $query->whereNull('check_out_at');
    }

    public function scopeInactive($query)
    {
        return $query->whereNotNull('check_out_at');
    }
}
