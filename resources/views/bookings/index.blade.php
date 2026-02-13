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
                        `<span class="detail-value" style="color: #8a7a63;">CANCELLED</span>`
                    }
                </div>
            `).join('');
        })
        .catch(error => {
            console.error('Error fetching bookings:', error);
            document.getElementById('my-bookings').innerHTML = '<p>Error loading bookings.</p>';
        });
});
</script>
@endsection