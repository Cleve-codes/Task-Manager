<template>
  <div class="email-preferences-overview">
    <!-- Header -->
    <div class="d-flex align-center justify-space-between mb-6">
      <div>
        <h2 class="text-h5 font-weight-bold">Email Preferences Overview</h2>
        <p class="text-subtitle-1 text-medium-emphasis">
          Manage email notification preferences for all users
        </p>
      </div>
      
      <v-btn
        @click="refreshData"
        :loading="isLoading"
        variant="outlined"
        prepend-icon="mdi-refresh"
      >
        Refresh
      </v-btn>
    </div>

    <!-- Statistics Cards -->
    <v-row class="mb-6">
      <v-col
        v-for="(stat, preference) in statistics"
        :key="preference"
        cols="12"
        sm="6"
        lg="4"
        xl="2"
      >
        <v-card>
          <v-card-text class="text-center">
            <div class="text-h6 font-weight-bold mb-1">
              {{ stat.percentage }}%
            </div>
            <div class="text-caption text-medium-emphasis mb-2">
              {{ formatPreferenceName(preference) }}
            </div>
            <v-progress-linear
              :model-value="stat.percentage"
              :color="getPreferenceColor(stat.percentage)"
              height="6"
              rounded
            />
            <div class="text-caption mt-1">
              {{ stat.enabled }}/{{ stat.enabled + stat.disabled }} users
            </div>
          </v-card-text>
        </v-card>
      </v-col>
    </v-row>

    <!-- Users Table -->
    <v-card>
      <v-card-title class="d-flex align-center">
        <v-icon class="mr-2">mdi-account-group</v-icon>
        User Email Preferences
      </v-card-title>

      <v-card-text>
        <!-- Search -->
        <v-text-field
          v-model="searchQuery"
          prepend-inner-icon="mdi-magnify"
          label="Search users..."
          variant="outlined"
          density="compact"
          clearable
          class="mb-4"
        />

        <!-- Data Table -->
        <v-data-table
          :headers="headers"
          :items="filteredUsers"
          :loading="isLoading"
          :items-per-page="10"
          class="elevation-0"
        >
          <!-- User Info -->
          <template #item.user="{ item }">
            <div class="d-flex align-center">
              <v-avatar size="32" color="primary" class="mr-3">
                <span class="text-caption font-weight-bold">
                  {{ getInitials(item.name) }}
                </span>
              </v-avatar>
              <div>
                <div class="font-weight-medium">{{ item.name }}</div>
                <div class="text-caption text-medium-emphasis">{{ item.email }}</div>
              </div>
            </div>
          </template>

          <!-- Preference Toggles -->
          <template #item.task_assigned="{ item }">
            <v-switch
              :model-value="item.preferences.task_assigned"
              @update:model-value="updateUserPreference(item.id, 'task_assigned', $event)"
              :loading="updatingUsers.has(item.id)"
              color="primary"
              density="compact"
              hide-details
            />
          </template>

          <template #item.task_updated="{ item }">
            <v-switch
              :model-value="item.preferences.task_updated"
              @update:model-value="updateUserPreference(item.id, 'task_updated', $event)"
              :loading="updatingUsers.has(item.id)"
              color="primary"
              density="compact"
              hide-details
            />
          </template>

          <template #item.task_reminders="{ item }">
            <v-switch
              :model-value="item.preferences.task_reminders"
              @update:model-value="updateUserPreference(item.id, 'task_reminders', $event)"
              :loading="updatingUsers.has(item.id)"
              color="primary"
              density="compact"
              hide-details
            />
          </template>

          <template #item.welcome_email="{ item }">
            <v-switch
              :model-value="item.preferences.welcome_email"
              @update:model-value="updateUserPreference(item.id, 'welcome_email', $event)"
              :loading="updatingUsers.has(item.id)"
              color="primary"
              density="compact"
              hide-details
            />
          </template>

          <template #item.password_reset="{ item }">
            <v-switch
              :model-value="item.preferences.password_reset"
              @update:model-value="updateUserPreference(item.id, 'password_reset', $event)"
              :loading="updatingUsers.has(item.id)"
              color="primary"
              density="compact"
              hide-details
            />
          </template>

          <!-- Actions -->
          <template #item.actions="{ item }">
            <v-btn
              @click="openUserPreferencesDialog(item)"
              icon="mdi-cog"
              variant="text"
              size="small"
              :loading="updatingUsers.has(item.id)"
            />
          </template>
        </v-data-table>
      </v-card-text>
    </v-card>

    <!-- User Preferences Dialog -->
    <v-dialog v-model="showUserDialog" max-width="600">
      <v-card v-if="selectedUser">
        <v-card-title class="d-flex align-center">
          <v-icon class="mr-2">mdi-email-outline</v-icon>
          Email Preferences - {{ selectedUser.name }}
        </v-card-title>

        <v-card-text>
          <UserEmailPreferencesForm
            :user="selectedUser"
            :loading="isUpdatingUser"
            @submit="handleUserPreferencesUpdate"
            @cancel="closeUserDialog"
          />
        </v-card-text>
      </v-card>
    </v-dialog>

    <!-- Loading Overlay -->
    <LoadingSpinner
      v-if="isLoading"
      overlay
      text="Loading email preferences..."
    />
  </div>
