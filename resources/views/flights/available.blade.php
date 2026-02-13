@extends('layouts.app')

@section('title', 'Available Flights')

@section('content')
<div class="section-container">
    <h2>Available Flights</h2>
    <div id="flight-results">
        <p>No flights found. Search for flights to get started.</p>
    </div>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Load available flights on page load
    // This would typically load from a saved search or show all available flights
    // For now, we'll show a message to search first
    document.getElementById('flight-results').innerHTML = '<p>Please search for flights first. <a href="{{ route("flights.index") }}">Go to Search</a></p>';
});
</script>
@endsection