# 🚀 Task Management System - Setup Guide

Complete setup instructions for evaluators and developers.

## 📋 Prerequisites

Before starting, ensure you have the following installed:

- **PHP 8.2+** with extensions: PDO, OpenSSL, Mbstring, Tokenizer, XML, Ctype, JSON
- **Composer 2.x** - PHP dependency manager
- **Node.js 18+** and npm - JavaScript runtime and package manager
- **Git** - Version control system

## ⚡ Quick Start (5 minutes)

### 1. Clone the Repository
```bash
git clone https://github.com/Cleve-Codes/task-management.git
cd task-management
```

### 2. Backend Setup
```bash
cd backend

# Install PHP dependencies
composer install

# Setup environment
cp .env.example .env
php artisan key:generate

# Setup database
php artisan migrate
php artisan db:seed

# Start backend server
php artisan serve
```
Backend will be available at: http://localhost:8000

### 3. Frontend Setup
```bash
cd ../frontend

# Install Node.js dependencies
npm install

# Setup environment
cp .env.example .env.local

# Start frontend server
npm run dev
```
Frontend will be available at: http://localhost:3000

### 4. Access the Application
- **Frontend**: http://localhost:3000
- **Backend API**: http://localhost:8000/api
- **API Documentation**: http://localhost:8000/api/documentation

### 5. Default Login Credentials
```
Admin User:
Email: admin@example.com
Password: password

Regular User:
Email: user@example.com  
Password: password
```

## 🔧 Detailed Configuration

### Database Configuration

**SQLite (Default - Recommended for evaluation):**
```bash
# Already configured in .env.example
DB_CONNECTION=sqlite
DB_DATABASE=database/database.sqlite
```

**PostgreSQL (Production):**
```bash
DB_CONNECTION=pgsql
DB_HOST=your-host
DB_PORT=5432
DB_DATABASE=your-database
DB_USERNAME=your-username
DB_PASSWORD=your-password
```

### Email Configuration (Optional)

**For testing email notifications:**
```bash
# Get free Mailgun account at https://www.mailgun.com/
MAIL_MAILER=mailgun
MAILGUN_DOMAIN=your-domain.com
MAILGUN_SECRET=your-secret-key
MAIL_FROM_ADDRESS=noreply@your-domain.com
```

**For local testing without emails:**
```bash
MAIL_MAILER=log
# Emails will be logged to storage/logs/laravel.log
```

## 🧪 Testing the Application

### 1. Authentication
- Register a new user or use default credentials
- Test login/logout functionality
- Verify role-based access (admin vs user)

### 2. Task Management
- Create, edit, and delete tasks
- Assign tasks to users (admin only)
- Update task status
- Test deadline functionality

### 3. Email Notifications
- Update email preferences
- Test task assignment notifications
- Test task reminder system

### 4. API Testing
- Visit http://localhost:8000/api/documentation for Swagger UI
- Test API endpoints with provided examples
- Verify authentication with Bearer tokens

## 🚨 Troubleshooting

### Common Issues

**"Class not found" errors:**
```bash
cd backend
composer dump-autoload
```

**Database connection errors:**
```bash
# Ensure SQLite file exists
touch database/database.sqlite
php artisan migrate
```

**Frontend build errors:**
```bash
cd frontend
rm -rf node_modules package-lock.json
npm install
```

**Permission errors (Linux/Mac):**
```bash
cd backend
chmod -R 775 storage bootstrap/cache
```

### Reset Everything
```bash
# Backend reset
cd backend
php artisan migrate:fresh --seed
php artisan cache:clear
php artisan config:clear

# Frontend reset
cd ../frontend
rm -rf node_modules
npm install
```

## 📊 Evaluation Checklist

- [ ] Backend API running on http://localhost:8000
- [ ] Frontend app running on http://localhost:3000
- [ ] Can login with default credentials
- [ ] Can create and manage tasks
- [ ] Role-based access working (admin vs user)
- [ ] Email preferences can be updated
- [ ] API documentation accessible
- [ ] Responsive design on mobile/desktop

## 🎯 Key Features to Test

1. **Authentication System**
   - User registration and login
   - Role-based access control
   - Profile management

2. **Task Management**
   - CRUD operations for tasks
   - Task assignment (admin only)
   - Status updates and deadlines
   - Task filtering and search

3. **Email System**
   - User notification preferences
   - Admin email overview
   - Task assignment notifications

4. **User Interface**
   - Responsive design
   - Dark/light theme toggle
   - Modern Material Design

## 📞 Support

If you encounter any issues during setup:
1. Check the troubleshooting section above
2. Verify all prerequisites are installed
3. Ensure ports 8000 and 3000 are available
4. Check the logs in `backend/storage/logs/laravel.log`
