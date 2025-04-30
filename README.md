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
