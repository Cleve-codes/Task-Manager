<template>
  <v-navigation-drawer
    v-model="isOpen"
    app
    :rail="rail"
    @click="rail = false"
  >
    <template #prepend>
      <v-list-item
        :title="rail ? undefined : authStore.userName"
        :subtitle="rail ? undefined : authStore.userEmail"
        nav
      >
        <template #prepend>
          <v-avatar color="secondary">
            <span class="text-white font-weight-bold">
              {{ userInitials }}
            </span>
          </v-avatar>
        </template>

        <template #append>
          <v-btn
            :icon="rail ? 'mdi-chevron-right' : 'mdi-chevron-left'"
            variant="text"
            @click.stop="toggleSidebar"
          />
        </template>
      </v-list-item>
    </template>

    <v-divider />

    <v-list density="compact" nav>
      <v-list-item
        v-for="item in navigationItems"
        :key="item.title"
        :to="item.to"
        :prepend-icon="item.icon"
        :title="item.title"
        exact
      />
    </v-list>

    <template #append>
      <div class="pa-2">
        <v-btn
          :block="!rail"
          :color="rail ? undefined : 'error'"
          :variant="rail ? 'text' : 'outlined'"
          :prepend-icon="rail ? undefined : 'mdi-logout'"
          :icon="rail ? 'mdi-logout' : undefined"
          :style="rail ? 'color: #FF5252 !important;' : undefined"
          @click="handleLogout"
        >
          <span v-if="!rail">Logout</span>
        </v-btn>
      </div>
    </template>
  </v-navigation-drawer>
</template>

<script setup lang="ts">
import { ref, computed, watch } from 'vue'
import { useAuthStore } from '@/stores/auth'
import { getInitials } from '@/utils/helpers'
import { ADMIN_NAV_ITEMS, USER_NAV_ITEMS } from '@/utils/constants'

// Props
interface Props {
  modelValue: boolean
}

const props = defineProps<Props>()

// Emits
const emit = defineEmits<{
  'update:modelValue': [value: boolean]
}>()

// Store
const authStore = useAuthStore()

// State
const rail = ref(false)

// Computed
const isOpen = computed({
  get: () => props.modelValue,
  set: (value) => emit('update:modelValue', value)
})

const userInitials = computed(() => getInitials(authStore.userName))

const navigationItems = computed(() => {
  return authStore.isAdmin ? ADMIN_NAV_ITEMS : USER_NAV_ITEMS
})

// Watch for route changes to close rail on mobile
watch(isOpen, (newValue) => {
  if (newValue) {
    rail.value = false
  }
})

// Methods
const toggleSidebar = () => {
  if (rail.value) {
    // If currently in rail mode, expand back to full sidebar
    rail.value = false
  } else {
    // If expanded, collapse to rail mode
    rail.value = true
  }
}

const handleLogout = async () => {
  await authStore.logout()
}
</script>
