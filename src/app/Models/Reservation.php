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

    public function checkedInBy()
    {
        return $this->belongsTo(User::class, 'check_in_by');
    }

    public function checkedOutBy()
    {
        return $this->belongsTo(User::class, 'check_out_by');
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
        // return !is_null($this->check_in_at) && is_null($this->check_out_at);
        return is_null($this->check_out_at);
    }

    public function adjustCheckinTime(): Carbon
    {
        [$hour, $minute, $second] = explode(':', Config::get('hotel.checkout_limit_time', '23:59:00'));

        $limit = $this->check_in_at->copy()->setTime($hour, $minute, $second);

        return $this->check_in_at->lessThanOrEqualTo($limit)
            ? $this->check_in_at->copy()->subDay()->setTime($hour, $minute, $second)
            : $this->check_in_at->copy()->setTime($hour, $minute, $second);
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
        return $query->whereNotNull('check_in_at')->whereNull('check_out_at');
    }

    public function scopeInactive($query)
    {
        return $query->whereNotNull('check_out_at');
    }

    // Diferença de dias agendado (INFORMATIVO)
    public function getNumberOfDaysScheduledAttribute()
    {
        return ceil($this->scheduled_check_in->diffInDays($this->scheduled_check_out));
    }
    // Preço de acordo com o agendado (INFORMATIVO)
    public function getTotalPriceScheduledAttribute()
    {
        return $this->daily_price * $this->number_of_days_scheduled;
    }

    // Diferença de dias real baseada no check-in e check-out (REAL)
    public function getNumberOfDaysAttribute()
    {
        // Checkout agendado + tolerância (pega o horário limite do config ou usa o padrão)
        [$hour, $minute, $second] = explode(':', Config::get('hotel.checkout_limit_time', '23:59:00'));
        
        if (!$this->check_in_at) {
            // Se não tiver sido feito o check-in retorna a diferença de dias do agendado
            return ceil($this->scheduled_check_in->diffInDays($this->scheduled_check_out));
        }
        
        if (!$this->check_out_at) {
            // Se tiver sido feito o Check-in mas não o Check-out retorna a diferença de dias do check-in até o momento atual
            return ceil($this->check_in_at->diffInDays(now()));   
        }
        
        // Ajusta hora evitando bugs (Se tiver passado do horário limite ajusta para o horário limite, se ainda não passou, subtrai um dia e seta o horário limite)
        $adjustCheckin = $this->adjustCheckinTime();
        
        // Mas se tiver sido feito tanto o check-in quanto o check-out, retorna a diferença de dias do checkIn até o checkOut (VALOR REAL FINAL)
        return ceil($adjustCheckin->diffInDays(Carbon::parse($this->check_out_at), false));
    }
    // Preço baseado no check-in e check-out (REAL)
    public function getTotalPriceAttribute()
    {
        return $this->daily_price * $this->number_of_days;
    }

    // Total de dias além do agendado (INFORMATIVO)
    public function getNumberOfDaysLateAttribute()
    {
        if ($this->check_out_at) {
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
    // Preço das diárias além do agendado (INFORMATIVO) 
    public function getTotalPriceLateAttribute()
    {
        return $this->number_of_days_late * $this->daily_price;
    }
}
