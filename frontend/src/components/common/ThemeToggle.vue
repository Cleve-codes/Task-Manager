<template>
  <v-btn
    :icon="themeStore.isDark ? 'mdi-white-balance-sunny' : 'mdi-moon-waning-crescent'"
    variant="text"
    color="white"
    size="default"
    @click="themeStore.toggleTheme"
    :title="themeStore.isDark ? 'Switch to Light Mode' : 'Switch to Dark Mode'"
    class="theme-toggle-btn"
  />
</template>

<script setup lang="ts">
import { useThemeStore } from '@/stores/theme'

const themeStore = useThemeStore()
</script>

<style scoped>
.theme-toggle-btn {
  /* Enhanced visibility and styling */
  transition: all 0.2s ease;
}

.theme-toggle-btn :deep(.v-icon) {
  font-size: 22px !important;
  font-weight: 600 !important;
  color: white !important;
  filter: drop-shadow(0 1px 2px rgba(0, 0, 0, 0.3));
}

.theme-toggle-btn:hover {
  background-color: rgba(255, 255, 255, 0.1) !important;
  transform: scale(1.05);
}

.theme-toggle-btn:hover :deep(.v-icon) {
  filter: drop-shadow(0 2px 4px rgba(0, 0, 0, 0.4));
}

/* Ensure visibility in both themes */
.v-theme--light .theme-toggle-btn :deep(.v-icon),
.v-theme--dark .theme-toggle-btn :deep(.v-icon) {
  color: white !important;
}
</style>
