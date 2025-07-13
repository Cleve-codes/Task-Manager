# Task Management System

A full-stack task management application with Laravel backend and Vue.js frontend, featuring comprehensive email notifications, user preference management, role-based access control, and modern responsive design.

## 🚀 Features

### 🔐 Authentication & Authorization
- **Secure Authentication**: Laravel Sanctum with JWT tokens
- **Role-Based Access Control**: Admin and User roles with granular permissions
- **User Registration**: Welcome email notifications for new users
- **Profile Management**: Users can update their personal information

### 📋 Task Management
- **Complete CRUD Operations**: Create, read, update, and delete tasks
- **Task Assignment**: Admins can assign tasks to specific users
- **Status Tracking**: Track progress (Pending, In Progress, Completed)
- **Deadline Management**: Set and monitor task deadlines with reminders
- **My Tasks View**: Users see only their assigned tasks
- **Admin Dashboard**: Comprehensive overview of all tasks and users

### 📧 Email Notification System
- **Task Assignment Notifications**: Automatic emails when tasks are assigned
- **Task Update Notifications**: Alerts when tasks are modified
- **Task Reminder Notifications**: Automated deadline reminders
- **Welcome Emails**: Onboarding emails for new users
- **Email Preferences Management**: Users control which notifications they receive
- **Mailgun Integration**: Professional email delivery with tracking

### 🎛️ Email Preferences Management
- **User Preference Controls**: Toggle switches for each notification type
- **Admin Preference Management**: Admins can manage all users' preferences
- **Preference Statistics**: Dashboard showing adoption rates
- **Real-time Updates**: Instant saving of preference changes
- **Default Settings**: Sensible defaults for new users

### 🖥️ Modern Frontend (Vue.js 3)
- **Responsive Design**: Mobile-first approach with Vuetify components
- **Dark/Light Theme**: User-selectable themes with persistence
- **Real-time Updates**: Reactive UI with Pinia state management
- **Form Validation**: Comprehensive validation with VeeValidate
- **Toast Notifications**: User feedback with vue-toastification
- **TypeScript Support**: Full type safety throughout the application

## 🛠️ Tech Stack

### Backend
- **Framework**: Laravel 11.x
- **Authentication**: Laravel Sanctum with API tokens
- **Database**: SQLite (configurable to MySQL/PostgreSQL)
- **Email Service**: Mailgun with Laravel Notifications
- **Queue System**: Database queues for email processing
- **API Documentation**: Swagger/OpenAPI 3.0
- **Testing**: PHPUnit

### Frontend
- **Framework**: Vue.js 3 with Composition API
- **UI Library**: Vuetify 3 (Material Design)
- **State Management**: Pinia
- **Routing**: Vue Router 4
- **HTTP Client**: Axios
- **Form Validation**: VeeValidate
- **Notifications**: Vue Toastification
- **Build Tool**: Vite
- **Language**: TypeScript

### DevOps & Deployment
- **Frontend Hosting**: Vercel (configured)
- **Email Service**: Mailgun
- **Version Control**: Git with conventional commits
- **Package Management**: Composer (PHP), npm (Node.js)

## 📋 Requirements

### Backend Requirements
- PHP 8.2+
- Composer 2.x
- SQLite (or MySQL/PostgreSQL)
- PHP Extensions: PDO, OpenSSL, Mbstring, Tokenizer, XML, Ctype, JSON

### Frontend Requirements
- Node.js 18+
- npm or yarn
- Modern web browser with ES6+ support

### Email Service (Optional but Recommended)
- Mailgun account for production email delivery
- Custom domain for professional email sending

## 🚀 Quick Start

### 1. Clone the Repository
```bash
git clone https://github.com/Cleve-codes/Task-Manager.git
cd Task-Manager
```

### 2. Backend Setup
```bash
cd backend
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate
php artisan db:seed
php artisan serve
```

### 3. Frontend Setup
```bash
cd frontend
npm install
cp .env.example .env.local
npm run dev
```

