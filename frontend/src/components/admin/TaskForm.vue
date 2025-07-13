<template>
  <v-form ref="formRef" @submit.prevent="handleSubmit">
    <v-row>
      <v-col cols="12">
        <v-text-field
          v-model="form.title"
          :rules="titleRules"
          label="Task Title"
          prepend-inner-icon="mdi-format-title"
          variant="outlined"
          :disabled="loading"
          required
        />
      </v-col>

      <v-col cols="12">
        <v-textarea
          v-model="form.description"
          :rules="descriptionRules"
          label="Description"
          prepend-inner-icon="mdi-text"
          variant="outlined"
          :disabled="loading"
          rows="3"
          auto-grow
        />
      </v-col>

      <v-col cols="12" md="6">
        <v-select
          v-model="form.status"
          :items="statusOptions"
          :rules="statusRules"
          label="Status"
          prepend-inner-icon="mdi-flag"
          variant="outlined"
          :disabled="loading"
          required
        />
      </v-col>

      <v-col cols="12" md="6">
        <v-select
          v-model="form.assigned_to"
          :items="userOptions"
          :rules="assignedToRules"
          label="Assign To"
          prepend-inner-icon="mdi-account"
          variant="outlined"
          :disabled="loading"
          required
        />
      </v-col>

      <v-col cols="12">
        <v-text-field
          v-model="form.deadline"
          :rules="deadlineRules"
          label="Deadline"
          type="date"
          prepend-inner-icon="mdi-calendar"
          variant="outlined"
          :disabled="loading"
          :min="minDate"
        />
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
          {{ isEdit ? 'Update' : 'Create' }} Task
        </v-btn>
      </v-col>
    </v-row>
  </v-form>
</template>

<script setup lang="ts">
import { ref, computed, reactive, watch } from 'vue'
import { VALIDATION_RULES, TASK_STATUS } from '@/utils/constants'
import type { Task, TaskRequest, User } from '@/services/api'

// Props
interface Props {
  task?: Task | null
  users: User[]
  loading?: boolean
}

const props = defineProps<Props>()

// Emits
const emit = defineEmits<{
  'submit': [data: TaskRequest]
  'cancel': []
}>()

// Form ref
const formRef = ref()

// Form state
const form = reactive<TaskRequest>({
  title: '',
  description: '',
  status: TASK_STATUS.PENDING,
  assigned_to: 0,
  deadline: '',
})

// Computed
const isEdit = computed(() => !!props.task)

const minDate = computed(() => {
  const today = new Date()
  return today.toISOString().split('T')[0]
})

const statusOptions = [
  { title: 'Pending', value: TASK_STATUS.PENDING },
  { title: 'In Progress', value: TASK_STATUS.IN_PROGRESS },
  { title: 'Completed', value: TASK_STATUS.COMPLETED },
]

const userOptions = computed(() => 
  props.users.map(user => ({
    title: user.name,
    value: user.id,
  }))
)

// Validation rules
const titleRules = [
  VALIDATION_RULES.REQUIRED,
  VALIDATION_RULES.MIN_LENGTH(3),
  VALIDATION_RULES.MAX_LENGTH(100),
]

const descriptionRules = [
  VALIDATION_RULES.MAX_LENGTH(500),
]

const statusRules = [
  VALIDATION_RULES.REQUIRED,
]

const assignedToRules = [
  VALIDATION_RULES.REQUIRED,
  (value: number) => value > 0 || 'Please select a user',
]

const deadlineRules = [
  (value: string) => {
    if (!value) return true // Optional field
    const selectedDate = new Date(value)
    const today = new Date()
    today.setHours(0, 0, 0, 0)
    return selectedDate >= today || 'Deadline cannot be in the past'
  },
]

const isFormValid = computed(() => {
  return form.title && 
         form.title.length >= 3 &&
         form.status &&
         form.assigned_to > 0 &&
         (!form.description || form.description.length <= 500) &&
         (!form.deadline || new Date(form.deadline) >= new Date())
})

// Watch for task prop changes
watch(() => props.task, (newTask) => {
  if (newTask) {
    form.title = newTask.title
    form.description = newTask.description || ''
    form.status = newTask.status
    form.assigned_to = newTask.assigned_to
    form.deadline = newTask.deadline ? newTask.deadline.split('T')[0] : ''
  } else {
    // Reset form for create mode
    form.title = ''
    form.description = ''
    form.status = TASK_STATUS.PENDING
    form.assigned_to = 0
    form.deadline = ''
  }
}, { immediate: true })

// Methods
const handleSubmit = async () => {
  const { valid } = await formRef.value.validate()
  
  if (valid) {
    const submitData = { ...form }
    
    // Convert empty strings to undefined for optional fields
    if (!submitData.description) {
      delete submitData.description
    }
    if (!submitData.deadline) {
      delete submitData.deadline
    }
    
    emit('submit', submitData)
  }
}
</script>
