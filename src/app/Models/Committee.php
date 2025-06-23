<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Carbon\Carbon;

use Illuminate\Database\Eloquent\Model;

class Committee extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'name',
        'description',
        'created_by',
        'updated_by',
    ];

    public function guests()
    {
        return $this->hasMany(Guest::class);
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function getCreatedAtFormattedAttribute()
    {
        return Carbon::parse($this->created_at)->format('d/m/Y à\s H:i:s');
    }

    public function getUpdatedAtFormattedAttribute()
    {
        return Carbon::parse($this->updated_at)->format('d/m/Y à\s H:i:s');
    }
}
