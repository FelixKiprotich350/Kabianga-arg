# Kabianga Annual Research Grants Portal - API

A comprehensive REST API for managing research grants, proposals, and projects at the University of Kabianga.

## Overview

The Kabianga ARG API provides complete backend functionality for research lifecycle management from proposal submission to project completion and reporting. Built with Laravel and Sanctum for secure token-based authentication.

## Key Features

- **Token-based Authentication** - Secure API access with Laravel Sanctum
- **User Management** - Role-based access control with permissions
- **Research Proposals** - Complete proposal lifecycle management
- **Project Tracking** - Monitor active research projects and progress
- **Grant Management** - Manage funding opportunities and allocations
- **Reporting System** - Comprehensive analytics and reports
- **Notification System** - Real-time notifications via API
- **PDF Generation** - Automated document generation for proposals and reports

## API Architecture

### Core Endpoints
- **Authentication** - `/api/v1/auth/*` - Login, register, token management
- **Proposals** - `/api/v1/proposals/*` - Submit, review, approve/reject proposals
- **Projects** - `/api/v1/projects/*` - Track active projects, progress, and funding
- **Users** - `/api/v1/users/*` - Manage researchers, supervisors, and administrators
- **Reports** - `/api/v1/reports/*` - Generate comprehensive reports and insights
- **Dashboard** - `/api/v1/dashboard/*` - Real-time statistics and activity

### Technology Stack
- **Backend**: Laravel 10.x (PHP 8.1+)
- **Database**: MySQL 8.0+
- **Authentication**: Laravel Sanctum (Token-based)
- **PDF Generation**: Snappy/wkhtmltopdf
- **Queue System**: Laravel Queues for background jobs
- **API Documentation**: Scramble

## Quick Start

### Prerequisites
- PHP 8.1 or higher
- Composer
- MySQL 8.0+
- wkhtmltopdf

### Installation

1. **Clone the repository**
   ```bash
   git clone <repository-url>
   cd Kabianga-arg-final
   ```

2. **Install dependencies**
   ```bash
   composer install
   ```

3. **Environment setup**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

4. **Configure database**
   Update `.env` with your database credentials:
   ```
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=kabianga_arg
   DB_USERNAME=your_username
   DB_PASSWORD=your_password
   ```

    composer require --dev kitloong/laravel-migrations-generator
composer require --dev doctrine/dbal
 php artisan migrate:generate
5. **Run migrations and seeders**
   ```bash
   php artisan migrate
   php artisan db:seed
   ```

6. **Start the API server**
   ```bash
   php artisan serve
   ```

## API Documentation

Access the interactive API documentation at `/docs/api` when the server is running.

### Authentication

The API uses Laravel Sanctum for token-based authentication:

1. **Login** to get access token:
   ```
   POST /api/v1/auth/login
   {
     "email": "user@example.com",
     "password": "password"
   }
   ```

2. **Include token** in subsequent requests:
   ```
   Authorization: Bearer {your-token}
   ```

### Key Endpoints

#### Authentication
- `POST /api/v1/auth/login` - User login
- `POST /api/v1/auth/register` - User registration
- `POST /api/v1/auth/logout` - User logout
- `GET /api/v1/auth/me` - Get current user

#### Proposals
- `GET /api/v1/proposals` - List all proposals
- `POST /api/v1/proposals` - Create new proposal
- `GET /api/v1/proposals/{id}` - Get specific proposal
- `POST /api/v1/proposals/{id}/submit` - Submit proposal
- `POST /api/v1/proposals/{id}/approve` - Approve proposal

#### Projects
- `GET /api/v1/projects` - List all projects
- `GET /api/v1/projects/my` - Get user's projects
- `POST /api/v1/projects/{id}/progress` - Submit progress report
- `PATCH /api/v1/projects/{id}/complete` - Mark project complete

#### Reports
- `GET /api/v1/reports/summary` - Get reports summary
- `GET /api/v1/reports/financial` - Financial reports
- `POST /api/v1/reports/export` - Export reports

## Project Structure

```
├── app/
│   ├── Http/Controllers/     # API controllers
│   ├── Models/              # Eloquent models
│   ├── Services/            # Business logic services
│   └── Notifications/       # Notification classes
├── database/
│   ├── migrations/         # Database migrations
│   └── seeders/           # Database seeders
├── routes/
│   ├── api.php           # API routes
│   └── web.php           # Minimal web routes
└── config/               # Configuration files
```

## Authentication Flow

1. Client sends credentials to `/api/v1/auth/login`
2. Server validates and returns access token
3. Client includes token in `Authorization: Bearer {token}` header
4. Server validates token for protected endpoints
5. Tokens expire after 7 days (configurable)

## Error Handling

The API returns consistent JSON error responses:

```json
{
  "message": "Error description",
  "errors": {
    "field": ["Validation error message"]
  },
  "status": 422
}
```

## Rate Limiting

API endpoints are rate-limited to prevent abuse. Default limits:
- Authentication endpoints: 5 requests per minute
- General API endpoints: 60 requests per minute

## Security

- Token-based authentication with Laravel Sanctum
- Role-based access control (RBAC)
- Input validation and sanitization
- SQL injection prevention through Eloquent ORM
- Rate limiting on all endpoints

## Contributing

1. Fork the repository
2. Create a feature branch
3. Make your changes
4. Run tests: `php artisan test`
5. Submit a pull request

## License

This project is licensed under the MIT License.

## Support

For technical support or questions, contact the development team at the University of Kabianga.