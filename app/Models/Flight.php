<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Flight extends Model
{
    protected $fillable = [
        'from',
        'to',
        'departure',
        'arrival',
        'price',
        'airline',
        'seats_available'
    ];

    protected $casts = [
        'departure' => 'datetime',
        'arrival' => 'datetime',
        'price' => 'decimal:2',
    ];

    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class);
    }
    
    public static function getUniqueCities($direction = 'from')
    {
        return self::distinct()
                   ->pluck($direction)
                   ->sort()
                   ->values()
                   ->toArray();
    }
}