### 4. Email Configuration (Optional)
```bash
# Update backend/.env with your Mailgun credentials
MAIL_MAILER=mailgun
MAILGUN_DOMAIN=your-domain.com
MAILGUN_SECRET=your-mailgun-secret
MAIL_FROM_ADDRESS=noreply@your-domain.com
```

### 5. Access the Application
- **Frontend**: http://localhost:5173
- **Backend API**: http://127.0.0.1:8000/api
- **Swagger Documentation**: http://127.0.0.1:8000/api/documentation

### 6. Default Login Credentials
```
Admin User:
Email: admin@example.com
Password: password

Regular User:
Email: clevemomanyi@gmail.com
Password: password
```

## 📚 API Documentation

### Authentication Endpoints
- `POST /api/register` - User registration with welcome email
- `POST /api/login` - User login with token generation
- `POST /api/logout` - User logout and token revocation
- `GET /api/user` - Get current authenticated user

### Task Management Endpoints
- `GET /api/tasks` - Get all tasks (role-based filtering)
- `GET /api/my-tasks` - Get user's assigned tasks
- `POST /api/tasks` - Create new task (admin only) with email notification
- `GET /api/tasks/{id}` - Get specific task details
- `PUT /api/tasks/{id}` - Update task with change notifications
- `PATCH /api/tasks/{id}/status` - Update task status
- `DELETE /api/tasks/{id}` - Delete task (admin only)

### User Management Endpoints (Admin Only)
- `GET /api/users` - Get all users with pagination
- `POST /api/users` - Create new user
- `GET /api/users/{id}` - Get specific user details
- `PUT /api/users/{id}` - Update user information
- `DELETE /api/users/{id}` - Delete user account

### Email Preferences Endpoints
- `GET /api/email-preferences` - Get current user's email preferences
- `PUT /api/email-preferences` - Update current user's email preferences
- `GET /api/users/{id}/email-preferences` - Get user's preferences (admin only)
- `PUT /api/users/{id}/email-preferences` - Update user's preferences (admin only)
- `GET /api/admin/email-preferences/overview` - Get preferences overview with statistics (admin only)

## 🧪 Testing

### Frontend Testing
```bash
cd frontend
npm run test        # Run unit tests
npm run test:e2e    # Run end-to-end tests
npm run lint        # Run ESLint
npm run type-check  # TypeScript type checking
```

### Backend Testing
```bash
cd backend
php artisan test                    # Run PHPUnit tests
php artisan test --coverage        # Run tests with coverage
vendor/bin/phpstan analyse         # Static analysis
```

### API Testing with Postman
1. Import the collection: `backend/postman_collection.json`
2. Set environment variable: `base_url = http://127.0.0.1:8000/api`
3. Register/Login to get authentication tokens
4. Test all endpoints including email preferences

### API Testing with Swagger UI
1. Visit: http://127.0.0.1:8000/api/documentation
2. Click "Authorize" and enter: `Bearer YOUR_TOKEN`
3. Test endpoints directly from the browser
4. Explore the new email preferences endpoints

### Email Testing
```bash
cd backend
php artisan queue:work              # Process email queue
php artisan tinker                  # Test email notifications manually
```

## 🔧 Configuration

### Backend Environment Variables
Key environment variables in `backend/.env`:
```env
APP_NAME="Task Management System"
APP_URL=http://localhost:8000
DB_CONNECTION=sqlite

# Email Configuration
MAIL_MAILER=mailgun
MAILGUN_DOMAIN=your-domain.com
MAILGUN_SECRET=your-mailgun-secret
MAILGUN_ENDPOINT=api.mailgun.net
MAIL_FROM_ADDRESS=noreply@your-domain.com
MAIL_FROM_NAME="Task Management System"

# Queue Configuration
QUEUE_CONNECTION=database

# Frontend URL (for email links)
FRONTEND_URL=http://localhost:5173
```

