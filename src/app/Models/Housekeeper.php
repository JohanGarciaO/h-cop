<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Housekeeper extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'document',
        'phone',
    ];

    /**
     * Limpezas realizadas por este camareiro(a)
     */
    public function cleanings()
    {
        return $this->hasMany(Cleaning::class);
    }
}
