<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Flight Booking System')</title>
    <link rel="stylesheet" href="{{ asset('css/flights.css') }}">
</head>
<body>
    <header>
        <h1>✈️ Flight Booking System</h1>
    </header>

    <nav class="main-nav">
        <a href="{{ route('flights.available') }}" class="{{ request()->routeIs('flights.available') ? 'active' : '' }}">Find Flights</a>
        <a href="{{ route('bookings.index') }}" class="{{ request()->routeIs('bookings.index') ? 'active' : '' }}">My Bookings</a>
    </nav>

    <main>
        @yield('content')
    </main>

    <!-- Modal for booking -->
    <div id="booking-modal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <h2>Complete Your Booking</h2>
            <form id="booking-form">
                <input type="hidden" id="selected-flight-id" name="flight_id">
                
                <div class="form-group">
                    <label for="passenger-name">Passenger Name:</label>
                    <input type="text" id="passenger-name" name="passenger_name" required>
                </div>
                
                <div class="form-group">
                    <label for="passenger-email">Passenger Email:</label>
                    <input type="email" id="passenger-email" name="passenger_email" required>
                </div>
                
                <div class="form-group">
                    <label for="booking-passengers">Number of Passengers:</label>
                    <input type="number" id="booking-passengers" name="passenger_count" min="1" max="10" required>
                </div>
                
                <button type="submit">Confirm Booking</button>
            </form>
        </div>
    </div>

    <script src="{{ asset('js/flights.js') }}"></script>
    @yield('scripts')
</body>
</html>