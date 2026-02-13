<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Flight;
use DateTime;

class FlightSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Major cities from around the world
        $cities = [
            'New York', 'London', 'Paris', 'Tokyo', 'Sydney', 
            'Los Angeles', 'Miami', 'Dubai', 'Singapore', 'Toronto',
            'Berlin', 'Rome', 'Madrid', 'Barcelona', 'Amsterdam',
            'Vienna', 'Prague', 'Moscow', 'Beijing', 'Shanghai',
            'Hong Kong', 'Bangkok', 'Seoul', 'Delhi', 'Mumbai',
            'Rio de Janeiro', 'São Paulo', 'Buenos Aires', 'Mexico City', 'Lima',
            'Cairo', 'Cape Town', 'Nairobi', 'Lagos', 'Casablanca',
            'Dublin', 'Oslo', 'Stockholm', 'Helsinki', 'Warsaw',
            'Athens', 'Istanbul', 'Tel Aviv', 'Manila', 'Jakarta'
        ];
        
        // Airlines
        $airlines = [
            'Sky Airlines', 'EuroFly', 'Pacific Airways', 'Coastal Air', 'Desert Wings',
            'Global Airways', 'Oceanic Airlines', 'Continental Fly', 'Mountain Air', 'Sunset Airlines',
            'Northern Winds', 'Southern Cross', 'Eastern Express', 'Western Jet', 'Central Airways'
        ];
        
        // Generate 50 random flights
        for ($i = 0; $i < 50; $i++) {
            $fromCity = $cities[array_rand($cities)];
            $toCity = $cities[array_rand($cities)];
            
            // Ensure from and to cities are different
            while ($toCity === $fromCity) {
                $toCity = $cities[array_rand($cities)];
            }
            
            $departureDate = new DateTime('+'.rand(1, 30).' days '.rand(0, 23).':'.rand(0, 59).':00');
            $durationHours = rand(4, 15);
            $durationMinutes = rand(0, 59);
            $arrivalDate = clone $departureDate;
            $arrivalDate->modify("+{$durationHours} hours +{$durationMinutes} minutes");
            
            Flight::create([
                'from' => $fromCity,
                'to' => $toCity,
                'departure' => $departureDate,
                'arrival' => $arrivalDate,
                'price' => rand(150, 1500),
                'airline' => $airlines[array_rand($airlines)],
                'seats_available' => rand(50, 300)
            ]);
        }
    }
}
