<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Enums\RoomCleaningStatus;

class Cleaning extends Model
{
    protected $fillable = [
        'room_id',
        'reservation_id',
        'housekeeper_id',
        'status',
        'notes',
        'updated_by',
    ];

    protected $casts = [
        'status' => RoomCleaningStatus::class,
    ];

    /**
     * Quarto relacionado a esta limpeza
     */
    public function room()
    {
        return $this->belongsTo(Room::class);
    }

    /**
     * Reserva relacionada à limpeza
     */
    public function reservation()
    {
        return $this->belongsTo(Reservation::class);
    }

    /**
     * Camareiro(a) responsável pela limpeza
     */
    public function housekeeper()
    {
        return $this->belongsTo(Housekeeper::class);
    }

    /**
     * Usuário que atualizou este status
     */
    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
}
