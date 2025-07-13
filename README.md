# Task Management API

A comprehensive task management system built with Laravel, featuring role-based access control, RESTful API design, and comprehensive Swagger documentation.

## 🚀 Features

- **User Authentication**: Secure login/register with Laravel Sanctum
- **Role-Based Access Control**: Admin and User roles with different permissions
- **Task Management**: Complete CRUD operations for tasks
- **Task Assignment**: Admins can assign tasks to users
- **Status Tracking**: Track task progress (Pending, In Progress, Completed)
- **Deadline Management**: Set and track task deadlines
- **Email Notifications**: Automatic notifications when tasks are assigned
- **API Documentation**: Interactive Swagger/OpenAPI documentation
- **Postman Collection**: Ready-to-use API testing collection

## 🛠️ Tech Stack

- **Backend**: Laravel 12.x
- **Authentication**: Laravel Sanctum
- **Database**: SQLite (configurable)
- **Documentation**: Swagger/OpenAPI 3.0
- **Email**: Laravel Mail
- **Testing**: PHPUnit
- **API Testing**: Postman

## 📋 Requirements

- PHP 8.2+
- Composer
- SQLite (or MySQL/PostgreSQL)
- Node.js (for frontend, if applicable)

## 🚀 Quick Start

### 1. Clone the Repository
```bash
git clone https://github.com/Cleve-codes/task-management-api.git
cd task-management-api
```

### 2. Backend Setup
```bash
cd backend
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate
php artisan serve
```

### 3. Access the Application
- **API Base URL**: http://127.0.0.1:8000/api
- **Swagger Documentation**: http://127.0.0.1:8000/api/documentation

## 📚 API Documentation

### Authentication Endpoints
- `POST /api/register` - User registration
- `POST /api/login` - User login
- `POST /api/logout` - User logout
- `GET /api/user` - Get current user

### Task Management Endpoints
- `GET /api/tasks` - Get all tasks (role-based filtering)
- `GET /api/my-tasks` - Get user's assigned tasks
- `POST /api/tasks` - Create new task (admin only)
- `GET /api/tasks/{id}` - Get specific task
- `PUT /api/tasks/{id}` - Update task
- `PATCH /api/tasks/{id}/status` - Update task status
- `DELETE /api/tasks/{id}` - Delete task (admin only)

### User Management Endpoints (Admin Only)
- `GET /api/users` - Get all users
- `POST /api/users` - Create new user
- `GET /api/users/{id}` - Get specific user
- `PUT /api/users/{id}` - Update user
- `DELETE /api/users/{id}` - Delete user

## 🧪 Testing

### Using Postman
1. Import the collection: `backend/postman_collection.json`
2. Set environment variable: `base_url = http://127.0.0.1:8000/api`
3. Register/Login to get authentication tokens
4. Test the endpoints

### Using Swagger UI
1. Visit: http://127.0.0.1:8000/api/documentation
2. Click "Authorize" and enter: `Bearer YOUR_TOKEN`
3. Test endpoints directly from the browser

## 🔧 Configuration

### Environment Variables
Key environment variables in `.env`:
```env
APP_NAME="Task Management API"
APP_URL=http://localhost:8000
DB_CONNECTION=sqlite
MAIL_MAILER=log
```

### Database
The application uses SQLite by default. To use MySQL/PostgreSQL:
1. Update `.env` database configuration
2. Run migrations: `php artisan migrate`

## 🏗️ Project Structure

```
task-management-api/
├── backend/
│   ├── app/
│   │   ├── Http/Controllers/     # API Controllers
│   │   ├── Models/              # Eloquent Models
│   │   ├── Policies/            # Authorization Policies
│   │   ├── Http/Requests/       # Form Request Validation
│   │   └── Mail/                # Email Templates
│   ├── database/
│   │   ├── migrations/          # Database Migrations
│   │   └── seeders/             # Database Seeders
│   ├── routes/api.php           # API Routes
│   └── postman_collection.json # Postman Collection
└── frontend/                    # Frontend (if applicable)
```

## 🤝 Contributing

1. Fork the repository
2. Create a feature branch: `git checkout -b feature/amazing-feature`
3. Commit your changes: `git commit -m 'feat: add amazing feature'`
4. Push to the branch: `git push origin feature/amazing-feature`
5. Open a Pull Request

## 📄 License

This project is open-sourced software licensed under the [MIT license](LICENSE).

## 👨‍💻 Author

**Cleve Momanyi**
- Email: clevemomanyi@gmail.com
- GitHub: [@yourusername](https://github.com/yourusername)

## 🙏 Acknowledgments

- Laravel Framework
- Laravel Sanctum for authentication
- Swagger/OpenAPI for documentation
- All contributors and testers
