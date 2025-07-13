<template>
  <div class="profile-page">
    <v-row>
      <!-- Page Header -->
      <v-col cols="12">
        <div class="d-flex align-center mb-4">
          <div>
            <h1 class="text-h4 font-weight-bold">Profile Settings</h1>
            <p class="text-subtitle-1 text-medium-emphasis">
              Manage your account information
            </p>
          </div>
        </div>
      </v-col>

      <!-- Profile Form -->
      <v-col cols="12" md="8" lg="6">
        <v-card>
          <v-card-title class="d-flex align-center">
            <v-icon class="mr-2">mdi-account-circle</v-icon>
            Personal Information
          </v-card-title>

          <v-card-text>
            <v-form ref="formRef" @submit.prevent="handleSubmit">
              <v-row>
                <v-col cols="12">
                  <v-text-field
                    v-model="form.name"
                    :rules="nameRules"
                    label="Full Name"
                    prepend-inner-icon="mdi-account"
                    variant="outlined"
                    :disabled="isLoading"
                    required
                  />
                </v-col>

                <v-col cols="12">
                  <v-text-field
                    v-model="form.email"
                    :rules="emailRules"
                    label="Email"
                    type="email"
                    prepend-inner-icon="mdi-email"
                    variant="outlined"
                    :disabled="isLoading"
                    required
                  />
                </v-col>

                <v-col cols="12">
                  <v-text-field
                    v-model="form.currentPassword"
                    :rules="currentPasswordRules"
                    :type="showCurrentPassword ? 'text' : 'password'"
                    label="Current Password"
                    prepend-inner-icon="mdi-lock"
                    :append-inner-icon="showCurrentPassword ? 'mdi-eye' : 'mdi-eye-off'"
                    @click:append-inner="showCurrentPassword = !showCurrentPassword"
                    variant="outlined"
                    :disabled="isLoading"
                    hint="Required to save changes"
                    persistent-hint
                    required
                  />
                </v-col>

                <v-col cols="12">
                  <v-text-field
                    v-model="form.newPassword"
                    :rules="newPasswordRules"
                    :type="showNewPassword ? 'text' : 'password'"
                    label="New Password (optional)"
                    prepend-inner-icon="mdi-lock-plus"
                    :append-inner-icon="showNewPassword ? 'mdi-eye' : 'mdi-eye-off'"
                    @click:append-inner="showNewPassword = !showNewPassword"
                    variant="outlined"
                    :disabled="isLoading"
                    hint="Leave blank to keep current password"
                    persistent-hint
                  />
                </v-col>

                <v-col v-if="form.newPassword" cols="12">
                  <v-text-field
                    v-model="confirmPassword"
                    :rules="confirmPasswordRules"
                    :type="showConfirmPassword ? 'text' : 'password'"
                    label="Confirm New Password"
                    prepend-inner-icon="mdi-lock-check"
                    :append-inner-icon="showConfirmPassword ? 'mdi-eye' : 'mdi-eye-off'"
                    @click:append-inner="showConfirmPassword = !showConfirmPassword"
                    variant="outlined"
                    :disabled="isLoading"
                    required
                  />
                </v-col>
              </v-row>

              <v-row class="mt-4">
                <v-col cols="12" class="d-flex justify-end gap-2">
                  <v-btn
                    variant="outlined"
                    @click="resetForm"
                    :disabled="isLoading"
                  >
                    Reset
                  </v-btn>
                  
                  <v-btn
                    type="submit"
                    color="primary"
                    :loading="isLoading"
                    :disabled="!isFormValid"
                  >
                    Update Profile
                  </v-btn>
                </v-col>
              </v-row>
            </v-form>
          </v-card-text>
        </v-card>
      </v-col>

      <!-- Email Preferences Card -->
      <v-col cols="12">
        <EmailPreferences />
      </v-col>

      <!-- Profile Info Card -->
      <v-col cols="12" md="4" lg="6">
        <v-card>
          <v-card-title class="d-flex align-center">
            <v-icon class="mr-2">mdi-information</v-icon>
            Account Information
          </v-card-title>

          <v-card-text>
            <div class="d-flex flex-column gap-4">
              <div class="text-center">
                <v-avatar size="80" color="primary">
                  <span class="text-h4 text-white font-weight-bold">
                    {{ userInitials }}
                  </span>
                </v-avatar>
                <div class="text-h6 mt-2">{{ authStore.userName }}</div>
                <v-chip
                  :color="authStore.isAdmin ? 'warning' : 'info'"
                  size="small"
                  class="mt-1"
                >
                  {{ authStore.userRole.toUpperCase() }}
                </v-chip>
              </div>

              <v-divider />

              <div>
                <div class="text-subtitle-2 text-medium-emphasis mb-2">
                  Account Details
                </div>
                
                <div class="d-flex justify-space-between mb-2">
                  <span class="font-weight-medium">Email:</span>
                  <span>{{ authStore.userEmail }}</span>
                </div>
                
                <div class="d-flex justify-space-between mb-2">
                  <span class="font-weight-medium">Role:</span>
                  <span>{{ authStore.userRole }}</span>
                </div>
                
                <div class="d-flex justify-space-between">
                  <span class="font-weight-medium">Member since:</span>
                  <span>{{ formatDate(authStore.user?.created_at || '') }}</span>
                </div>
              </div>
            </div>
          </v-card-text>
        </v-card>
      </v-col>
    </v-row>

    <!-- Loading Overlay -->
    <LoadingSpinner
      v-if="isLoading"
      overlay
      text="Updating profile..."
    />
  </div>
