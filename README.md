# Flight Booking Application

A Laravel-based flight booking application with a modern UI and comprehensive functionality.

## Features

- Search flights by departure city, destination, date, and price
- Book flights with passenger information
- View and manage your bookings
- Responsive design with a blue-ish dirty turquoise color scheme
- Clean, intuitive user interface

## Technologies Used

- Laravel 10.x
- PHP 8.2+
- MySQL
- HTML, CSS, JavaScript
- Bootstrap (CSS framework)

## Installation

1. Clone the repository:
```bash
git clone https://github.com/username/flight-booking-app.git
```

2. Navigate to the project directory:
```bash
cd flight-booking-app
```

3. Install dependencies:
```bash
composer install
```

4. Copy the environment file and set your configurations:
```bash
cp .env.example .env
```

5. Generate application key:
```bash
php artisan key:generate
```

6. Set up your database configurations in the `.env` file

7. Run migrations and seed the database:
```bash
php artisan migrate --seed
```

8. Start the development server:
```bash
php artisan serve
```

## Running Tests

To run the unit tests:
```bash
php artisan test --testsuite=Unit
```

## Project Structure

- `app/` - Application logic (models, controllers, etc.)
- `resources/views/` - Blade templates
- `public/` - Public assets (CSS, JS, images)
- `routes/` - Route definitions
- `tests/` - Test files
- `.github/workflows/` - CI/CD workflows

## CI/CD Pipeline

The project includes a GitHub Actions workflow that:
- Sets up PHP environment
- Installs dependencies
- Runs database migrations
- Executes unit tests

## Contributing

1. Fork the repository
2. Create a feature branch (`git checkout -b feature/amazing-feature`)
3. Commit your changes (`git commit -m 'Add some amazing feature'`)
4. Push to the branch (`git push origin feature/amazing-feature`)
5. Open a Pull Request

## License

This project is open source and available under the [MIT License](LICENSE).