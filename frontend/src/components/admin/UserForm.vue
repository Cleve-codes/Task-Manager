<template>
  <v-form ref="formRef" @submit.prevent="handleSubmit">
    <v-row>
      <v-col cols="12">
        <v-text-field
          v-model="form.name"
          :rules="nameRules"
          label="Full Name"
          prepend-inner-icon="mdi-account"
          variant="outlined"
          :disabled="loading"
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
          :disabled="loading"
          required
        />
      </v-col>

      <v-col cols="12">
        <v-select
          v-model="form.role"
          :items="roleOptions"
          :rules="roleRules"
          label="Role"
          prepend-inner-icon="mdi-shield-account"
          variant="outlined"
          :disabled="loading"
          required
        />
      </v-col>

      <v-col v-if="!isEdit" cols="12">
        <v-text-field
          v-model="form.password"
          :rules="passwordRules"
          :type="showPassword ? 'text' : 'password'"
          label="Password"
          prepend-inner-icon="mdi-lock"
          :append-inner-icon="showPassword ? 'mdi-eye' : 'mdi-eye-off'"
          @click:append-inner="showPassword = !showPassword"
          variant="outlined"
          :disabled="loading"
          required
        />
      </v-col>

      <!-- Password updates removed for security reasons -->
      <v-col v-if="isEdit" cols="12">
        <v-alert
          type="info"
          variant="tonal"
          class="mb-0"
        >
          <div class="d-flex align-center">
            <v-icon class="mr-2">mdi-information</v-icon>
            <div>
              <div class="font-weight-medium">Password Security</div>
              <div class="text-body-2">
                For security reasons, users must update their own passwords through their profile settings.
              </div>
            </div>
          </div>
        </v-alert>
      </v-col>
    </v-row>

    <v-row class="mt-4">
      <v-col cols="12" class="d-flex justify-end gap-2">
        <v-btn
          variant="outlined"
          @click="$emit('cancel')"
          :disabled="loading"
        >
          Cancel
        </v-btn>
        
        <v-btn
          type="submit"
          color="primary"
          :loading="loading"
          :disabled="!isFormValid"
        >
          {{ isEdit ? 'Update' : 'Create' }} User
        </v-btn>
      </v-col>
    </v-row>
  </v-form>
</template>

<script setup lang="ts">
import { ref, computed, reactive, watch } from 'vue'
import { VALIDATION_RULES, USER_ROLES } from '@/utils/constants'
import type { User, UserRequest } from '@/services/api'

// Props
interface Props {
  user?: User | null
  loading?: boolean
}

const props = defineProps<Props>()

// Emits
const emit = defineEmits<{
  'submit': [data: UserRequest]
  'cancel': []
}>()

// Form ref
const formRef = ref()

// Form state
const showPassword = ref(false)
const form = reactive<UserRequest>({
  name: '',
  email: '',
  role: 'user',
  password: '',
})

// Computed
const isEdit = computed(() => !!props.user)

const roleOptions = [
  { title: 'User', value: USER_ROLES.USER },
  { title: 'Admin', value: USER_ROLES.ADMIN },
]

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

const roleRules = [
  VALIDATION_RULES.REQUIRED,
]

const passwordRules = [
  VALIDATION_RULES.REQUIRED,
  VALIDATION_RULES.MIN_LENGTH(8),
]

const isFormValid = computed(() => {
  const baseValid = form.name &&
                   form.email &&
                   form.role &&
                   VALIDATION_RULES.EMAIL(form.email) === true

  if (isEdit.value) {
    // For edit mode, password is not required (removed for security)
    return baseValid
  }

  // For create mode, password is required
  return baseValid && form.password && form.password.length >= 8
})

// Watch for user prop changes
watch(() => props.user, (newUser) => {
  if (newUser) {
    form.name = newUser.name
    form.email = newUser.email
    form.role = newUser.role
    form.password = ''
  } else {
    // Reset form for create mode
    form.name = ''
    form.email = ''
    form.role = 'user'
    form.password = ''
  }
}, { immediate: true })

// Methods
const handleSubmit = async () => {
  const { valid } = await formRef.value.validate()

  if (valid) {
    const submitData = { ...form }

    // Remove password completely in edit mode for security
    if (isEdit.value) {
      delete submitData.password
    }

    emit('submit', submitData)
  }
}
</script>
