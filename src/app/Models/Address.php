<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Address extends Model
{
    use hasFactory;

    protected $fillable = [
        'postal_code', 
        'state', 
        'city', 
        'street',
        'number', 
        'neighborhood', 
        'complement'
    ];

    public function guests()
    {
        return $this->hasMany(Guest::class);
    }
}
