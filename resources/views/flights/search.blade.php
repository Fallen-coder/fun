@extends('layouts.app')

@section('title', 'Search Flights')

@section('content')
<div class="section-container">
    <h2>Find Flights</h2>
    <form id="search-form">
        <div class="form-row">
            <div class="form-group">
                <label for="from">From:</label>
                <select id="from" name="from" required>
                    <option value="">Select Departure City</option>
                </select>
            </div>

            <div class="form-group">
                <label for="to">To:</label>
                <select id="to" name="to" required>
                    <option value="">Select Destination City</option>
                </select>
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
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Load cities for the 'from' dropdown
    fetch('/api/cities?direction=from')
        .then(response => response.json())
        .then(cities => {
            const fromSelect = document.getElementById('from');
            cities.forEach(city => {
                const option = document.createElement('option');
                option.value = city;
                option.textContent = city;
                fromSelect.appendChild(option);
            });
        })
        .catch(error => console.error('Error loading departure cities:', error));

    // Load cities for the 'to' dropdown
    fetch('/api/cities?direction=to')
        .then(response => response.json())
        .then(cities => {
            const toSelect = document.getElementById('to');
            cities.forEach(city => {
                const option = document.createElement('option');
                option.value = city;
                option.textContent = city;
                toSelect.appendChild(option);
            });
        })
        .catch(error => console.error('Error loading destination cities:', error));
});
</script>
@endsection