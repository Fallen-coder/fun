<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Booking extends Model
{
    protected $fillable = [
        'flight_id',
        'passenger_name',
        'passenger_email',
        'passenger_count',
        'total_price',
        'booking_reference',
        'status'
    ];

    protected $casts = [
        'total_price' => 'decimal:2',
        'status' => 'string',
    ];

    public function flight(): BelongsTo
    {
        return $this->belongsTo(Flight::class);
    }
}
