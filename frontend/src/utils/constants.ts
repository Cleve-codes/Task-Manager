// API Configuration
export const API_BASE_URL = import.meta.env.VITE_API_BASE_URL || 'https://task-manager-api-nwrn.onrender.com/api'

// API Endpoints
export const API_ENDPOINTS = {
  // Authentication
  LOGIN: '/login',
  REGISTER: '/register',
  LOGOUT: '/logout',
  USER: '/user',
  
  // Tasks
  TASKS: '/tasks',
  MY_TASKS: '/my-tasks',
  TASK_STATUS: (id: number) => `/tasks/${id}/status`,
  
  // Users (Admin only)
  USERS: '/users',
  USER_BY_ID: (id: number) => `/users/${id}`,

  // Email Preferences
  EMAIL_PREFERENCES: '/email-preferences',
  USER_EMAIL_PREFERENCES: (id: number) => `/users/${id}/email-preferences`,
  EMAIL_PREFERENCES_OVERVIEW: '/admin/email-preferences/overview',
} as const

// User Roles
export const USER_ROLES = {
  ADMIN: 'admin',
  USER: 'user',
} as const

// Task Status
export const TASK_STATUS = {
  PENDING: 'Pending',
  IN_PROGRESS: 'In Progress',
  COMPLETED: 'Completed',
} as const

// Task Status Colors
export const TASK_STATUS_COLORS = {
  [TASK_STATUS.PENDING]: 'orange',
  [TASK_STATUS.IN_PROGRESS]: 'blue',
  [TASK_STATUS.COMPLETED]: 'green',
} as const

// Task Status Icons
export const TASK_STATUS_ICONS = {
  [TASK_STATUS.PENDING]: 'mdi-clock-outline',
  [TASK_STATUS.IN_PROGRESS]: 'mdi-progress-clock',
  [TASK_STATUS.COMPLETED]: 'mdi-check-circle',
} as const

// Local Storage Keys
export const STORAGE_KEYS = {
  TOKEN: 'auth_token',
  USER: 'auth_user',
  THEME: 'app_theme',
} as const

// Route Names
export const ROUTE_NAMES = {
  // Auth
  LOGIN: 'login',
  REGISTER: 'register',

  // Admin
  ADMIN_DASHBOARD: 'admin-dashboard',
  ADMIN_USERS: 'admin-users',
  ADMIN_TASKS: 'admin-tasks',
  ADMIN_EMAIL_PREFERENCES: 'admin-email-preferences',

  // User
  USER_DASHBOARD: 'user-dashboard',

  // Shared
  PROFILE: 'profile',
} as const

// Navigation Items
export const ADMIN_NAV_ITEMS = [
  {
    title: 'Dashboard',
    icon: 'mdi-view-dashboard',
    to: { name: ROUTE_NAMES.ADMIN_DASHBOARD },
  },
  {
    title: 'Users',
    icon: 'mdi-account-group',
    to: { name: ROUTE_NAMES.ADMIN_USERS },
  },
  {
    title: 'Tasks',
    icon: 'mdi-format-list-checks',
    to: { name: ROUTE_NAMES.ADMIN_TASKS },
  },
  {
    title: 'Email Preferences',
    icon: 'mdi-email-outline',
    to: { name: ROUTE_NAMES.ADMIN_EMAIL_PREFERENCES },
  },
  {
    title: 'Profile',
    icon: 'mdi-account-circle',
    to: { name: ROUTE_NAMES.PROFILE },
  },
] as const

export const USER_NAV_ITEMS = [
  {
    title: 'My Tasks',
    icon: 'mdi-format-list-checks',
    to: { name: ROUTE_NAMES.USER_DASHBOARD },
  },
  {
    title: 'Profile',
    icon: 'mdi-account-circle',
    to: { name: ROUTE_NAMES.PROFILE },
  },
] as const

// Form Validation Rules
export const VALIDATION_RULES = {
  REQUIRED: (value: any) => !!value || 'This field is required',
  EMAIL: (value: string) => {
    const pattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/
    return pattern.test(value) || 'Please enter a valid email address'
  },
  MIN_LENGTH: (min: number) => (value: string) => 
    value.length >= min || `Must be at least ${min} characters`,
  MAX_LENGTH: (max: number) => (value: string) => 
    value.length <= max || `Must be no more than ${max} characters`,
} as const

// Date Formats
export const DATE_FORMATS = {
  DISPLAY: 'MMM DD, YYYY',
  DISPLAY_WITH_TIME: 'MMM DD, YYYY HH:mm',
  ISO: 'YYYY-MM-DDTHH:mm:ss',
  INPUT: 'YYYY-MM-DD',
} as const

// HTTP Status Codes
export const HTTP_STATUS = {
  OK: 200,
  CREATED: 201,
  BAD_REQUEST: 400,
  UNAUTHORIZED: 401,
  FORBIDDEN: 403,
  NOT_FOUND: 404,
  UNPROCESSABLE_ENTITY: 422,
  INTERNAL_SERVER_ERROR: 500,
} as const
