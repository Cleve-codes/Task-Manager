<template>
  <v-form ref="formRef" @submit.prevent="handleSubmit">
    <div class="d-flex flex-column gap-4">
      <!-- User Info -->
      <v-alert
        type="info"
        variant="tonal"
        class="mb-4"
      >
        <div class="d-flex align-center">
          <v-avatar size="32" color="primary" class="mr-3">
            <span class="text-caption font-weight-bold">
              {{ getInitials(user.name) }}
            </span>
          </v-avatar>
          <div>
            <div class="font-weight-medium">{{ user.name }}</div>
            <div class="text-caption">{{ user.email }}</div>
          </div>
        </div>
      </v-alert>

      <!-- Task Assigned -->
      <div class="preference-item">
        <v-switch
          v-model="preferences.task_assigned"
          :disabled="loading"
          color="primary"
          hide-details
        >
          <template #label>
            <div class="preference-label">
              <div class="d-flex align-center">
                <v-icon class="mr-2" size="small">mdi-clipboard-plus</v-icon>
                <span class="font-weight-medium">Task Assigned</span>
              </div>
              <div class="text-caption text-medium-emphasis">
                Notify when new tasks are assigned
              </div>
            </div>
          </template>
        </v-switch>
      </div>

      <v-divider />

      <!-- Task Updated -->
      <div class="preference-item">
        <v-switch
          v-model="preferences.task_updated"
          :disabled="loading"
          color="primary"
          hide-details
        >
          <template #label>
            <div class="preference-label">
              <div class="d-flex align-center">
                <v-icon class="mr-2" size="small">mdi-clipboard-edit</v-icon>
                <span class="font-weight-medium">Task Updated</span>
              </div>
              <div class="text-caption text-medium-emphasis">
                Notify when tasks are updated or modified
              </div>
            </div>
          </template>
        </v-switch>
      </div>

      <v-divider />

      <!-- Task Reminders -->
      <div class="preference-item">
        <v-switch
          v-model="preferences.task_reminders"
          :disabled="loading"
          color="primary"
          hide-details
        >
          <template #label>
            <div class="preference-label">
              <div class="d-flex align-center">
                <v-icon class="mr-2" size="small">mdi-bell-outline</v-icon>
                <span class="font-weight-medium">Task Reminders</span>
              </div>
              <div class="text-caption text-medium-emphasis">
                Send reminder emails for upcoming and overdue tasks
              </div>
            </div>
          </template>
        </v-switch>
      </div>

      <v-divider />

      <!-- Welcome Email -->
      <div class="preference-item">
        <v-switch
          v-model="preferences.welcome_email"
          :disabled="loading"
          color="primary"
          hide-details
        >
          <template #label>
            <div class="preference-label">
              <div class="d-flex align-center">
                <v-icon class="mr-2" size="small">mdi-hand-wave</v-icon>
                <span class="font-weight-medium">Welcome Emails</span>
              </div>
              <div class="text-caption text-medium-emphasis">
                Send welcome emails and account setup information
              </div>
            </div>
          </template>
        </v-switch>
      </div>

      <v-divider />

      <!-- Password Reset -->
      <div class="preference-item">
        <v-switch
          v-model="preferences.password_reset"
          :disabled="loading"
          color="primary"
          hide-details
        >
          <template #label>
            <div class="preference-label">
              <div class="d-flex align-center">
                <v-icon class="mr-2" size="small">mdi-lock-reset</v-icon>
                <span class="font-weight-medium">Password Reset</span>
              </div>
              <div class="text-caption text-medium-emphasis">
                Send password reset and security-related emails
              </div>
            </div>
          </template>
        </v-switch>
      </div>
    </div>

    <!-- Action Buttons -->
    <div class="d-flex justify-end gap-2 mt-6">
      <v-btn
        @click="$emit('cancel')"
        variant="outlined"
        :disabled="loading"
      >
        Cancel
      </v-btn>
      
      <v-btn
        type="submit"
        color="primary"
        :loading="loading"
        :disabled="!hasChanges"
      >
        <v-icon left>mdi-content-save</v-icon>
        Update Preferences
      </v-btn>
    </div>
  </v-form>
</template>

<script setup lang="ts">
import { ref, reactive, computed, watch } from 'vue'
import { getInitials } from '@/utils/helpers'
import type { EmailPreferences, EmailPreferencesUpdate } from '@/services/api'

// Props
interface Props {
  user: {
    id: number
    name: string
    email: string
    preferences: EmailPreferences
  }
  loading?: boolean
}

const props = defineProps<Props>()

// Emits
const emit = defineEmits<{
  'submit': [preferences: EmailPreferencesUpdate]
  'cancel': []
}>()

// Form ref
const formRef = ref()

// Original preferences for comparison
const originalPreferences = ref<EmailPreferences>({ ...props.user.preferences })

// Form data
const preferences = reactive<EmailPreferences>({ ...props.user.preferences })

// Computed
const hasChanges = computed(() => {
  return Object.keys(preferences).some(key => {
    const prefKey = key as keyof EmailPreferences
    return preferences[prefKey] !== originalPreferences.value[prefKey]
  })
})

// Methods
const handleSubmit = () => {
  if (!hasChanges.value) return

  // Only send changed preferences
  const updates: EmailPreferencesUpdate = {}
  Object.keys(preferences).forEach(key => {
    const prefKey = key as keyof EmailPreferences
    if (preferences[prefKey] !== originalPreferences.value[prefKey]) {
      updates[prefKey] = preferences[prefKey]
    }
  })

  emit('submit', updates)
}

// Watch for user prop changes
watch(() => props.user, (newUser) => {
  if (newUser) {
    Object.assign(preferences, newUser.preferences)
    originalPreferences.value = { ...newUser.preferences }
  }
}, { immediate: true, deep: true })
</script>

<style scoped>
.preference-item {
  padding: 8px 0;
}

.preference-label {
  width: 100%;
}

.v-switch :deep(.v-selection-control__wrapper) {
  margin-right: 16px;
}
</style>
