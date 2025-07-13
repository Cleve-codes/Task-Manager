<template>
  <v-app-bar
    :elevation="2"
    color="primary"
    dark
    app
  >
    <v-app-bar-nav-icon
      v-if="authStore.isAuthenticated"
      @click="$emit('toggle-sidebar')"
    />

    <v-app-bar-title class="font-weight-bold">
      Task Management
    </v-app-bar-title>

    <v-spacer />

    <template v-if="authStore.isAuthenticated">
      <!-- Theme Toggle -->
      <ThemeToggle class="mr-2" />

      <!-- User Menu -->
      <v-menu offset-y>
        <template #activator="{ props }">
          <v-btn
            v-bind="props"
            icon
            class="mr-2"
          >
            <v-avatar size="32" color="secondary">
              <span class="text-white font-weight-bold">
                {{ userInitials }}
              </span>
            </v-avatar>
          </v-btn>
        </template>

        <v-list>
          <v-list-item>
            <v-list-item-title class="font-weight-bold">
              {{ authStore.userName }}
            </v-list-item-title>
            <v-list-item-subtitle>
              {{ authStore.userEmail }}
            </v-list-item-subtitle>
          </v-list-item>

          <v-divider />

          <v-list-item
            prepend-icon="mdi-account-circle"
            title="Profile"
            @click="handleProfile"
          />

          <v-list-item
            prepend-icon="mdi-logout"
            title="Logout"
            @click="handleLogout"
          />
        </v-list>
      </v-menu>

      <!-- Role Badge -->
      <v-chip
        :color="authStore.isAdmin ? 'warning' : 'info'"
        size="small"
        class="mr-2"
      >
        {{ authStore.userRole.toUpperCase() }}
      </v-chip>
    </template>
  </v-app-bar>
</template>

<script setup lang="ts">
import { computed } from 'vue'
import { useRouter } from 'vue-router'
import { useAuthStore } from '@/stores/auth'
import { getInitials } from '@/utils/helpers'
import { ROUTE_NAMES } from '@/utils/constants'
import ThemeToggle from '@/components/common/ThemeToggle.vue'

// Emits
defineEmits<{
  'toggle-sidebar': []
}>()

// Router and Store
const router = useRouter()
const authStore = useAuthStore()

// Computed
const userInitials = computed(() => getInitials(authStore.userName))

// Methods
const handleProfile = () => {
  router.push({ name: ROUTE_NAMES.PROFILE })
}

const handleLogout = async () => {
  await authStore.logout()
}
</script>
