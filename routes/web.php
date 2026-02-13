<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FlightController;
use App\Http\Controllers\BookingController;

// Main landing page - redirect to search
Route::get('/', function () {
    return redirect()->route('flights.index');
});

// Flight routes
Route::get('/flights', [FlightController::class, 'searchView'])->name('flights.index');
Route::get('/flights/available', [FlightController::class, 'availableView'])->name('flights.available');
Route::get('/api/cities', [FlightController::class, 'getCities']);
Route::post('/api/search', [FlightController::class, 'search']);
Route::post('/api/book', [FlightController::class, 'book']);

// Booking routes
Route::get('/bookings', [BookingController::class, 'index'])->name('bookings.index');
Route::get('/api/bookings', [FlightController::class, 'getBookings']);
Route::post('/api/cancel/{id}', [FlightController::class, 'cancelBooking']);
