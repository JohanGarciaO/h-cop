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

    protected $appends = [
        'status'
    ];

    public function guest()
    {
        return $this->belongsTo(Guest::class);
    }

    public function room()
    {
        return $this->belongsTo(Room::class);
    }

    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function isCheckIn()
    {
        return !is_null($this->check_in_at);
    }

    public function isCheckOut()
    {
        return !is_null($this->check_out_at);
    }

    public function isActive()
    {
        return is_null($this->check_out_at);
    }

    public function getStatusAttribute()
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

    // Diferença de dias agendado
    public function getNumberOfDaysScheduledAttribute()
    {
        return ceil($this->scheduled_check_in->diffInDays($this->scheduled_check_out));
    }
    // Preço e acordo com o agendado
    public function getTotalPriceScheduledAttribute()
    {
        return $this->daily_price * $this->number_of_days_scheduled;
    }

    // Diferença de dias real baseada no check-in e check-out
    public function getNumberOfDaysAttribute()
    {
        // Checkout agendado + tolerância (pega o horário limite do config ou usa o padrão)
        [$hour, $minute, $second] = explode(':', Config::get('hotel.checkout_limit_time', '23:59:00'));
        
        if (!$this->check_in_at) {
            // Se não tiver sido feito o check-in retorna a diferença de dias do agendado
            return ceil($this->scheduled_check_in->diffInDays($this->scheduled_check_out));
        }else{
            $checkIn = Carbon::parse($this->check_in_at)->setTime($hour, $minute, $second);
        }
        
        if (!$this->check_out_at) {
            // Se não tiver sido feito o Check-out retorna a diferença de dias do check-in até o momento atual
            return ceil($checkIn->diffInDays(now()));   
        }

        return ceil($checkIn->diffInDays(Carbon::parse($this->check_out_at), false));
    }
    // preço (O real baseada no check-in e check-out)
    public function getTotalPriceAttribute()
    {
        return $this->daily_price * $this->number_of_days;
    }

    public function getNumberOfDaysLateAttribute()
    {
        if ($this->check_out_at && $this->scheduled_check_out) {
            $checkout = Carbon::parse($this->check_out_at);

            // Checkout agendado + tolerância (pega o horário limite do config ou usa o padrão)
            [$hour, $minute, $second] = explode(':', Config::get('hotel.checkout_limit_time', '23:59:00'));
            $scheduled = Carbon::parse($this->scheduled_check_out)->setTime($hour, $minute, $second);

            if ($checkout->greaterThan($scheduled)) {
                $daysLate = ceil($scheduled->diffInDays($checkout));
                return $daysLate;
            }
        }
        return 0;
    }
    
    public function getTotalPriceLateAttribute()
    {
        return $this->number_of_days_late * $this->daily_price;
    }
}