</template>

<script setup lang="ts">
import { ref, computed, reactive, onMounted } from 'vue'
import { useToast } from 'vue-toastification'
import { useAuthStore } from '@/stores/auth'
import { apiService } from '@/services/api'
import { formatDate, getInitials } from '@/utils/helpers'
import { VALIDATION_RULES } from '@/utils/constants'
import LoadingSpinner from '@/components/common/LoadingSpinner.vue'
import EmailPreferences from '@/components/user/EmailPreferences.vue'

// Store and Toast
const authStore = useAuthStore()
const toast = useToast()

// Form ref
const formRef = ref()

// Form state
const isLoading = ref(false)
const showCurrentPassword = ref(false)
const showNewPassword = ref(false)
const showConfirmPassword = ref(false)
const confirmPassword = ref('')

const form = reactive({
  name: '',
  email: '',
  currentPassword: '',
  newPassword: '',
})

// Computed
const userInitials = computed(() => getInitials(authStore.userName))

// Validation rules
const nameRules = [
  VALIDATION_RULES.REQUIRED,
  VALIDATION_RULES.MIN_LENGTH(2),
  VALIDATION_RULES.MAX_LENGTH(50),
]

const emailRules = [
  VALIDATION_RULES.REQUIRED,
  VALIDATION_RULES.EMAIL,
]

const currentPasswordRules = [
  VALIDATION_RULES.REQUIRED,
]

const newPasswordRules = [
  (value: string) => !value || value.length >= 8 || 'Password must be at least 8 characters',
]

const confirmPasswordRules = [
  (value: string) => {
    if (!form.newPassword) return true
    return value === form.newPassword || 'Passwords do not match'
  },
]

const isFormValid = computed(() => {
  const baseValid = form.name && 
                   form.email && 
                   form.currentPassword &&
                   VALIDATION_RULES.EMAIL(form.email) === true

  if (form.newPassword) {
    return baseValid && 
           form.newPassword.length >= 8 &&
           confirmPassword.value === form.newPassword
  }
  
  return baseValid
})

// Methods
const resetForm = () => {
  form.name = authStore.userName
  form.email = authStore.userEmail
  form.currentPassword = ''
  form.newPassword = ''
  confirmPassword.value = ''
}

const handleSubmit = async () => {
  const { valid } = await formRef.value.validate()
  
  if (valid) {
    try {
      isLoading.value = true
      
      const updateData: any = {
        name: form.name,
        email: form.email,
        current_password: form.currentPassword,
      }
      
      if (form.newPassword) {
        updateData.password = form.newPassword
      }
      
      // Call API to update profile
      const updatedUser = await apiService.updateProfile(updateData)
      
      // Update auth store with new user data
      authStore.updateUser(updatedUser)
      
      toast.success('Profile updated successfully')
      
      // Reset password fields
      form.currentPassword = ''
      form.newPassword = ''
      confirmPassword.value = ''
      
    } catch (error: any) {
      console.error('Error updating profile:', error)
      
      if (error.response?.status === 422) {
        const errors = error.response.data?.errors
        if (errors) {
          Object.values(errors).flat().forEach((message: any) => {
            toast.error(message)
          })
        }
      } else {
        toast.error('Failed to update profile')
      }
    } finally {
      isLoading.value = false
    }
  }
}

// Lifecycle
onMounted(() => {
  resetForm()
})
</script>

<style scoped>
.profile-page {
  padding: 1rem;
}

.gap-2 {
  gap: 0.5rem;
}

.gap-4 {
  gap: 1rem;
}
</style>
