# Task Management System - Frontend

Vue.js 3 frontend application for the Task Management System with TypeScript, Vuetify, and modern development tools.

## 🚀 Features

- **Vue.js 3** with Composition API
- **TypeScript** for type safety
- **Vuetify 3** for Material Design components
- **Pinia** for state management
- **Vue Router** for navigation
- **VeeValidate** for form validation
- **Axios** for API communication
- **Dark/Light Theme** support
- **Responsive Design** for all devices

## 📋 Prerequisites

- Node.js 18+ and npm
- Backend API running on http://localhost:8000

## 🔧 Installation

```bash
# Install dependencies
npm install

# Copy environment file
cp .env.example .env.local

# Update .env.local with your API URL if different
# VITE_API_BASE_URL=http://localhost:8000/api
```

## 🏃‍♂️ Development

```bash
# Start development server
npm run dev

# The app will be available at http://localhost:3000
```

## 🏗️ Build

```bash
# Type check
npm run type-check

# Build for production
npm run build

# Preview production build
npm run preview
```

## 🧹 Code Quality

```bash
# Lint and fix code
npm run lint
```

## 📁 Project Structure

```
src/
├── components/          # Reusable Vue components
│   ├── admin/          # Admin-specific components
│   ├── auth/           # Authentication components
│   ├── common/         # Shared components
│   └── user/           # User-specific components
├── views/              # Page components
├── stores/             # Pinia state management
├── services/           # API services
├── router/             # Vue Router configuration
├── utils/              # Utility functions
└── types/              # TypeScript type definitions
```

## 🌐 Deployment

The app is configured for Vercel deployment with automatic builds from the main branch.

## 🔧 Configuration

Environment variables in `.env.local`:
- `VITE_API_BASE_URL`: Backend API URL
- `VITE_APP_NAME`: Application name
- `VITE_APP_VERSION`: Application version