### Frontend Environment Variables
Key environment variables in `frontend/.env.local`:
```env
VITE_API_BASE_URL=http://127.0.0.1:8000/api
VITE_APP_NAME="Task Management System"
```

### Database Configuration
The application uses SQLite by default. To use MySQL/PostgreSQL:
1. Update `backend/.env` database configuration
2. Run migrations: `php artisan migrate`
3. Seed the database: `php artisan db:seed`

### Email Service Setup
1. **Create Mailgun Account**: Sign up at mailgun.com
2. **Add Domain**: Add your domain to Mailgun
3. **Configure DNS**: Set up SPF, DKIM, and DMARC records
4. **Update Environment**: Add Mailgun credentials to `.env`
5. **Test Email**: Use the email preferences system to test

## 🏗️ Project Structure

```
task-management/
├── backend/                     # Laravel API Backend
│   ├── app/
│   │   ├── Console/Commands/    # Artisan Commands (Task Reminders)
│   │   ├── Events/              # Laravel Events (Task Events)
│   │   ├── Http/
│   │   │   ├── Controllers/     # API Controllers
│   │   │   └── Requests/        # Form Request Validation
│   │   ├── Models/              # Eloquent Models
│   │   ├── Notifications/       # Email Notifications
│   │   └── Policies/            # Authorization Policies
│   ├── database/
│   │   ├── migrations/          # Database Migrations
│   │   └── seeders/             # Database Seeders
│   ├── resources/views/emails/  # Email Templates
│   ├── routes/api.php           # API Routes
│   └── config/                  # Configuration Files
├── frontend/                    # Vue.js 3 Frontend
│   ├── src/
│   │   ├── components/          # Vue Components
│   │   │   ├── admin/           # Admin-specific Components
│   │   │   ├── auth/            # Authentication Components
│   │   │   ├── common/          # Shared Components
│   │   │   └── user/            # User-specific Components
│   │   ├── views/               # Page Components
│   │   │   ├── Admin/           # Admin Pages
│   │   │   ├── Auth/            # Authentication Pages
│   │   │   └── User/            # User Pages
│   │   ├── stores/              # Pinia State Management
│   │   ├── services/            # API Services
│   │   ├── router/              # Vue Router Configuration
│   │   └── utils/               # Utility Functions
│   ├── public/                  # Static Assets
│   ├── vercel.json              # Vercel Deployment Config
│   └── package.json             # Frontend Dependencies
├── .gitignore                   # Git Ignore Rules
└── README.md                    # Project Documentation
```

## 🚀 Deployment

### Frontend Deployment (Vercel)
```bash
cd frontend
npm install -g vercel
vercel
```

Follow the prompts to deploy to Vercel. The `vercel.json` configuration is already set up.

### Backend Deployment
The Laravel backend can be deployed to various platforms:
- **Shared Hosting**: Upload files and configure virtual host
- **VPS/Cloud**: Use services like DigitalOcean, AWS, or Linode
- **Platform as a Service**: Deploy to Heroku, Railway, or similar

### Email Domain Setup
1. Deploy frontend to get your Vercel domain (e.g., `your-app.vercel.app`)
2. Add the domain to Mailgun
3. Configure DNS records for email authentication
4. Update backend `.env` with your domain

## 🎯 Key Features Showcase

### Email Preferences Management
- **User Control**: Users can toggle email notifications on/off
- **Admin Overview**: Admins see adoption rates and can manage all users
- **Real-time Updates**: Changes save instantly with visual feedback
- **Professional Templates**: Mobile-responsive email designs

### Task Management
- **Intuitive Interface**: Clean, modern design with Vuetify components
- **Role-based Views**: Different interfaces for admins and users
- **Status Tracking**: Visual indicators for task progress
- **Deadline Management**: Color-coded deadlines with reminder system

### Responsive Design
- **Mobile-first**: Optimized for all screen sizes
- **Dark/Light Themes**: User preference with system detection
- **Accessibility**: WCAG compliant with keyboard navigation
- **Performance**: Optimized loading with lazy components

