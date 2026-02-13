<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use App\Models\Flight;

class FlightTest extends TestCase
{
    /** @test */
    public function it_has_the_correct_fillable_attributes()
    {
        $flight = new Flight();
        
        $this->assertEquals([
            'from',
            'to',
            'departure',
            'arrival',
            'price',
            'airline',
            'seats_available'
        ], $flight->getFillable());
    }

    /** @test */
    public function it_has_the_correct_casts()
    {
        $flight = new Flight();
        $casts = $flight->getCasts();
        
        $this->assertArrayHasKey('departure', $casts);
        $this->assertArrayHasKey('arrival', $casts);
        $this->assertArrayHasKey('price', $casts);
        $this->assertEquals('datetime', $casts['departure']);
        $this->assertEquals('datetime', $casts['arrival']);
        $this->assertEquals('decimal:2', $casts['price']);
    }

    /** @test */
    public function it_has_static_method_to_get_unique_cities()
    {
        $this->assertTrue(method_exists(Flight::class, 'getUniqueCities'));
    }
}