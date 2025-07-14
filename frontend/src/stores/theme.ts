import { defineStore } from 'pinia'
import { ref, computed } from 'vue'

export const useThemeStore = defineStore('theme', () => {
  // State
  const isDark = ref(false)

  // Initialize theme from localStorage or default to light
  const initializeTheme = () => {
    const savedTheme = localStorage.getItem('theme')
    if (savedTheme) {
      isDark.value = savedTheme === 'dark'
    } else {
      // Default to light theme to avoid conflicts
      isDark.value = false
      localStorage.setItem('theme', 'light')
    }
  }

  // Getters
  const currentTheme = computed(() => isDark.value ? 'dark' : 'light')

  // Actions
  const toggleTheme = () => {
    isDark.value = !isDark.value
    localStorage.setItem('theme', currentTheme.value)
  }

  const setTheme = (theme: 'light' | 'dark') => {
    isDark.value = theme === 'dark'
    localStorage.setItem('theme', theme)
  }

  const resetToSystemTheme = () => {
    // Default to light theme for consistency
    localStorage.setItem('theme', 'light')
    isDark.value = false
  }

  return {
    isDark,
    currentTheme,
    initializeTheme,
    toggleTheme,
    setTheme,
    resetToSystemTheme
  }
})