</template>

<script setup lang="ts">
import { ref, computed, onMounted } from 'vue'
import { useToast } from 'vue-toastification'
import { apiService, type EmailPreferencesOverview, type EmailPreferencesUpdate } from '@/services/api'
import { getInitials } from '@/utils/helpers'
import UserEmailPreferencesForm from './UserEmailPreferencesForm.vue'
import LoadingSpinner from '@/components/common/LoadingSpinner.vue'

// Toast
const toast = useToast()

// State
const isLoading = ref(false)
const isUpdatingUser = ref(false)
const searchQuery = ref('')
const showUserDialog = ref(false)
const selectedUser = ref<any>(null)
const updatingUsers = ref(new Set<number>())

// Data
const users = ref<EmailPreferencesOverview['users']>([])
const statistics = ref<EmailPreferencesOverview['statistics']>({})

// Table headers
const headers = [
  { title: 'User', key: 'user', sortable: true },
  { title: 'Task Assigned', key: 'task_assigned', align: 'center' as const },
  { title: 'Task Updated', key: 'task_updated', align: 'center' as const },
  { title: 'Task Reminders', key: 'task_reminders', align: 'center' as const },
  { title: 'Welcome Email', key: 'welcome_email', align: 'center' as const },
  { title: 'Password Reset', key: 'password_reset', align: 'center' as const },
  { title: 'Actions', key: 'actions', align: 'center' as const, sortable: false },
]

// Computed
const filteredUsers = computed(() => {
  if (!searchQuery.value) return users.value
  
  const query = searchQuery.value.toLowerCase()
  return users.value.filter(user => 
    user.name.toLowerCase().includes(query) ||
    user.email.toLowerCase().includes(query)
  )
})

// Methods
const loadData = async () => {
  try {
    isLoading.value = true
    const data = await apiService.getEmailPreferencesOverview()
    users.value = data.users
    statistics.value = data.statistics
  } catch (error: any) {
    console.error('Error loading email preferences overview:', error)
    toast.error('Failed to load email preferences overview')
  } finally {
    isLoading.value = false
  }
}

const refreshData = () => {
  loadData()
}

const updateUserPreference = async (userId: number, preference: keyof EmailPreferencesUpdate, value: boolean | null) => {
  // Convert null to false for safety
  const booleanValue = value ?? false
  try {
    updatingUsers.value.add(userId)

    const updates: EmailPreferencesUpdate = { [preference]: booleanValue }
    await apiService.updateUserEmailPreferences(userId, updates)

    // Update local data
    const user = users.value.find(u => u.id === userId)
    if (user) {
      user.preferences[preference] = booleanValue
    }
    
    // Refresh statistics
    await loadData()
    
    toast.success('Preference updated successfully')
  } catch (error: any) {
    console.error('Error updating user preference:', error)
    toast.error('Failed to update preference')
  } finally {
    updatingUsers.value.delete(userId)
  }
}

const openUserPreferencesDialog = (user: any) => {
  selectedUser.value = user
  showUserDialog.value = true
}

const closeUserDialog = () => {
  showUserDialog.value = false
  selectedUser.value = null
}

const handleUserPreferencesUpdate = async (preferences: EmailPreferencesUpdate) => {
  if (!selectedUser.value) return

  try {
    isUpdatingUser.value = true
    
    await apiService.updateUserEmailPreferences(selectedUser.value.id, preferences)
    
    // Update local data
    Object.assign(selectedUser.value.preferences, preferences)
    
    // Refresh statistics
    await loadData()
    
    toast.success('User preferences updated successfully')
    closeUserDialog()
  } catch (error: any) {
    console.error('Error updating user preferences:', error)
    toast.error('Failed to update user preferences')
  } finally {
    isUpdatingUser.value = false
  }
}

const formatPreferenceName = (preference: string): string => {
  return preference.replace(/_/g, ' ').replace(/\b\w/g, l => l.toUpperCase())
}

const getPreferenceColor = (percentage: number): string => {
  if (percentage >= 80) return 'success'
  if (percentage >= 60) return 'warning'
  return 'error'
}

// Lifecycle
onMounted(() => {
  loadData()
})
</script>

<style scoped>
.email-preferences-overview {
  padding: 1rem;
}
</style>
