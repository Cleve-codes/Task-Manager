import { defineStore } from 'pinia'
import { ref, computed } from 'vue'

export const useThemeStore = defineStore('theme', () => {
  // State
  const isDark = ref(false)

  // Initialize theme from localStorage or system preference
  const initializeTheme = () => {
    const savedTheme = localStorage.getItem('theme')
    if (savedTheme) {
      isDark.value = savedTheme === 'dark'
    } else {
      // Check system preference
      isDark.value = window.matchMedia('(prefers-color-scheme: dark)').matches
    }

    // Listen for system theme changes (only if no saved preference)
    if (!savedTheme) {
      const mediaQuery = window.matchMedia('(prefers-color-scheme: dark)')
      mediaQuery.addEventListener('change', (e) => {
        // Only update if user hasn't set a preference
        if (!localStorage.getItem('theme')) {
          isDark.value = e.matches
        }
      })
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
    localStorage.removeItem('theme')
    isDark.value = window.matchMedia('(prefers-color-scheme: dark)').matches
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
