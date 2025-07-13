import axios, { type AxiosInstance, type AxiosResponse, type AxiosError } from 'axios'
import { useToast } from 'vue-toastification'
import { API_BASE_URL, API_ENDPOINTS, HTTP_STATUS } from '@/utils/constants'
import { tokenManager } from '@/utils/helpers'

// Types
export interface ApiResponse<T = any> {
  success: boolean
  message: string
  data?: T
  errors?: Record<string, string[]>
}

export interface LoginRequest {
  email: string
  password: string
}

export interface RegisterRequest {
  name: string
  email: string
  password: string
}

export interface AuthResponse {
  token: string
  user: User
}

export interface User {
  id: number
  name: string
  email: string
  role: 'admin' | 'user'
  created_at: string
  updated_at: string
  email_preferences?: EmailPreferences
}

export interface EmailPreferences {
  task_assigned: boolean
  task_updated: boolean
  task_reminders: boolean
  welcome_email: boolean
  password_reset: boolean
}

export interface EmailPreferencesUpdate {
  task_assigned?: boolean
  task_updated?: boolean
  task_reminders?: boolean
  welcome_email?: boolean
  password_reset?: boolean
}

export interface EmailPreferencesOverview {
  users: Array<{
    id: number
    name: string
    email: string
    preferences: EmailPreferences
  }>
  statistics: Record<string, {
    enabled: number
    disabled: number
    percentage: number
  }>
}

export interface Task {
  id: number
  title: string
  description: string | null
  status: 'Pending' | 'In Progress' | 'Completed'
  assigned_to: number
  deadline: string | null
  created_at: string
  updated_at: string
  user?: User
}

export interface TaskRequest {
  title: string
  description?: string
  status: 'Pending' | 'In Progress' | 'Completed'
  assigned_to: number
  deadline?: string
}

export interface TaskStatusUpdate {
  status: 'Pending' | 'In Progress' | 'Completed'
}

export interface UserRequest {
  name: string
  email: string
  password?: string
  role: 'admin' | 'user'
}

export interface ProfileUpdateRequest {
  name: string
  email: string
  current_password: string
  password?: string
}

// Create Axios instance
const createApiInstance = (): AxiosInstance => {
  const instance = axios.create({
    baseURL: API_BASE_URL,
    timeout: 10000,
    headers: {
      'Content-Type': 'application/json',
      'Accept': 'application/json',
    },
    // Don't use withCredentials for pure API authentication
    withCredentials: false,
  })

  // Request interceptor to add auth token
  instance.interceptors.request.use(
    (config) => {
      const token = tokenManager.get()
      if (token) {
        config.headers.Authorization = `Bearer ${token}`
      }
      return config
    },
    (error) => {
      return Promise.reject(error)
    }
  )

  // Response interceptor for error handling
  instance.interceptors.response.use(
    (response: AxiosResponse) => {
      return response
    },
    (error: AxiosError) => {
      const toast = useToast()
      
      if (error.response) {
        const status = error.response.status
        const data = error.response.data as any
        
        switch (status) {
          case 419: // CSRF token mismatch
            toast.error('Security token expired. Please refresh the page and try again.')
            break

          case HTTP_STATUS.UNAUTHORIZED:
            // Token expired or invalid
            tokenManager.remove()
            if (window.location.pathname !== '/login') {
              toast.error('Session expired. Please login again.')
              window.location.href = '/login'
            }
            break

          case HTTP_STATUS.FORBIDDEN:
            toast.error(data?.message || 'Access denied')
            break

          case HTTP_STATUS.NOT_FOUND:
            toast.error(data?.message || 'Resource not found')
            break

          case HTTP_STATUS.UNPROCESSABLE_ENTITY:
            // Validation errors - don't show toast, let component handle
            break

          case HTTP_STATUS.INTERNAL_SERVER_ERROR:
            toast.error('Server error. Please try again later.')
            break

          default:
            toast.error(data?.message || 'An error occurred')
        }
      } else if (error.request) {
        // Network error
        toast.error('Network error. Please check your connection.')
      } else {
        // Other error
        toast.error('An unexpected error occurred')
      }
      
      return Promise.reject(error)
    }
  )

  return instance
}

// Create API instance
const api = createApiInstance()

// API Service Class
class ApiService {
  // Authentication
  async login(credentials: LoginRequest): Promise<AuthResponse> {
    const response = await api.post<AuthResponse>(API_ENDPOINTS.LOGIN, credentials)
    return response.data
  }

