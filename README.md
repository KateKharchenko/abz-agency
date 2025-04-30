# ABZ Agency - User Management System

A Laravel-based user management system with image processing, token-based authentication, and a responsive Bootstrap frontend.

## Features

- User listing with server-side pagination
- User creation with image upload and processing
- User profile viewing
- Token-based authentication for user registration
- Form validation with custom error messages
- Image processing with cropping and optimization
- RESTful API with standardized response formats

## Requirements

- PHP 8.1+
- Composer
- MySQL or compatible database
- Node.js and NPM (for frontend assets)
- TinyPNG API key for image optimization

## Installation

### 1. Clone the Repository

```bash
git clone https://github.com/KateKharchenko/abz-agency.git
```

### 2. Navigate to Project Directory

```bash
cd abz-agency
```

### 3. Install Dependencies

```bash
composer install
```

### 4. Configure Environment

```bash
cp .env.example .env
php artisan key:generate
```

Edit the `.env` file to configure your database connection and add your TinyPNG API key:

```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=abz_agency
DB_USERNAME=root
DB_PASSWORD=

TINYPNG_API_KEY=your_tinypng_api_key_here
```

### 5. Run Migrations

```bash
php artisan migrate
```

### 6. Run Seeders

```bash
php artisan db:seed
```

### 7. Create Storage Link

```bash
php artisan storage:link
```

### 8. Start the Development Server

```bash
php artisan serve
```

Visit `http://localhost:8000` in your browser to access the application.

## API Documentation

### Authentication

#### Get Token

```
GET /api/token
```

Response:
```json
{
  "success": true,
  "token": "random40CharacterString"
}
```

### Users

#### List Users

```
GET /api/users?page=1&count=6
```

Response:
```json
{
  "success": true,
  "total_pages": 3,
  "total_users": 15,
  "count": 6,
  "page": 1,
  "links": {
    "next_url": "http://localhost:8000/api/users?page=2",
    "prev_url": null
  },
  "users": [...]
}
```

#### Get User

```
GET /api/users/{id}
```

Response:
```json
{
  "success": true,
  "user": {
    "id": 1,
    "name": "John Doe",
    "email": "john@example.com",
    "phone": "+380957398462",
    "position": "Developer",
    "position_id": 1,
    "photo": "1682512345.jpg"
  }
}
```

#### Create User

```
POST /api/users
Headers: Authorization: Bearer {token}
Content-Type: multipart/form-data
```

Request Body:
```
name: John Doe
email: john@example.com
phone: +380957398462
position_id: 1
password: password123
password_confirmation: password123
photo: [file]
```

Response:
```json
{
  "success": true,
  "user_id": 23,
  "message": "New user successfully registered"
}
```

### Positions

#### List Positions

```
GET /api/positions
```

Response:
```json
{
  "success": true,
  "positions": [
    {
      "id": 1,
      "name": "Developer"
    },
    {
      "id": 2,
      "name": "Designer"
    }
  ]
}
```

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

Laravel is a web application framework with expressive, elegant syntax. We believe development must be an enjoyable and creative experience to be truly fulfilling. Laravel takes the pain out of development by easing common tasks used in many web projects, such as:

- [Simple, fast routing engine](https://laravel.com/docs/routing).
- [Powerful dependency injection container](https://laravel.com/docs/container).
- Multiple back-ends for [session](https://laravel.com/docs/session) and [cache](https://laravel.com/docs/cache) storage.
- Expressive, intuitive [database ORM](https://laravel.com/docs/eloquent).
- Database agnostic [schema migrations](https://laravel.com/docs/migrations).
- [Robust background job processing](https://laravel.com/docs/queues).
- [Real-time event broadcasting](https://laravel.com/docs/broadcasting).

Laravel is accessible, powerful, and provides tools required for large, robust applications.

## Learning Laravel

Laravel has the most extensive and thorough [documentation](https://laravel.com/docs) and video tutorial library of all modern web application frameworks, making it a breeze to get started with the framework.

You may also try the [Laravel Bootcamp](https://bootcamp.laravel.com), where you will be guided through building a modern Laravel application from scratch.

If you don't feel like reading, [Laracasts](https://laracasts.com) can help. Laracasts contains thousands of video tutorials on a range of topics including Laravel, modern PHP, unit testing, and JavaScript. Boost your skills by digging into our comprehensive video library.

## Laravel Sponsors

We would like to extend our thanks to the following sponsors for funding Laravel development. If you are interested in becoming a sponsor, please visit the [Laravel Partners program](https://partners.laravel.com).

### Premium Partners

- **[Vehikl](https://vehikl.com/)**
- **[Tighten Co.](https://tighten.co)**
- **[WebReinvent](https://webreinvent.com/)**
- **[Kirschbaum Development Group](https://kirschbaumdevelopment.com)**
- **[64 Robots](https://64robots.com)**
- **[Curotec](https://www.curotec.com/services/technologies/laravel/)**
- **[Cyber-Duck](https://cyber-duck.co.uk)**
- **[DevSquad](https://devsquad.com/hire-laravel-developers)**
- **[Jump24](https://jump24.co.uk)**
- **[Redberry](https://redberry.international/laravel/)**
- **[Active Logic](https://activelogic.com)**
- **[byte5](https://byte5.de)**
- **[OP.GG](https://op.gg)**

## Contributing

Thank you for considering contributing to the Laravel framework! The contribution guide can be found in the [Laravel documentation](https://laravel.com/docs/contributions).

## Code of Conduct

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
