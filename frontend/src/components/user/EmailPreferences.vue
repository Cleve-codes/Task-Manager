<template>
  <v-card>
    <v-card-title class="d-flex align-center">
      <v-icon class="mr-2">mdi-email-outline</v-icon>
      Email Notification Preferences
    </v-card-title>

    <v-card-subtitle>
      Choose which email notifications you'd like to receive
    </v-card-subtitle>

    <v-card-text>
      <v-form ref="formRef" @submit.prevent="handleSubmit">
        <div class="d-flex flex-column gap-4">
          <!-- Task Assigned -->
          <div class="preference-item">
            <v-switch
              v-model="preferences.task_assigned"
              :disabled="isLoading"
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
                    Get notified when a new task is assigned to you
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
              :disabled="isLoading"
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
                    Get notified when your tasks are updated or modified
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
              :disabled="isLoading"
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
                    Get reminder emails for upcoming and overdue tasks
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
              :disabled="isLoading"
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
                    Receive welcome emails and account setup information
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
              :disabled="isLoading"
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
                    Receive password reset and security-related emails
                  </div>
                </div>
              </template>
            </v-switch>
          </div>
        </div>

        <!-- Action Buttons -->
        <div class="d-flex justify-end mt-6">
          <v-btn
            type="submit"
            color="primary"
            :loading="isLoading"
            :disabled="!hasChanges"
          >
            <v-icon left>mdi-content-save</v-icon>
            Save Preferences
          </v-btn>
        </div>
      </v-form>
    </v-card-text>
  </v-card>
</template>

<script setup lang="ts">
import { ref, reactive, computed, onMounted, watch } from 'vue'
import { useToast } from 'vue-toastification'
import { apiService, type EmailPreferences, type EmailPreferencesUpdate } from '@/services/api'

// Toast
const toast = useToast()

// Form ref
const formRef = ref()

// State
const isLoading = ref(false)
const originalPreferences = ref<EmailPreferences | null>(null)

// Form data
const preferences = reactive<EmailPreferences>({
  task_assigned: true,
  task_updated: true,
  task_reminders: true,
  welcome_email: true,
  password_reset: true,
})

// Computed
const hasChanges = computed(() => {
  if (!originalPreferences.value) return false
  
  return Object.keys(preferences).some(key => {
    const prefKey = key as keyof EmailPreferences
    return preferences[prefKey] !== originalPreferences.value![prefKey]
  })
})

// Methods
const loadPreferences = async () => {
  try {
    isLoading.value = true
    const data = await apiService.getEmailPreferences()
    
    // Update reactive preferences
    Object.assign(preferences, data)
    
    // Store original for comparison
    originalPreferences.value = { ...data }
  } catch (error: any) {
    console.error('Error loading email preferences:', error)
    toast.error('Failed to load email preferences')
  } finally {
    isLoading.value = false
  }
}

const handleSubmit = async () => {
  if (!hasChanges.value) return

  try {
    isLoading.value = true
    
    // Only send changed preferences
    const updates: EmailPreferencesUpdate = {}
    Object.keys(preferences).forEach(key => {
      const prefKey = key as keyof EmailPreferences
      if (preferences[prefKey] !== originalPreferences.value![prefKey]) {
        updates[prefKey] = preferences[prefKey]
      }
    })

    const response = await apiService.updateEmailPreferences(updates)
    
    // Update original preferences
    originalPreferences.value = { ...response.preferences }
    Object.assign(preferences, response.preferences)
    
    toast.success('Email preferences updated successfully!')
  } catch (error: any) {
    console.error('Error updating email preferences:', error)
    toast.error(error.response?.data?.message || 'Failed to update email preferences')
  } finally {
    isLoading.value = false
  }
}

// Lifecycle
onMounted(() => {
  loadPreferences()
})
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
