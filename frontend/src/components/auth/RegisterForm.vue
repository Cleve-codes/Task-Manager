<template>
  <v-form ref="formRef" @submit.prevent="handleSubmit" class="modern-auth-form">
    <!-- Full Name Field -->
    <div class="form-group">
      <label class="form-label">Full Name</label>
      <v-text-field
        v-model="form.name"
        :rules="nameRules"
        prepend-inner-icon="mdi-account-outline"
        variant="outlined"
        class="modern-input"
        :disabled="authStore.isLoading"
        placeholder="Enter your full name"
        hide-details="auto"
        required
      />
    </div>

    <!-- Email Field -->
    <div class="form-group">
      <label class="form-label">Email Address</label>
      <v-text-field
        v-model="form.email"
        :rules="emailRules"
        type="email"
        prepend-inner-icon="mdi-email-outline"
        variant="outlined"
        class="modern-input"
        :disabled="authStore.isLoading"
        placeholder="Enter your email address"
        hide-details="auto"
        required
      />
    </div>

    <!-- Password Field -->
    <div class="form-group">
      <label class="form-label">Password</label>
      <v-text-field
        v-model="form.password"
        :rules="passwordRules"
        :type="showPassword ? 'text' : 'password'"
        prepend-inner-icon="mdi-lock-outline"
        :append-inner-icon="showPassword ? 'mdi-eye-outline' : 'mdi-eye-off-outline'"
        @click:append-inner="showPassword = !showPassword"
        variant="outlined"
        class="modern-input"
        :disabled="authStore.isLoading"
        placeholder="Create a strong password"
        hide-details="auto"
        required
      />
    </div>

    <!-- Confirm Password Field -->
    <div class="form-group">
      <label class="form-label">Confirm Password</label>
      <v-text-field
        v-model="confirmPassword"
        :rules="confirmPasswordRules"
        :type="showConfirmPassword ? 'text' : 'password'"
        prepend-inner-icon="mdi-lock-check-outline"
        :append-inner-icon="showConfirmPassword ? 'mdi-eye-outline' : 'mdi-eye-off-outline'"
        @click:append-inner="showConfirmPassword = !showConfirmPassword"
        variant="outlined"
        class="modern-input"
        :disabled="authStore.isLoading"
        placeholder="Confirm your password"
        hide-details="auto"
        required
      />
    </div>

    <!-- Submit Button -->
    <v-btn
      type="submit"
      color="primary"
      size="x-large"
      block
      :loading="authStore.isLoading"
      :disabled="!isFormValid"
      class="modern-submit-btn"
      elevation="0"
    >
      <span class="submit-text">Create Account</span>
      <v-icon end size="20">mdi-arrow-right</v-icon>
    </v-btn>
  </v-form>
</template>

<script setup lang="ts">
import { ref, computed, reactive } from 'vue'
import { useAuthStore } from '@/stores/auth'
import { VALIDATION_RULES } from '@/utils/constants'
import type { RegisterRequest } from '@/services/api'

// Store
const authStore = useAuthStore()

// Form ref
const formRef = ref()

// Form state
const showPassword = ref(false)
const showConfirmPassword = ref(false)
const confirmPassword = ref('')

const form = reactive<RegisterRequest>({
  name: '',
  email: '',
  password: '',
})

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

const passwordRules = [
  VALIDATION_RULES.REQUIRED,
  VALIDATION_RULES.MIN_LENGTH(8),
]

const confirmPasswordRules = [
  VALIDATION_RULES.REQUIRED,
  (value: string) => value === form.password || 'Passwords do not match',
]

// Computed
const isFormValid = computed(() => {
  return form.name && 
         form.email && 
         form.password && 
         confirmPassword.value &&
         form.password.length >= 8 &&
         confirmPassword.value === form.password &&
         VALIDATION_RULES.EMAIL(form.email) === true
})

// Methods
const handleSubmit = async () => {
  const { valid } = await formRef.value.validate()
  
  if (valid) {
    await authStore.register(form)
  }
}
</script>

<style scoped>
.modern-auth-form {
  padding: 0;
}

