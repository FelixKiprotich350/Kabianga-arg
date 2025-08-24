# Kabianga Annual Research Grants Portal

A comprehensive web-based portal for managing research grants, proposals, and projects at the University of Kabianga.

## Overview

The Kabianga ARG Portal streamlines the entire research lifecycle from proposal submission to project completion and reporting. Built with Laravel, it provides a robust platform for researchers, administrators, and supervisors to collaborate effectively.

## Key Features

- **User Management** - Role-based access control with permissions
- **Research Proposals** - Complete proposal lifecycle management
- **Project Tracking** - Monitor active research projects and progress
- **Grant Management** - Manage funding opportunities and allocations
- **Reporting System** - Comprehensive analytics and reports
- **Notification System** - Real-time in-app and email notifications
- **PDF Generation** - Automated document generation for proposals and reports

## System Architecture

### Core Modules
- **Authentication & Authorization** - Secure login with role-based permissions
- **Dashboard** - Real-time statistics and activity monitoring
- **Proposals Management** - Submit, review, approve/reject research proposals
- **Projects Management** - Track active projects, progress, and funding
- **User Management** - Manage researchers, supervisors, and administrators
- **Reports & Analytics** - Generate comprehensive reports and insights

### Technology Stack
- **Backend**: Laravel 10.x (PHP 8.1+)
- **Database**: MySQL 8.0+
- **Frontend**: Blade templates with Bootstrap
- **PDF Generation**: Snappy/wkhtmltopdf
- **Queue System**: Laravel Queues for background jobs
- **Notifications**: Laravel Notifications (Email + In-app)

## Quick Start

### Prerequisites
- PHP 8.1 or higher
- Composer
- MySQL 8.0+
- Node.js & NPM
- wkhtmltopdf

### Installation

1. **Clone the repository**
   ```bash
   git clone <repository-url>
   cd Kabianga-arg
   ```

2. **Install dependencies**
   ```bash
   composer install
   npm install
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

5. **Run migrations and seeders**
   ```bash
   php artisan migrate
   php artisan db:seed
   ```

6. **Build assets**
   ```bash
   npm run build
   ```

7. **Start the application**
   ```bash
   php artisan serve
   ```

## Project Structure

```
├── app/
│   ├── Http/Controllers/     # Application controllers
│   ├── Models/              # Eloquent models
│   ├── Services/            # Business logic services
│   ├── Notifications/       # Email notification classes
│   └── Traits/             # Reusable traits
├── database/
│   ├── migrations/         # Database migrations
│   └── seeders/           # Database seeders
├── resources/
│   ├── views/             # Blade templates
│   └── js/               # Frontend JavaScript
├── routes/
│   ├── web.php           # Web routes
│   └── api.php           # API routes
└── public/               # Public assets
```

## API Documentation

The system provides a comprehensive REST API. See [API_README.md](API_README.md) for detailed documentation.

## Notification System

Advanced notification system with dual delivery (email + in-app). See [NOTIFICATIONS_README.md](NOTIFICATIONS_README.md) for configuration and usage.

## Contributing

1. Fork the repository
2. Create a feature branch
3. Make your changes
4. Run tests: `php artisan test`
5. Submit a pull request

## Security

- All routes are protected with authentication middleware
- Role-based access control (RBAC) implementation
- Input validation and sanitization
- CSRF protection enabled
- SQL injection prevention through Eloquent ORM

## License

This project is licensed under the MIT License.

## Support

For technical support or questions, contact the development team at the University of Kabianga.