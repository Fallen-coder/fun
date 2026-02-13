<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use App\Models\Booking;

class BookingTest extends TestCase
{
    /** @test */
    public function it_has_the_correct_fillable_attributes()
    {
        $booking = new Booking();
        
        $this->assertEquals([
            'flight_id',
            'passenger_name',
            'passenger_email',
            'passenger_count',
            'total_price',
            'booking_reference',
            'status'
        ], $booking->getFillable());
    }

    /** @test */
    public function it_has_the_correct_casts()
    {
        $booking = new Booking();
        $casts = $booking->getCasts();
        
        $this->assertArrayHasKey('total_price', $casts);
        $this->assertArrayHasKey('status', $casts);
        $this->assertEquals('decimal:2', $casts['total_price']);
        $this->assertEquals('string', $casts['status']);
    }
}