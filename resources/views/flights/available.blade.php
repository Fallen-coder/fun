@extends('layouts.app')

@section('title', 'Available Flights')

@section('content')
<div class="section-container">
    <h2>Available Flights</h2>
    
    <!-- Filters -->
    <div class="filters-container">
        <div class="form-row">
            <div class="form-group">
                <label for="filter-from">From:</label>
                <select id="filter-from">
                    <option value="">All Cities</option>
                </select>
            </div>
            
            <div class="form-group">
                <label for="filter-to">To:</label>
                <select id="filter-to">
                    <option value="">All Cities</option>
                </select>
            </div>
            
            <div class="form-group">
                <label for="filter-max-price">Max Price:</label>
                <input type="number" id="filter-max-price" placeholder="Any price">
            </div>
        </div>
    </div>
    
    <div id="flight-results">
        <p>Loading flights...</p>
    </div>
</div>
@endsection

@section('scripts')
<script>
let allFlights = []; // Store all flights for filtering

document.addEventListener('DOMContentLoaded', function() {
    // Load all available flights on page load
    fetch('/api/all-flights')
        .then(response => response.json())
        .then(flights => {
            allFlights = flights;
            if (flights.length === 0) {
                document.getElementById('flight-results').innerHTML = '<p>No flights available at the moment.</p>';
                return;
            }
            
            // Populate city dropdowns
            populateCityDropdowns(flights);
            
            // Display all flights initially
            displayFlights(flights);
        })
        .catch(error => {
            console.error('Error loading flights:', error);
            document.getElementById('flight-results').innerHTML = '<p>Error loading flights. Please try again later.</p>';
        });
    
    // Add event listeners for filters
    document.getElementById('filter-from').addEventListener('change', applyFilters);
    document.getElementById('filter-to').addEventListener('change', applyFilters);
    document.getElementById('filter-max-price').addEventListener('input', applyFilters);
});

function populateCityDropdowns(flights) {
    const fromSelect = document.getElementById('filter-from');
    const toSelect = document.getElementById('filter-to');
    
    // Get unique cities
    const fromCities = [...new Set(flights.map(flight => flight.from))].sort();
    const toCities = [...new Set(flights.map(flight => flight.to))].sort();
    
    // Populate 'from' dropdown
    fromCities.forEach(city => {
        const option = document.createElement('option');
        option.value = city;
        option.textContent = city;
        fromSelect.appendChild(option);
    });
    
    // Populate 'to' dropdown
    toCities.forEach(city => {
        const option = document.createElement('option');
        option.value = city;
        option.textContent = city;
        toSelect.appendChild(option);
    });
}

function applyFilters() {
    const fromFilter = document.getElementById('filter-from').value.toLowerCase();
    const toFilter = document.getElementById('filter-to').value.toLowerCase();
    const maxPriceFilter = parseFloat(document.getElementById('filter-max-price').value) || Infinity;
    
    const filteredFlights = allFlights.filter(flight => {
        const matchesFrom = !fromFilter || flight.from.toLowerCase().includes(fromFilter);
        const matchesTo = !toFilter || flight.to.toLowerCase().includes(toFilter);
        const matchesPrice = flight.price <= maxPriceFilter;
        
        return matchesFrom && matchesTo && matchesPrice;
    });
    
    displayFlights(filteredFlights);
}

function displayFlights(flights) {
    if (flights.length === 0) {
        document.getElementById('flight-results').innerHTML = '<p>No flights match your filters.</p>';
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