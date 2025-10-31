<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Builder;
use carbon\Carbon;

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
        return $this->hasMany(Reservation::class)->orderBy('created_at','desc');
    }

    public function activeReservations()
    {
        return $this->hasMany(Reservation::class)->whereNull('check_out_at');
    }

    public function occupation()
    {
        $today = carbon::now()->format('Y-m-d');

        return $this->reservations()
            ->whereNull('check_out_at')
            ->where('scheduled_check_in', '<=', $today)
            ->where('scheduled_check_out', '>=', $today)
            ->count();
    }

    public function isAvailable()
    {
        return $this->capacity > $this->activeReservations()->count();
    }

    public function isAvailableBetween($checkIn, $checkOut, $exceptReservationId = null)
    {
        $activeReservationsCount = $this->reservations()
            ->whereNull('check_out_at')
            ->where('scheduled_check_in', '<', $checkOut)
            ->where('scheduled_check_out', '>', $checkIn)
            ->when($exceptReservationId, fn($q) => $q->where('id', '!=', $exceptReservationId))
            ->count();

        return $activeReservationsCount < $this->capacity;
    }

    public function scopeAvailableBetween($query, $checkIn, $checkOut, $exceptReservationId = null)
    {
        return $query->where(function ($q) use ($checkIn, $checkOut, $exceptReservationId) {

            // Caso não tenha nenhuma reserva no período estará livre
            $q->whereDoesntHave('reservations', function ($r) use ($checkIn, $checkOut) {
                $r->whereNull('check_out_at')
                ->where('scheduled_check_in', '<', $checkOut)
                ->where('scheduled_check_out', '>', $checkIn);
            })
            // Caso tenha reservas mas ainda não extrapolou a capacidade máxima
            ->orWhereIn('id', function ($sub) use ($checkIn, $checkOut, $exceptReservationId) {
                $sub->select('room_id')
                    ->from('reservations')
                    ->whereNull('check_out_at')
                    ->where('scheduled_check_in', '<', $checkOut)
                    ->where('scheduled_check_out', '>', $checkIn)
                    ->when($exceptReservationId, fn($s) => $s->where('id', '!=', $exceptReservationId))
                    ->groupBy('room_id')
                    ->havingRaw('COUNT(*) < (SELECT capacity FROM rooms WHERE rooms.id = reservations.room_id)');
            })
            // Garante que só aceitará reservas se o quarto estiver "pronto para uso" ou não tiver histórico de limpeza
            ->whereDoesntHave('lastCleaning')           
	    ->orWhereHas('lastCleaning', function($q) {
                $q->where('status', 'READY');
            });

        });
    }

    public function cleanings()
    {
        return $this->hasMany(Cleaning::class)->orderBy('created_at','desc');
    }

    public function lastCleaning()
    {
        return $this->hasOne(Cleaning::class)->latestOfMany();
    }
}
