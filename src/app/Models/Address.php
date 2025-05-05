<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Address extends Model
{
    use hasFactory;

    protected $fillable = [
        'postal_code', 
        'state_id',
        'city_id',
        'street',
        'number', 
        'neighborhood', 
        'complement'
    ];

    public function state(){
        return $this->belongsTo(State::class);
    }

    public function city(){
        return $this->belongsTo(City::class);
    }

    public function guests()
    {
        return $this->hasMany(Guest::class);
    }
}