  async register(userData: RegisterRequest): Promise<User> {
    const response = await api.post<User>(API_ENDPOINTS.REGISTER, userData)
    return response.data
  }

  async logout(): Promise<void> {
    await api.post(API_ENDPOINTS.LOGOUT)
  }

  async getCurrentUser(): Promise<User> {
    const response = await api.get<User>(API_ENDPOINTS.USER)
    return response.data
  }

  async updateProfile(profileData: ProfileUpdateRequest): Promise<User> {
    const response = await api.put<User>('/profile', profileData)
    return response.data
  }

  // Tasks
  async getTasks(): Promise<ApiResponse<Task[]>> {
    const response = await api.get<ApiResponse<Task[]>>(API_ENDPOINTS.TASKS)
    return response.data
  }

  async getMyTasks(): Promise<ApiResponse<Task[]>> {
    const response = await api.get<ApiResponse<Task[]>>(API_ENDPOINTS.MY_TASKS)
    return response.data
  }

  async getTask(id: number): Promise<ApiResponse<Task>> {
    const response = await api.get<ApiResponse<Task>>(`${API_ENDPOINTS.TASKS}/${id}`)
    return response.data
  }

  async createTask(taskData: TaskRequest): Promise<ApiResponse<Task>> {
    const response = await api.post<ApiResponse<Task>>(API_ENDPOINTS.TASKS, taskData)
    return response.data
  }

  async updateTask(id: number, taskData: Partial<TaskRequest>): Promise<ApiResponse<Task>> {
    const response = await api.put<ApiResponse<Task>>(`${API_ENDPOINTS.TASKS}/${id}`, taskData)
    return response.data
  }

  async updateTaskStatus(id: number, status: TaskStatusUpdate): Promise<ApiResponse<Task>> {
    const response = await api.patch<ApiResponse<Task>>(API_ENDPOINTS.TASK_STATUS(id), status)
    return response.data
  }

  async deleteTask(id: number): Promise<ApiResponse<null>> {
    const response = await api.delete<ApiResponse<null>>(`${API_ENDPOINTS.TASKS}/${id}`)
    return response.data
  }

  // Users (Admin only)
  async getUsers(): Promise<User[]> {
    const response = await api.get<User[]>(API_ENDPOINTS.USERS)
    return response.data
  }

  async getUser(id: number): Promise<User> {
    const response = await api.get<User>(API_ENDPOINTS.USER_BY_ID(id))
    return response.data
  }

  async createUser(userData: UserRequest): Promise<User> {
    const response = await api.post<User>(API_ENDPOINTS.USERS, userData)
    return response.data
  }

  async updateUser(id: number, userData: Partial<UserRequest>): Promise<User> {
    const response = await api.put<User>(API_ENDPOINTS.USER_BY_ID(id), userData)
    return response.data
  }

  async deleteUser(id: number): Promise<void> {
    await api.delete(API_ENDPOINTS.USER_BY_ID(id))
  }

  // Email Preferences
  async getEmailPreferences(): Promise<EmailPreferences> {
    const response = await api.get<EmailPreferences>(API_ENDPOINTS.EMAIL_PREFERENCES)
    return response.data
  }

  async updateEmailPreferences(preferences: EmailPreferencesUpdate): Promise<{ message: string; preferences: EmailPreferences }> {
    const response = await api.put<{ message: string; preferences: EmailPreferences }>(
      API_ENDPOINTS.EMAIL_PREFERENCES,
      preferences
    )
    return response.data
  }

  async getUserEmailPreferences(id: number): Promise<EmailPreferences> {
    const response = await api.get<EmailPreferences>(API_ENDPOINTS.USER_EMAIL_PREFERENCES(id))
    return response.data
  }

  async updateUserEmailPreferences(id: number, preferences: EmailPreferencesUpdate): Promise<{ message: string; preferences: EmailPreferences; user: Partial<User> }> {
    const response = await api.put<{ message: string; preferences: EmailPreferences; user: Partial<User> }>(
      API_ENDPOINTS.USER_EMAIL_PREFERENCES(id),
      preferences
    )
    return response.data
  }

  async getEmailPreferencesOverview(): Promise<EmailPreferencesOverview> {
    const response = await api.get<EmailPreferencesOverview>(API_ENDPOINTS.EMAIL_PREFERENCES_OVERVIEW)
    return response.data
  }
}

// Export singleton instance
export const apiService = new ApiService()
export default api
