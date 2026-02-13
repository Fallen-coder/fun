@extends('layouts.app')

@section('title', 'Available Flights')

@section('content')
<div class="section-container">
    <h2>Find & Book Flights</h2>
    
    <!-- Search Form -->
    <form id="search-form">
        <div class="form-row">
            <div class="form-group">
                <label for="from">From:</label>
                <select id="from" name="from">
                    <option value="">All Cities</option>
                </select>
            </div>

            <div class="form-group">
                <label for="to">To:</label>
                <select id="to" name="to">
                    <option value="">All Cities</option>
                </select>
            </div>
            
            <div class="form-group">
                <label for="departure">Departure Date:</label>
                <input type="date" id="departure" name="departure">
            </div>

            <div class="form-group">
                <label for="return">Return Date:</label>
                <input type="date" id="return" name="return">
            </div>
            
            <div class="form-group">
                <label for="max-price">Max Price:</label>
                <input type="number" id="max-price" name="max_price" placeholder="Any price">
            </div>
        </div>

        <button type="submit">Search Flights</button>
    </form>
    
    <div id="flight-results">
        <p>Loading flights...</p>
    </div>
</div>
@endsection

@section('scripts')
<script>
let allFlights = []; // Store all flights for filtering

document.addEventListener('DOMContentLoaded', function() {
    // Initialize date picker
    const today = new Date().toISOString().split('T')[0];
    document.getElementById('departure').min = today;
    
    // Load all available flights on page load
    fetch('/api/all-flights')
        .then(response => response.json())
        .then(flights => {
            allFlights = flights;
            if (flights.length === 0) {
                document.getElementById('flight-results').innerHTML = '<p>No flights available at the moment.</p>';
                return;
            }
            
            // Populate city dropdowns for search
            populateCityDropdowns(flights);
            
            // Display all flights initially
            displayFlights(flights);
        })
        .catch(error => {
            console.error('Error loading flights:', error);
            document.getElementById('flight-results').innerHTML = '<p>Error loading flights. Please try again later.</p>';
        });
    
    // Add event listener for search form
    document.getElementById('search-form').addEventListener('submit', handleSearch);
});

function populateCityDropdowns(flights) {
    // Populate search 'from' dropdown
    const searchFromSelect = document.getElementById('from');
    const searchToSelect = document.getElementById('to');
    
    // Get unique cities
    const fromCities = [...new Set(flights.map(flight => flight.from))].sort();
    const toCities = [...new Set(flights.map(flight => flight.to))].sort();
    
    // Populate search 'from' dropdown
    fromCities.forEach(city => {
        const option = document.createElement('option');
        option.value = city;
        option.textContent = city;
        searchFromSelect.appendChild(option);
    });
    
    // Populate search 'to' dropdown
    toCities.forEach(city => {
        const option = document.createElement('option');
        option.value = city;
        option.textContent = city;
        searchToSelect.appendChild(option);
    });
}

function handleSearch(e) {
    e.preventDefault();
    
    const formData = {
        from: document.getElementById('from').value,
        to: document.getElementById('to').value,
        departure: document.getElementById('departure').value,
        return: document.getElementById('return').value,
        max_price: document.getElementById('max-price').value
    };
    
    // If no filters are applied, show all flights
    if (!formData.from && !formData.to && !formData.departure && !formData.max_price) {
        displayFlights(allFlights);
        return;
    }
    
    // Perform search by filtering the allFlights array
    let searchedFlights = allFlights.filter(flight => {
        // Only apply filters for fields that have values
        const matchesFrom = !formData.from || flight.from.toLowerCase().includes(formData.from.toLowerCase());
        const matchesTo = !formData.to || flight.to.toLowerCase().includes(formData.to.toLowerCase());
        const matchesDate = !formData.departure || new Date(flight.departure).toISOString().split('T')[0] === formData.departure;
        const matchesMaxPrice = !formData.max_price || flight.price <= parseFloat(formData.max_price);
        
        // For return date, we'll skip this filter if not provided
        // (since return date isn't stored in our flight records)
        
        return matchesFrom && matchesTo && matchesDate && matchesMaxPrice;
    });
    
    // Display the search results
    displayFlights(searchedFlights);
}

function displayFlights(flights) {
    if (flights.length === 0) {
        document.getElementById('flight-results').innerHTML = '<p>No flights match your criteria.</p>';
        return;
    }

    document.getElementById('flight-results').innerHTML = flights.map(flight => `
        <div class="flight-card">
            <div class="flight-info">
                <div class="flight-route">
                    <div class="route-city">${flight.from}</div>
                    <div class="route-arrow">→</div>
                    <div class="route-city">${flight.to}</div>
                </div>
                <div class="flight-details">
                    <div class="detail-item">
                        <span class="detail-label">DEPARTURE</span>
                        <span class="detail-value">${formatDate(flight.departure)}</span>
                    </div>
                    <div class="detail-item">
                        <span class="detail-label">ARRIVAL</span>
                        <span class="detail-value">${formatDate(flight.arrival)}</span>
                    </div>
                    <div class="detail-item">
                        <span class="detail-label">DURATION</span>
                        <span class="detail-value">${formatDuration(flight.departure, flight.arrival)}</span>
                    </div>
                    <div class="detail-item">
                        <span class="detail-label">SEATS AVAILABLE</span>
                        <span class="detail-value">${flight.seats_available}</span>
                    </div>
                    <div class="detail-item">
                        <span class="detail-label">AIRLINE</span>
                        <span class="detail-value">${flight.airline}</span>
                    </div>
                </div>
            </div>
            <div class="price-and-button">
                <div class="price">$${flight.price}<br><small>per person</small></div>
                <button class="book-button" onclick="openBookingModal(${flight.id}, 1, ${flight.price})">Book Now</button>
            </div>
        </div>
    `).join('');
}
</script>
@endsection