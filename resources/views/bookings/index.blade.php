@extends('layouts.app')

@section('title', 'My Bookings')

@section('content')
<div class="section-container">
    <h2>My Bookings</h2>
    <div id="my-bookings">
        <p>Loading bookings...</p>
    </div>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Load bookings on page load
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
                                <span class="detail-label">ARRIVAL</span>
                                <span class="detail-value">${formatDate(booking.flight.arrival)}</span>
                            </div>
                            <div class="detail-item">
                                <span class="detail-label">DURATION</span>
                                <span class="detail-value">${formatDuration(booking.flight.departure, booking.flight.arrival)}</span>
                            </div>
                            <div class="detail-item">
                                <span class="detail-label">AIRLINE</span>
                                <span class="detail-value">${booking.flight.airline}</span>
                            </div>
                        </div>
                        
                        <div class="user-details">
                            <h3>Passenger Information</h3>
                            <div class="detail-item">
                                <span class="detail-label">NAME</span>
                                <span class="detail-value">${booking.passenger_name}</span>
                            </div>
                            <div class="detail-item">
                                <span class="detail-label">EMAIL</span>
                                <span class="detail-value">${booking.passenger_email}</span>
                            </div>
                        </div>
                        
                        <div class="booking-summary">
                            <div class="detail-item">
                                <span class="detail-label">PASSENGERS</span>
                                <span class="detail-value">${booking.passenger_count || 1}</span>
                            </div>
                            <div class="detail-item">
                                <span class="detail-label">PRICE PER PERSON</span>
                                <span class="detail-value">$${booking.flight.price}</span>
                            </div>
                            <div class="detail-item">
                                <span class="detail-label">TOTAL PRICE</span>
                                <span class="detail-value">$${booking.total_price || booking.flight.price}</span>
                            </div>
                            <div class="detail-item">
                                <span class="detail-label">BOOKING REFERENCE</span>
                                <span class="detail-value">${booking.booking_reference}</span>
                            </div>
                            <div class="detail-item">
                                <span class="detail-label">STATUS</span>
                                <span class="detail-value">${booking.status.toUpperCase()}</span>
                            </div>
                        </div>
                    </div>
                    
                    ${booking.status !== 'cancelled' ? 
                        `<button class="delete-button" onclick="cancelBooking(${booking.id})">Cancel Booking</button>` : 
                        `<span class="detail-value cancelled-status">CANCELLED</span>`
                    }
                </div>
            `).join('');
        })
        .catch(error => {
            console.error('Error fetching bookings:', error);
            document.getElementById('my-bookings').innerHTML = '<p>Error loading bookings.</p>';
        });
});

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
</script>
@endsection