# 🎬 Movie Review App

A full-featured movie review web application built with PHP/Laravel and integrated with The Movie Database (TMDB) API. Users can search for movies, write detailed reviews with ratings, attach images, and view analytics dashboards.

## ✨ Features

### Core Features
- **User Authentication**: Secure login and registration with Laravel's built-in authentication
- **Movie Search**: Search movies using TMDB API with real-time results
- **Movie Details**: View comprehensive movie information including cast, ratings, and overview
- **Review Management**: Create, edit, and delete movie reviews with ratings (1-10 scale)
- **Review Filtering**: Filter reviews by movie or user
- **Dashboard Analytics**: View personal and overall review statistics with Chart.js visualizations
- **RESTful API**: Complete API endpoints for movies and reviews using Laravel Sanctum

### Bonus Features
- **Image Uploads**: Attach images to reviews (JPEG, PNG, GIF, WebP, max 5MB)
- **Review Analytics**: Interactive charts showing:
  - Reviews over time (last 6 months)
  - Rating distribution
  - User-specific and overall statistics
- **Performance Optimization**:
  - Caching for TMDB API calls
  - Database query optimization with eager loading
  - Rate limiting for movie routes
  - Aggregated database queries for analytics

## 🛠️ Technologies

- **Backend**: PHP 8.2.12, Laravel 12
- **Frontend**: Blade Templates, Tailwind CSS 4, Chart.js 4.4.0
- **Database**: MySQL/PostgreSQL (configurable)
- **Authentication**: Laravel Sanctum 4
- **API Integration**: TMDB (The Movie Database) API
- **Testing**: Pest 3, PHPUnit 11
- **Code Quality**: Laravel Pint 1

## 📋 Requirements

