// flights.js

// DOM Elements (only initialize if they exist on the current page)
const searchForm = document.getElementById('search-form');
const flightResults = document.getElementById('flight-results');
const myBookings = document.getElementById('my-bookings');
const bookingModal = document.getElementById('booking-modal');
const closeModal = document.querySelector('.close');
const bookingForm = document.getElementById('booking-form');
const selectedFlightIdInput = document.getElementById('selected-flight-id');

// Format date for display
function formatDate(dateString) {
    const options = { year: 'numeric', month: 'short', day: 'numeric', hour: '2-digit', minute: '2-digit' };
    return new Date(dateString).toLocaleDateString(undefined, options);
}

// Format time duration
function formatDuration(departure, arrival) {
    const depTime = new Date(departure);
    const arrTime = new Date(arrival);
    const diffMs = arrTime - depTime;
    const diffHrs = Math.floor(diffMs / 3600000);
    const diffMins = Math.floor((diffMs % 3600000) / 60000);
    return `${diffHrs}h ${diffMins}m`;
}

// Render flight results (only on available flights page)
function renderFlights(flights) {
    if (document.getElementById('flight-results')) {
        if (flights.length === 0) {
            document.getElementById('flight-results').innerHTML = '<p class="no-results">No flights found for your search criteria.</p>';
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
                            <span class="detail-label">PASSENGERS</span>
                            <span class="detail-value">${flight.passenger_count || 1}</span>
                        </div>
                        <div class="detail-item">
                            <span class="detail-label">AIRLINE</span>
                            <span class="detail-value">${flight.airline}</span>
                        </div>
                    </div>
                </div>
                <div class="price-and-button">
                    <div class="price">$${flight.total_price || flight.price}<br><small>${flight.passenger_count || 1} x $${flight.price}</small></div>
                    <button class="book-button" onclick="openBookingModal(${flight.id}, ${flight.passenger_count || 1}, ${flight.price})">Book Now</button>
                </div>
            </div>
        `).join('');
    }
}

// Render bookings (only on bookings page)
function renderBookings() {
    if (document.getElementById('my-bookings')) {
        fetch('/api/bookings')
            .then(response => response.json())
            .then(bookings => {
                if (bookings.length === 0) {
                    document.getElementById('my-bookings').innerHTML = '<p>No bookings yet.</p>';
                    return;
                }

                document.getElementById('my-bookings').innerHTML = bookings.map(booking => `
                    <div class="booking-card">
                        <div class="booking-info">
                            <div class="flight-route">
                                <div class="route-city">${booking.flight.from}</div>
                                <div class="route-arrow">→</div>
                                <div class="route-city">${booking.flight.to}</div>
                            </div>
                            <div class="flight-details">
                                <div class="detail-item">
                                    <span class="detail-label">DEPARTURE</span>
                                    <span class="detail-value">${formatDate(booking.flight.departure)}</span>
                                </div>
                                <div class="detail-item">
                                    <span class="detail-label">PASSENGERS</span>
                                    <span class="detail-value">${booking.passenger_count || 1}</span>
                                </div>
                                <div class="detail-item">
                                    <span class="detail-label">TOTAL PRICE</span>
                                    <span class="detail-value">$${booking.total_price || booking.flight.price}</span>
                                </div>
                                <div class="detail-item">
                                    <span class="detail-label">BOOKING REF</span>
                                    <span class="detail-value">${booking.booking_reference}</span>
                                </div>
                            </div>
                        </div>
                        ${booking.status !== 'cancelled' ? 
                            `<button class="delete-button" onclick="cancelBooking(${booking.id})">Cancel</button>` : 
                            `<span class="detail-value" style="color: #6a8ca5;">CANCELLED</span>`
                        }
                    </div>
                `).join('');
            })
            .catch(error => {
                console.error('Error fetching bookings:', error);
                document.getElementById('my-bookings').innerHTML = '<p>Error loading bookings.</p>';
            });
    }
}

// Search flights
function searchFlights(formData) {
    fetch('/api/search', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify(formData)
    })
    .then(response => response.json())
    .then(data => {
        if (Array.isArray(data)) {
            // Store search results in sessionStorage to access on available flights page
            sessionStorage.setItem('searchResults', JSON.stringify(data));
            
            // Redirect to available flights page
            window.location.href = '/flights/available';
        } else if (data.errors) {
            let errorMessage = 'Validation errors:\n';
            for (let field in data.errors) {
                errorMessage += `- ${field}: ${data.errors[field].join(', ')}\n`;
            }
            alert(errorMessage);
        } else {
            alert('An unexpected error occurred.');
        }
    })
    .catch(error => {
        console.error('Error searching flights:', error);
        alert('An error occurred while searching for flights.');
    });
}

// Open booking modal
function openBookingModal(flightId, passengerCount, price) {
    if (selectedFlightIdInput) {
        selectedFlightIdInput.value = flightId;
        document.getElementById('booking-passengers').value = passengerCount;
        bookingModal.style.display = 'block';
    }
}

// Close booking modal
function closeBookingModal() {
    if (bookingModal) {
        bookingModal.style.display = 'none';
        if (bookingForm) {
            bookingForm.reset();
        }
    }
}

// Book a flight
function bookFlight(formData) {
    fetch('/api/book', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify(formData)
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert(`Booking confirmed! Reference: ${data.booking.booking_reference}`);
            closeBookingModal();
            
            // Redirect to bookings page to see the new booking
            window.location.href = '/bookings';
        } else if (data.errors) {
            let errorMessage = 'Validation errors:\n';
            for (let field in data.errors) {
                errorMessage += `- ${field}: ${data.errors[field].join(', ')}\n`;
            }
            alert(errorMessage);
        } else {
            alert(data.error || 'Booking failed. Please try again.');
        }
    })
    .catch(error => {
        console.error('Error booking flight:', error);
        alert('An error occurred while booking the flight.');
    });
}

// Cancel a booking
function cancelBooking(bookingId) {
    if (confirm('Are you sure you want to cancel this booking?')) {
        fetch(`/api/cancel/${bookingId}`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Booking cancelled successfully!');
                // Reload the page to refresh bookings
                location.reload();
            } else if (data.errors) {
                let errorMessage = 'Cancellation errors:\n';
                for (let field in data.errors) {
                    errorMessage += `- ${field}: ${data.errors[field].join(', ')}\n`;
                }
                alert(errorMessage);
            } else {
                alert('Failed to cancel booking. Please try again.');
            }
        })
        .catch(error => {
            console.error('Error cancelling booking:', error);
            alert('An error occurred while cancelling the booking.');
        });
    }
}

// Event Listeners
document.addEventListener('DOMContentLoaded', () => {
    // Initialize date picker on search page
    if (document.getElementById('departure')) {
        const today = new Date().toISOString().split('T')[0];
        document.getElementById('departure').min = today;
    }

    // Handle search form submission (only on search page)
    if (searchForm) {
        searchForm.addEventListener('submit', (e) => {
            e.preventDefault();

            const formData = {
                from: document.getElementById('from').value,
                to: document.getElementById('to').value,
                departure: document.getElementById('departure').value,
                return: document.getElementById('return').value,
                passengers: document.getElementById('passengers').value
            };

            searchFlights(formData);
        });
    }

    // On available flights page, load search results from sessionStorage
    if (document.getElementById('flight-results') && window.location.pathname === '/flights/available') {
        const searchResults = sessionStorage.getItem('searchResults');
        if (searchResults) {
            renderFlights(JSON.parse(searchResults));
        } else {
            document.getElementById('flight-results').innerHTML = '<p>No search results found. <a href="/flights">Search for flights</a></p>';
        }
    }

    // On bookings page, load bookings
    if (document.getElementById('my-bookings') && window.location.pathname === '/bookings') {
        renderBookings();
    }

    // Close modal when clicking on close button
    if (closeModal) {
        closeModal.addEventListener('click', closeBookingModal);
    }

    // Close modal when clicking outside of it
    if (bookingModal) {
        window.addEventListener('click', (event) => {
            if (event.target === bookingModal) {
                closeBookingModal();
            }
        });
    }

    // Handle booking form submission
    if (bookingForm) {
        bookingForm.addEventListener('submit', (e) => {
            e.preventDefault();

            const formData = {
                flight_id: document.getElementById('selected-flight-id').value,
                passenger_name: document.getElementById('passenger-name').value,
                passenger_email: document.getElementById('passenger-email').value,
                passenger_count: document.getElementById('booking-passengers').value
            };

            bookFlight(formData);
        });
    }
    
    // Load cities for the 'from' dropdown if on search page
    if (document.getElementById('from') && window.location.pathname === '/flights') {
        fetch('/api/cities?direction=from')
            .then(response => response.json())
            .then(cities => {
                const fromSelect = document.getElementById('from');
                // Clear existing options except the first one
                fromSelect.innerHTML = '<option value="">Select Departure City</option>';
                cities.forEach(city => {
                    const option = document.createElement('option');
                    option.value = city;
                    option.textContent = city;
                    fromSelect.appendChild(option);
                });
            })
            .catch(error => console.error('Error loading departure cities:', error));
    }

    // Load cities for the 'to' dropdown if on search page
    if (document.getElementById('to') && window.location.pathname === '/flights') {
        fetch('/api/cities?direction=to')
            .then(response => response.json())
            .then(cities => {
                const toSelect = document.getElementById('to');
                // Clear existing options except the first one
                toSelect.innerHTML = '<option value="">Select Destination City</option>';
                cities.forEach(city => {
                    const option = document.createElement('option');
                    option.value = city;
                    option.textContent = city;
                    toSelect.appendChild(option);
                });
            })
            .catch(error => console.error('Error loading destination cities:', error));
    }
});