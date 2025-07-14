<template>
  <v-app>
    <!-- Header -->
    <Header
      v-if="authStore.isAuthenticated"
      @toggle-sidebar="sidebarOpen = !sidebarOpen"
    />

    <!-- Sidebar -->
    <Sidebar
      v-if="authStore.isAuthenticated"
      v-model="sidebarOpen"
    />

    <!-- Main Content -->
    <v-main>
      <RouterView />
    </v-main>
  </v-app>
</template>

<script setup lang="ts">
import { ref, onMounted, watch } from 'vue'
import { RouterView } from 'vue-router'
import { useTheme } from 'vuetify'
import { useAuthStore } from '@/stores/auth'
import { useThemeStore } from '@/stores/theme'
import Header from '@/components/common/Header.vue'
import Sidebar from '@/components/common/Sidebar.vue'

// Stores
const authStore = useAuthStore()
const themeStore = useThemeStore()

// Vuetify theme
const theme = useTheme()

// State
const sidebarOpen = ref(true)

// Watch theme store and update Vuetify theme
watch(() => themeStore.currentTheme, (newTheme) => {
  theme.global.name.value = newTheme
}, { immediate: true })

// Initialize auth and theme on app start
onMounted(async () => {
  await authStore.initializeAuth()
  themeStore.initializeTheme()
  // Ensure Vuetify theme is synced
  theme.global.name.value = themeStore.currentTheme
})
</script>

<style>
/* Global styles */
html, body {
  width: 100%;
  height: 100%;
  margin: 0;
  padding: 0;
  overflow-x: hidden;
}

.v-application {
  font-family: 'Roboto', sans-serif !important;
  width: 100% !important;
  height: 100vh !important;
}

.v-main {
  width: 100% !important;
}

/* Custom scrollbar - Light theme */
.v-theme--light ::-webkit-scrollbar {
  width: 8px;
}

.v-theme--light ::-webkit-scrollbar-track {
  background: #f1f1f1;
}

.v-theme--light ::-webkit-scrollbar-thumb {
  background: #c1c1c1;
  border-radius: 4px;
}

.v-theme--light ::-webkit-scrollbar-thumb:hover {
  background: #a8a8a8;
}

/* Custom scrollbar - Dark theme */
.v-theme--dark ::-webkit-scrollbar {
  width: 8px;
}

.v-theme--dark ::-webkit-scrollbar-track {
  background: #21262D;
}

.v-theme--dark ::-webkit-scrollbar-thumb {
  background: #4F545C;
  border-radius: 4px;
}

.v-theme--dark ::-webkit-scrollbar-thumb:hover {
  background: #6C757D;
}

/* Smooth transitions */
.v-application {
  transition: background-color 0.3s ease, color 0.3s ease;
}

.v-card {
  transition: all 0.3s ease, background-color 0.3s ease, border-color 0.3s ease;
}

.v-btn {
  transition: all 0.2s ease;
}

.v-app-bar {
  transition: background-color 0.3s ease, color 0.3s ease;
}

.v-navigation-drawer {
  transition: background-color 0.3s ease, color 0.3s ease;
}

/* Theme-specific enhancements */
.v-theme--light .v-card {
  border: 1px solid rgba(0, 0, 0, 0.05);
  box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

.v-theme--dark .v-card {
  border: 1px solid rgba(255, 255, 255, 0.05);
  box-shadow: 0 4px 8px rgba(0, 0, 0, 0.3);
}

/* Custom gap utility */
.gap-2 {
  gap: 0.5rem;
}

.gap-3 {
  gap: 0.75rem;
}

.gap-4 {
  gap: 1rem;
}

/* Ensure containers take full width */
.v-container {
  max-width: 100% !important;
}

.v-container.fluid {
  padding: 0 !important;
}
</style>
