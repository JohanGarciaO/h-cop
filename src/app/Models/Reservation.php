<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Config;
use carbon\Carbon;

class Reservation extends Model
{
    use HasFactory;

    protected $fillable = [
        'guest_id', 
        'room_id', 
        'daily_price', 
        'scheduled_check_in',
        'scheduled_check_out',
        'check_in_at', 
        'check_out_at'
    ];

    protected $casts = [
        'scheduled_check_in' => 'date',
        'scheduled_check_out' => 'date',
        'check_in_at' => 'datetime',
        'check_out_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
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

    public function status()
    {
        $status;
        if($this->isActive()){
            return $status = ($this->check_in_at) ? 'check-out pendente' : 'check-in pendente';
        }else{
            return $status = 'hospedagem finalizada';
        }
    }

    public function scopeActive($query)
    {
        return $query->whereNull('check_out_at');
    }

    public function scopeInactive($query)
    {
        return $query->whereNotNull('check_out_at');
    }

    public function getNumberOfDaysAttribute()
    {
        // Garante que ambas as datas estejam definidas e como instâncias do Carbon
        if (!$this->scheduled_check_in || !$this->scheduled_check_out) {
            return 1;
        }

        return Carbon::parse($this->scheduled_check_in)
                    ->diffInDays(Carbon::parse($this->scheduled_check_out)) ?: 1;
    }

    public function getTotalPriceAttribute()
    {
        return $this->daily_price * $this->number_of_days;
    }
    
    public function hasLateCheckout()
    {
        if ($this->check_out_at && $this->scheduled_check_out) {
            $checkout = Carbon::parse($this->check_out_at);

            // Checkout agendado + tolerância (pega o horário limite do config ou usa o padrão)
            [$hour, $minute, $second] = explode(':', Config::get('hotel.checkout_limit_time', '23:59:00'));
            $scheduled = Carbon::parse($this->scheduled_check_out)->setTime($hour, $minute, $second);

            if ($checkout->greaterThan($scheduled)) {
                $daysLate = ceil($scheduled->diffInDays($checkout));
                return $daysLate * $this->daily_price;
            }
        }
        return false;
    }
}
