import { STORAGE_KEYS, DATE_FORMATS } from './constants'

// Local Storage Helpers
export const storage = {
  get: (key: string): string | null => {
    try {
      return localStorage.getItem(key)
    } catch (error) {
      console.error('Error getting item from localStorage:', error)
      return null
    }
  },

  set: (key: string, value: string): void => {
    try {
      localStorage.setItem(key, value)
    } catch (error) {
      console.error('Error setting item in localStorage:', error)
    }
  },

  remove: (key: string): void => {
    try {
      localStorage.removeItem(key)
    } catch (error) {
      console.error('Error removing item from localStorage:', error)
    }
  },

  clear: (): void => {
    try {
      localStorage.clear()
    } catch (error) {
      console.error('Error clearing localStorage:', error)
    }
  },
}

// Token Management
export const tokenManager = {
  get: (): string | null => storage.get(STORAGE_KEYS.TOKEN),
  set: (token: string): void => storage.set(STORAGE_KEYS.TOKEN, token),
  remove: (): void => storage.remove(STORAGE_KEYS.TOKEN),
  exists: (): boolean => !!storage.get(STORAGE_KEYS.TOKEN),
}

// User Data Management
export const userManager = {
  get: (): any | null => {
    const userData = storage.get(STORAGE_KEYS.USER)
    return userData ? JSON.parse(userData) : null
  },
  set: (user: any): void => storage.set(STORAGE_KEYS.USER, JSON.stringify(user)),
  remove: (): void => storage.remove(STORAGE_KEYS.USER),
}

// Date Formatting Helpers
export const formatDate = (date: string | Date, format: string = DATE_FORMATS.DISPLAY): string => {
  if (!date) return ''
  
  const dateObj = typeof date === 'string' ? new Date(date) : date
  
  if (isNaN(dateObj.getTime())) return ''
  
  // Simple date formatting (you might want to use a library like date-fns or moment.js)
  const options: Intl.DateTimeFormatOptions = {}
  
  switch (format) {
    case DATE_FORMATS.DISPLAY:
      options.year = 'numeric'
      options.month = 'short'
      options.day = '2-digit'
      break
    case DATE_FORMATS.DISPLAY_WITH_TIME:
      options.year = 'numeric'
      options.month = 'short'
      options.day = '2-digit'
      options.hour = '2-digit'
      options.minute = '2-digit'
      break
    default:
      return dateObj.toLocaleDateString()
  }
  
  return dateObj.toLocaleDateString('en-US', options)
}

// Time Ago Helper
export const timeAgo = (date: string | Date): string => {
  if (!date) return ''
  
  const dateObj = typeof date === 'string' ? new Date(date) : date
  const now = new Date()
  const diffInSeconds = Math.floor((now.getTime() - dateObj.getTime()) / 1000)
  
  if (diffInSeconds < 60) return 'Just now'
  if (diffInSeconds < 3600) return `${Math.floor(diffInSeconds / 60)} minutes ago`
  if (diffInSeconds < 86400) return `${Math.floor(diffInSeconds / 3600)} hours ago`
  if (diffInSeconds < 2592000) return `${Math.floor(diffInSeconds / 86400)} days ago`
  
  return formatDate(dateObj)
}

// Capitalize First Letter
export const capitalize = (str: string): string => {
  if (!str) return ''
  return str.charAt(0).toUpperCase() + str.slice(1).toLowerCase()
}

// Truncate Text
export const truncate = (text: string, length: number = 100): string => {
  if (!text) return ''
  if (text.length <= length) return text
  return text.substring(0, length) + '...'
}

// Generate Initials
export const getInitials = (name: string): string => {
  if (!name) return ''
  return name
    .split(' ')
    .map(word => word.charAt(0).toUpperCase())
    .join('')
    .substring(0, 2)
}

// Debounce Function
export const debounce = <T extends (...args: any[]) => any>(
  func: T,
  wait: number
): ((...args: Parameters<T>) => void) => {
  let timeout: ReturnType<typeof setTimeout>
  
  return (...args: Parameters<T>) => {
    clearTimeout(timeout)
    timeout = setTimeout(() => func(...args), wait)
  }
}

// Deep Clone Object
export const deepClone = <T>(obj: T): T => {
  if (obj === null || typeof obj !== 'object') return obj
  if (obj instanceof Date) return new Date(obj.getTime()) as unknown as T
  if (obj instanceof Array) return obj.map(item => deepClone(item)) as unknown as T
  if (typeof obj === 'object') {
    const clonedObj = {} as T
    for (const key in obj) {
      if (obj.hasOwnProperty(key)) {
        clonedObj[key] = deepClone(obj[key])
      }
    }
    return clonedObj
  }
  return obj
}

// Check if Object is Empty
export const isEmpty = (obj: any): boolean => {
  if (obj === null || obj === undefined) return true
  if (typeof obj === 'string' || Array.isArray(obj)) return obj.length === 0
  if (typeof obj === 'object') return Object.keys(obj).length === 0
  return false
}

// Generate Random ID
export const generateId = (): string => {
  return Math.random().toString(36).substring(2) + Date.now().toString(36)
}

// Validate Email
export const isValidEmail = (email: string): boolean => {
  const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/
  return emailRegex.test(email)
}

// Format File Size
export const formatFileSize = (bytes: number): string => {
  if (bytes === 0) return '0 Bytes'
  
  const k = 1024
  const sizes = ['Bytes', 'KB', 'MB', 'GB']
  const i = Math.floor(Math.log(bytes) / Math.log(k))
  
  return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i]
}

// Sleep Function
export const sleep = (ms: number): Promise<void> => {
  return new Promise(resolve => setTimeout(resolve, ms))
}