.form-group {
  margin-bottom: 24px;
}

.form-label {
  display: block;
  font-size: 14px;
  font-weight: 600;
  color: rgba(255, 255, 255, 0.9);
  margin-bottom: 8px;
  letter-spacing: 0.025em;
}

.modern-input :deep(.v-field) {
  background: rgba(255, 255, 255, 0.08) !important;
  border: 1px solid rgba(255, 255, 255, 0.15) !important;
  border-radius: 12px !important;
  backdrop-filter: blur(10px);
  transition: all 0.3s ease;
}

/* Ensure consistent background for all field states */
.modern-input :deep(.v-field--active),
.modern-input :deep(.v-field--dirty),
.modern-input :deep(.v-field--filled) {
  background: rgba(255, 255, 255, 0.08) !important;
}

.modern-input :deep(.v-field__overlay) {
  background: transparent !important;
}

.modern-input :deep(.v-field--variant-outlined .v-field__overlay) {
  background: transparent !important;
}

.modern-input :deep(.v-field:hover),
.modern-input :deep(.v-field--active:hover),
.modern-input :deep(.v-field--dirty:hover),
.modern-input :deep(.v-field--filled:hover) {
  background: rgba(255, 255, 255, 0.12) !important;
  border-color: rgba(255, 255, 255, 0.25) !important;
  transform: translateY(-1px);
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
}

.modern-input :deep(.v-field--focused),
.modern-input :deep(.v-field--active.v-field--focused),
.modern-input :deep(.v-field--dirty.v-field--focused),
.modern-input :deep(.v-field--filled.v-field--focused) {
  background: rgba(255, 255, 255, 0.15) !important;
  border-color: #5865F2 !important;
  box-shadow: 0 0 0 3px rgba(88, 101, 242, 0.2), 0 4px 12px rgba(0, 0, 0, 0.15) !important;
}

.modern-input :deep(.v-field__input) {
  color: rgba(255, 255, 255, 0.95) !important;
  font-size: 15px;
  padding: 16px 20px;
  min-height: 56px;
}

.modern-input :deep(.v-field__input::placeholder) {
  color: rgba(255, 255, 255, 0.5) !important;
  opacity: 1;
}

.modern-input :deep(.v-field__prepend-inner) {
  padding-left: 20px;
  padding-right: 12px;
}

.modern-input :deep(.v-field__append-inner) {
  padding-right: 20px;
  padding-left: 12px;
}

.modern-input :deep(.v-icon) {
  color: rgba(255, 255, 255, 0.6) !important;
  font-size: 20px;
}

.modern-input :deep(.v-field--focused .v-icon) {
  color: #5865F2 !important;
}

.modern-submit-btn {
  margin-top: 8px;
  height: 56px !important;
  border-radius: 12px !important;
  background: linear-gradient(135deg, #5865F2 0%, #4752C4 100%) !important;
  font-weight: 600 !important;
  font-size: 16px !important;
  letter-spacing: 0.025em;
  text-transform: none !important;
  transition: all 0.3s ease;
  box-shadow: 0 4px 12px rgba(88, 101, 242, 0.3) !important;
}

.modern-submit-btn:hover {
  transform: translateY(-2px);
  box-shadow: 0 8px 20px rgba(88, 101, 242, 0.4) !important;
  background: linear-gradient(135deg, #6B73FF 0%, #5865F2 100%) !important;
}

.modern-submit-btn:active {
  transform: translateY(0);
}

.modern-submit-btn .submit-text {
  margin-right: 8px;
}

.modern-submit-btn :deep(.v-icon) {
  transition: transform 0.2s ease;
}

.modern-submit-btn:hover :deep(.v-icon) {
  transform: translateX(2px);
}

/* Error states */
.modern-input :deep(.v-field--error) {
  border-color: #ED4245 !important;
  background: rgba(237, 66, 69, 0.1) !important;
}

.modern-input :deep(.v-field--error .v-icon) {
  color: #ED4245 !important;
}

/* Loading state */
.modern-submit-btn:disabled {
  opacity: 0.7;
  transform: none !important;
}
</style>
