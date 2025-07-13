import { createRouter, createWebHistory, type RouteLocationNormalized } from 'vue-router'
import { useAuthStore } from '@/stores/auth'
import { ROUTE_NAMES, USER_ROLES } from '@/utils/constants'

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [
    // Redirect root to appropriate dashboard
    {
      path: '/',
      redirect: () => {
        const authStore = useAuthStore()
        if (!authStore.isAuthenticated) {
          return { name: ROUTE_NAMES.LOGIN }
        }
        return authStore.isAdmin
          ? { name: ROUTE_NAMES.ADMIN_DASHBOARD }
          : { name: ROUTE_NAMES.USER_DASHBOARD }
      },
    },

    // Authentication Routes
    {
      path: '/login',
      name: ROUTE_NAMES.LOGIN,
      component: () => import('@/views/Auth/Login.vue'),
      meta: {
        requiresGuest: true,
        title: 'Login'
      },
    },
    {
      path: '/register',
      name: ROUTE_NAMES.REGISTER,
      component: () => import('@/views/Auth/Register.vue'),
      meta: {
        requiresGuest: true,
        title: 'Register'
      },
    },

    // Admin Routes
    {
      path: '/admin',
      meta: {
        requiresAuth: true,
        requiresRole: USER_ROLES.ADMIN
      },
      children: [
        {
          path: '',
          redirect: { name: ROUTE_NAMES.ADMIN_DASHBOARD },
        },
        {
          path: 'dashboard',
          name: ROUTE_NAMES.ADMIN_DASHBOARD,
          component: () => import('@/views/Admin/Dashboard.vue'),
          meta: {
            title: 'Admin Dashboard'
          },
        },
        {
          path: 'users',
          name: ROUTE_NAMES.ADMIN_USERS,
          component: () => import('@/views/Admin/Users.vue'),
          meta: {
            title: 'User Management'
          },
        },
        {
          path: 'tasks',
          name: ROUTE_NAMES.ADMIN_TASKS,
          component: () => import('@/views/Admin/Tasks.vue'),
          meta: {
            title: 'Task Management'
          },
        },
        {
          path: 'email-preferences',
          name: ROUTE_NAMES.ADMIN_EMAIL_PREFERENCES,
          component: () => import('@/views/Admin/EmailPreferences.vue'),
          meta: {
            title: 'Email Preferences'
          },
        },
      ],
    },

    // User Routes
    {
      path: '/dashboard',
      name: ROUTE_NAMES.USER_DASHBOARD,
      component: () => import('@/views/User/Dashboard.vue'),
      meta: {
        requiresAuth: true,
        requiresRole: USER_ROLES.USER,
        title: 'My Tasks'
      },
    },

    // Profile Route (accessible to both admin and user)
    {
      path: '/profile',
      name: ROUTE_NAMES.PROFILE,
      component: () => import('@/views/Profile.vue'),
      meta: {
        requiresAuth: true,
        title: 'Profile'
      },
    },

    // 404 Route
    {
      path: '/:pathMatch(.*)*',
      name: 'not-found',
      component: () => import('@/views/NotFound.vue'),
      meta: {
        title: 'Page Not Found'
      },
    },
  ],
})

// Navigation Guards
router.beforeEach(async (to: RouteLocationNormalized, from: RouteLocationNormalized, next) => {
  const authStore = useAuthStore()

  // Initialize auth store if not already done
  if (!authStore.isInitialized) {
    await authStore.initializeAuth()
  }

  // Set page title
  if (to.meta.title) {
    document.title = `${to.meta.title} - Task Management`
  } else {
    document.title = 'Task Management'
  }

  // Check if route requires authentication
  if (to.meta.requiresAuth) {
    if (!authStore.isAuthenticated) {
      next({ name: ROUTE_NAMES.LOGIN, query: { redirect: to.fullPath } })
      return
    }

    // Check role-based access
    if (to.meta.requiresRole) {
      const requiredRole = to.meta.requiresRole as string
      if (!authStore.hasPermission(requiredRole)) {
        // Redirect to appropriate dashboard based on user role
        if (authStore.isAdmin) {
          next({ name: ROUTE_NAMES.ADMIN_DASHBOARD })
        } else {
          next({ name: ROUTE_NAMES.USER_DASHBOARD })
        }
        return
      }
    }
  }

  // Check if route requires guest (not authenticated)
  if (to.meta.requiresGuest && authStore.isAuthenticated) {
    // Redirect to appropriate dashboard based on user role
    if (authStore.isAdmin) {
      next({ name: ROUTE_NAMES.ADMIN_DASHBOARD })
    } else {
      next({ name: ROUTE_NAMES.USER_DASHBOARD })
    }
    return
  }

  next()
})

export default router
