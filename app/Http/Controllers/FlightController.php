<?php

namespace App\Http\Controllers;

use App\Models\Flight;
use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class FlightController extends Controller
{
    public function index()
    {
        return redirect()->route('flights.available');
    }

    public function availableView()
    {
        return view('flights.available');
    }

    public function search(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'from' => 'required|string|max:255',
                'to' => 'required|string|max:255',
                'departure' => 'required|date',
                'passengers' => 'nullable|integer|min:1|max:10'
            ]);

            $flights = Flight::where('from', 'LIKE', '%' . $request->from . '%')
                ->where('to', 'LIKE', '%' . $request->to . '%')
                ->whereDate('departure', $request->departure)
                ->get();

            return response()->json($flights);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json(['errors' => $e->errors()], 422);
        }
    }

    public function book(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'flight_id' => 'required|exists:flights,id',
                'passenger_name' => 'required|string|max:255',
                'passenger_email' => 'required|email|max:255',
                'passenger_count' => 'required|integer|min:1|max:10'
            ]);

            $flight = Flight::findOrFail($request->flight_id);

            // Check if enough seats are available
            if ($flight->seats_available < $request->passenger_count) {
                return response()->json(['error' => "Not enough seats available. Only {$flight->seats_available} seats remain."], 400);
            }

            $booking = Booking::create([
                'flight_id' => $request->flight_id,
                'passenger_name' => $request->passenger_name,
                'passenger_email' => $request->passenger_email,
                'passenger_count' => $request->passenger_count,
                'total_price' => $flight->price * $request->passenger_count,
                'booking_reference' => strtoupper(Str::random(8)),
                'status' => 'confirmed'
            ]);

            // Update available seats
            $flight->update([
                'seats_available' => $flight->seats_available - $request->passenger_count
            ]);

            return response()->json(['success' => true, 'booking' => $booking]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json(['errors' => $e->errors()], 422);
        }
    }

    public function getBookings()
    {
        $bookings = Booking::with('flight')->orderBy('created_at', 'desc')->get();
        return response()->json($bookings);
    }

    public function cancelBooking($id)
    {
        try {
            $booking = Booking::findOrFail($id);
            $booking->update(['status' => 'cancelled']);

            // Restore seats to the flight
            $flight = $booking->flight;
            $flight->update([
                'seats_available' => $flight->seats_available + $booking->passenger_count
            ]);

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to cancel booking'], 500);
        }
    }
    
    public function getCities(Request $request)
    {
        $direction = $request->get('direction', 'from');
        $cities = Flight::getUniqueCities($direction);
        
        return response()->json($cities);
    }
    
    public function getAllFlights()
    {
        $flights = Flight::all();
        return response()->json($flights);
    }
    
    public function getFlight($id)
    {
        $flight = Flight::findOrFail($id);
        return response()->json($flight);
    }
}