- PHP >= 8.2
- Composer
- Node.js & NPM
- MySQL/PostgreSQL
- TMDB API Key ([Get one here](https://www.themoviedb.org/settings/api))

## 🚀 Installation

### 1. Clone the Repository
```bash
git clone <repository-url>
cd movie-review-app
```

### 2. Install Dependencies
```bash
# Install PHP dependencies
composer install

# Install Node dependencies
npm install
```

### 3. Environment Configuration
```bash
# Copy environment file
cp .env.example .env

# Generate application key
php artisan key:generate
```

### 4. Configure Environment Variables
Edit `.env` file and set the following:

```env
APP_NAME="Movie Review App"
APP_URL=http://localhost:8000

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=your_database_name
DB_USERNAME=your_username
DB_PASSWORD=your_password

TMDB_API_KEY=your_tmdb_api_key
TMDB_API_URL=https://api.themoviedb.org/3
```

### 5. Database Setup
```bash
# Run migrations
php artisan migrate

# (Optional) Seed database with sample data
php artisan db:seed
```

### 6. Storage Setup
```bash
# Create symbolic link for public storage
php artisan storage:link
```

### 7. Build Frontend Assets
```bash
# Build for production
npm run build

# Or run in development mode
npm run dev
```

### 8. Start Development Server
```bash
# Start Laravel development server
php artisan serve

# Or use Laravel Sail (if configured)
./vendor/bin/sail up
```

The application will be available at `http://localhost:8000`

## 📖 Usage

### Web Interface

1. **Register/Login**: Create an account or login with existing credentials
2. **Search Movies**: Use the search functionality to find movies from TMDB
3. **View Movie Details**: Click on any movie to see detailed information
4. **Write Reviews**: Create reviews with ratings and optional images
5. **Manage Reviews**: Edit or delete your reviews from the review list or movie details page
6. **View Dashboard**: Access analytics and statistics on your dashboard

### API Endpoints

#### Public Endpoints (No Authentication)

**Search Movies**
```
GET /api/v1/movies/search?q={query}&page={page}
```

**Get Movie Details**
```
GET /api/v1/movies/{movieId}
```

#### Protected Endpoints (Require Sanctum Token)

**Reviews**
```
GET    /api/v1/reviews              # List all reviews (with filters)
POST   /api/v1/reviews              # Create a review
GET    /api/v1/reviews/{id}         # Get a specific review
PUT    /api/v1/reviews/{id}         # Update a review
DELETE /api/v1/reviews/{id}         # Delete a review
```

**Movie Reviews**
```
GET /api/v1/movies/{movieId}/reviews  # Get all reviews for a movie
```

#### API Authentication

To authenticate API requests, include the Sanctum token in the Authorization header:

```
Authorization: Bearer {your-token}
```

Get your token by logging in via the web interface or using the `/login` endpoint.

## 📁 Project Structure

```
movie-review-app/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Api/              # API controllers
│   │   │   ├── Auth/             # Authentication controllers
│   │   │   └── ...               # Web controllers
│   │   ├── Requests/             # Form request validation
│   │   └── Resources/            # API resources
│   ├── Models/                   # Eloquent models
│   └── Services/                 # Business logic services
│       └── TmdbService.php       # TMDB API integration
├── database/
│   ├── migrations/               # Database migrations
│   └── factories/                # Model factories
├── resources/
│   ├── views/                    # Blade templates
│   ├── css/                      # Stylesheets
│   └── js/                       # JavaScript files
├── routes/
│   ├── web.php                   # Web routes
│   └── api.php                   # API routes
└── storage/
    └── app/
        └── public/
            └── reviews/          # Uploaded review images
```

## 🎯 Key Features Implementation

### TMDB Integration
- Cached API responses to reduce external API calls
- Rate limiting to prevent API abuse
- Error handling for network issues

### Review System
- One review per user per movie
- Image uploads with validation
- Rating system (1-10 scale)
- Full CRUD operations

### Performance Optimizations
- Database aggregation for analytics
- Eager loading to prevent N+1 queries
- Caching for frequently accessed data
- Optimized queries for dashboard statistics

### Security
- Laravel Sanctum for API authentication
- CSRF protection
- Input validation
- File upload validation
- Authorization checks

## 🧪 Testing

Run tests using Pest:

```bash
# Run all tests
php artisan test

# Run specific test file
php artisan test tests/Feature/ExampleTest.php

# Run with coverage
php artisan test --coverage
```

## 📝 Code Quality

The project uses Laravel Pint for code formatting:

```bash
# Format code
vendor/bin/pint

# Format only changed files
vendor/bin/pint --dirty
```

## 🔧 Configuration

### TMDB API
Configure TMDB API settings in `config/services.php`:

```php
'tmdb' => [
    'api_key' => env('TMDB_API_KEY'),
    'api_url' => env('TMDB_API_URL', 'https://api.themoviedb.org/3'),
],
```

### File Storage
Images are stored in `storage/app/public/reviews/`. Ensure the storage link is created:

```bash
php artisan storage:link
```

## 📊 Database Schema

### Reviews Table
- `id` - Primary key
- `user_id` - Foreign key to users
- `movie_id` - TMDB movie ID
- `movie_title` - Movie title (for quick access)
- `review_text` - Review content
- `rating` - Rating (1-10, nullable)
- `image_path` - Path to uploaded image (nullable)
- `created_at`, `updated_at` - Timestamps

## 🚀 Deployment

1. Set production environment variables
2. Run migrations: `php artisan migrate`
3. Create storage link: `php artisan storage:link`
4. Build assets: `npm run build`
5. Optimize: `php artisan config:cache` and `php artisan route:cache`
6. Set proper file permissions for `storage/` and `bootstrap/cache/`

## 📄 License

This project is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

## 👤 Author

Built as a practical assessment for Software Engineer – Full Stack (PHP/Laravel + TMDB API)

## 🙏 Acknowledgments

- [Laravel](https://laravel.com) - The PHP Framework
- [TMDB](https://www.themoviedb.org/) - The Movie Database API
- [Tailwind CSS](https://tailwindcss.com/) - Utility-first CSS framework
- [Chart.js](https://www.chartjs.org/) - Simple yet flexible JavaScript charting library
