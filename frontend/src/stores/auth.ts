import { ref, computed } from 'vue'
import { defineStore } from 'pinia'
import { useToast } from 'vue-toastification'
import { useRouter } from 'vue-router'
import { apiService, type User, type LoginRequest, type RegisterRequest } from '@/services/api'
import { tokenManager, userManager } from '@/utils/helpers'
import { USER_ROLES, ROUTE_NAMES } from '@/utils/constants'

export const useAuthStore = defineStore('auth', () => {
  // State
  const user = ref<User | null>(null)
  const token = ref<string | null>(null)
  const isLoading = ref(false)
  const isInitialized = ref(false)

  // Getters
  const isAuthenticated = computed(() => !!token.value && !!user.value)
  const isAdmin = computed(() => user.value?.role === USER_ROLES.ADMIN)
  const isUser = computed(() => user.value?.role === USER_ROLES.USER)
  const userName = computed(() => user.value?.name || '')
  const userEmail = computed(() => user.value?.email || '')
  const userRole = computed(() => user.value?.role || '')

  // Actions
  const toast = useToast()
  const router = useRouter()

  // Initialize auth state from localStorage
  const initializeAuth = async (): Promise<void> => {
    if (isInitialized.value) return

    try {
      const storedToken = tokenManager.get()
      const storedUser = userManager.get()

      if (storedToken && storedUser) {
        token.value = storedToken
        user.value = storedUser

        // Verify token is still valid by fetching current user
        try {
          const currentUser = await apiService.getCurrentUser()
          updateUser(currentUser)
        } catch (error) {
          // Token is invalid, clear auth state
          await logout(false)
        }
      }
    } catch (error) {
      console.error('Error initializing auth:', error)
      await logout(false)
    } finally {
      isInitialized.value = true
    }
  }

  // Login
  const login = async (credentials: LoginRequest): Promise<boolean> => {
    try {
      isLoading.value = true
      
      const response = await apiService.login(credentials)
      
      // Store auth data
      token.value = response.token
      user.value = response.user
      
      tokenManager.set(response.token)
      userManager.set(response.user)
      
      toast.success(`Welcome back, ${response.user.name}!`)
      
      // Redirect based on role
      if (response.user.role === USER_ROLES.ADMIN) {
        await router.push({ name: ROUTE_NAMES.ADMIN_DASHBOARD })
      } else {
        await router.push({ name: ROUTE_NAMES.USER_DASHBOARD })
      }
      
      return true
    } catch (error: any) {
      console.error('Login error:', error)
      
      if (error.response?.status === 401) {
        toast.error('Invalid email or password')
      } else {
        toast.error('Login failed. Please try again.')
      }
      
      return false
    } finally {
      isLoading.value = false
    }
  }

  // Register
  const register = async (userData: RegisterRequest): Promise<boolean> => {
    try {
      isLoading.value = true
      
      await apiService.register(userData)
      
      toast.success('Registration successful! Please login with your credentials.')
      await router.push({ name: ROUTE_NAMES.LOGIN })
      
      return true
    } catch (error: any) {
      console.error('Registration error:', error)
      
      if (error.response?.status === 422) {
        const errors = error.response.data?.errors
        if (errors) {
          Object.values(errors).flat().forEach((message: any) => {
            toast.error(message)
          })
        } else {
          toast.error('Registration failed. Please check your information.')
        }
      } else {
        toast.error('Registration failed. Please try again.')
      }
      
      return false
    } finally {
      isLoading.value = false
    }
  }

  // Logout
  const logout = async (showMessage: boolean = true): Promise<void> => {
    try {
      if (token.value) {
        await apiService.logout()
      }
    } catch (error) {
      console.error('Logout error:', error)
    } finally {
      // Clear auth state
      user.value = null
      token.value = null
      
      // Clear localStorage
      tokenManager.remove()
      userManager.remove()
      
      if (showMessage) {
        toast.info('You have been logged out')
      }
      
      // Redirect to login
      await router.push({ name: ROUTE_NAMES.LOGIN })
    }
  }

  // Update user data locally
  const updateUser = (userData: User): void => {
    user.value = userData
    userManager.set(userData)
  }

  // Update user profile
  const updateProfile = async (profileData: any): Promise<boolean> => {
    try {
      if (!user.value) return false

      isLoading.value = true

      const updatedUser = await apiService.updateProfile(profileData)

      updateUser(updatedUser)

      return true
    } catch (error: any) {
      console.error('Profile update error:', error)
      throw error // Re-throw to let component handle the error
    } finally {
      isLoading.value = false
    }
  }

  // Check if user has permission
  const hasPermission = (requiredRole: string): boolean => {
    if (!user.value) return false
    
    if (requiredRole === USER_ROLES.ADMIN) {
      return user.value.role === USER_ROLES.ADMIN
    }
    
    return true // All authenticated users have basic permissions
  }

  // Refresh user data
  const refreshUser = async (): Promise<void> => {
    try {
      if (!token.value) return
      
      const currentUser = await apiService.getCurrentUser()
      updateUser(currentUser)
    } catch (error) {
      console.error('Error refreshing user:', error)
      await logout(false)
    }
  }

  return {
    // State
    user,
    token,
    isLoading,
    isInitialized,
    
    // Getters
    isAuthenticated,
    isAdmin,
    isUser,
    userName,
    userEmail,
    userRole,
    
    // Actions
    initializeAuth,
    login,
    register,
    logout,
    updateUser,
    updateProfile,
    hasPermission,
    refreshUser,
  }
})
