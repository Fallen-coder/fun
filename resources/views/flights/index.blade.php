<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Flight Booking System</title>
    <link rel="stylesheet" href="{{ asset('css/flights.css') }}">
</head>
<body>
    <header>
        <h1>✈️ Flight Booking System</h1>
    </header>

    <main>
        <!-- Tab Navigation -->
        <div class="tab-navigation">
            <button class="tab-button active" data-tab="search">Search Flights</button>
            <button class="tab-button" data-tab="available">Available Flights</button>
            <button class="tab-button" data-tab="bookings">My Bookings</button>
        </div>

        <!-- Search Tab -->
        <div id="search-tab" class="tab-content active">
            <div class="section-container">
                <h2>Find Flights</h2>
                <form id="search-form">
                    <div class="form-row">
                        <div class="form-group">
                            <label for="from">From:</label>
                            <input type="text" id="from" name="from" required placeholder="Departure city">
                        </div>

                        <div class="form-group">
                            <label for="to">To:</label>
                            <input type="text" id="to" name="to" required placeholder="Destination city">
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="departure">Departure Date:</label>
                            <input type="date" id="departure" name="departure" required>
                        </div>

                        <div class="form-group">
                            <label for="return">Return Date:</label>
                            <input type="date" id="return" name="return">
                        </div>

                        <div class="form-group">
                            <label for="passengers">Passengers:</label>
                            <select id="passengers" name="passengers">
                                <option value="1">1</option>
                                <option value="2">2</option>
                                <option value="3">3</option>
                                <option value="4">4</option>
                                <option value="5">5</option>
                            </select>
                        </div>
                    </div>

                    <button type="submit">Search Flights</button>
                </form>
            </div>
        </div>

        <!-- Available Flights Tab -->
        <div id="available-tab" class="tab-content">
            <div class="section-container">
                <h2>Available Flights</h2>
                <div id="flight-results">
                    <p>No flights found. Search for flights to get started.</p>
                </div>
            </div>
        </div>

        <!-- My Bookings Tab -->
        <div id="bookings-tab" class="tab-content">
            <div class="section-container">
                <h2>My Bookings</h2>
                <div id="my-bookings">
                    <p>Loading bookings...</p>
                </div>
            </div>
        </div>
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
</body>
</html>