## 🔧 Troubleshooting

### Common Issues

#### Email Not Sending
```bash
# Check queue status
php artisan queue:work --once

# Check failed jobs
php artisan queue:failed

# Clear failed jobs
php artisan queue:flush
```

#### Frontend Build Issues
```bash
# Clear node modules and reinstall
rm -rf node_modules package-lock.json
npm install

# Check for TypeScript errors
npm run type-check
```

#### Database Issues
```bash
# Reset database
php artisan migrate:fresh --seed

# Check database connection
php artisan tinker
>>> DB::connection()->getPdo()
```

### Performance Optimization
- **Backend**: Use Redis for caching and queues in production
- **Frontend**: Enable gzip compression and CDN
- **Database**: Add indexes for frequently queried columns
- **Email**: Use queue workers for background email processing

## 🤝 Contributing

1. Fork the repository
2. Create a feature branch: `git checkout -b feature/amazing-feature`
3. Commit your changes: `git commit -m 'feat: add amazing feature'`
4. Push to the branch: `git push origin feature/amazing-feature`
5. Open a Pull Request

### Development Guidelines
- Follow conventional commit messages
- Add tests for new features
- Update documentation for API changes
- Ensure TypeScript types are properly defined

## 📄 License

This project is open-sourced software licensed under the [MIT license](LICENSE).

## � Screenshots & Demo

### Dashboard Views
- **User Dashboard**: Clean interface showing assigned tasks
- **Admin Dashboard**: Comprehensive overview with statistics
- **Task Management**: Intuitive task creation and editing
- **Email Preferences**: User-friendly preference controls

### Responsive Design
- **Mobile Optimized**: Full functionality on all devices
- **Dark/Light Themes**: Seamless theme switching
- **Accessibility**: Keyboard navigation and screen reader support

### Live Demo
- **Frontend Demo**: [Coming Soon - Deploy to Vercel]
- **API Documentation**: Available after backend deployment
- **Test Credentials**: Use the default credentials provided above

## 🔄 Version History

### v2.0.0 (Current)
- ✅ Complete Vue.js 3 frontend application
- ✅ Email preferences management system
- ✅ Comprehensive notification system
- ✅ Modern responsive design
- ✅ TypeScript support
- ✅ Vercel deployment configuration

### v1.0.0
- ✅ Laravel API backend
- ✅ Basic task management
- ✅ User authentication
- ✅ Role-based access control
- ✅ Swagger documentation

## 🎯 Roadmap

### Upcoming Features
- [ ] Real-time notifications with WebSockets
- [ ] File attachments for tasks
- [ ] Task comments and collaboration
- [ ] Advanced reporting and analytics
- [ ] Mobile app (React Native)
- [ ] Integration with calendar applications
- [ ] Bulk task operations
- [ ] Custom task templates

### Performance Improvements
- [ ] Redis caching implementation
- [ ] Database query optimization
- [ ] CDN integration for static assets
- [ ] Progressive Web App (PWA) features

## �👨‍💻 Author

**Cleve Momanyi**
- Email: clevemomanyi@gmail.com
- GitHub: [@Cleve-codes](https://github.com/Cleve-codes)
- LinkedIn: [Connect with me](https://linkedin.com/in/cleve-momanyi)

## 🙏 Acknowledgments

### Backend Technologies
- **Laravel Framework** - Robust PHP framework
- **Laravel Sanctum** - API authentication
- **Mailgun** - Email delivery service
- **Swagger/OpenAPI** - API documentation

### Frontend Technologies
- **Vue.js 3** - Progressive JavaScript framework
- **Vuetify 3** - Material Design component library
- **Pinia** - State management
- **Vite** - Build tool and development server
- **TypeScript** - Type safety

### Development Tools
- **Vercel** - Frontend hosting and deployment
- **GitHub** - Version control and collaboration
- **Conventional Commits** - Commit message standards

### Special Thanks
- All contributors and testers
- The open-source community
- Laravel and Vue.js communities
