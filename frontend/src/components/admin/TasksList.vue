<template>
  <v-card>
    <v-card-title class="d-flex align-center">
      <v-icon class="mr-2">mdi-format-list-checks</v-icon>
      Tasks Management
      
      <v-spacer />
      
      <v-btn
        color="primary"
        prepend-icon="mdi-plus"
        @click="$emit('create-task')"
      >
        Add Task
      </v-btn>
    </v-card-title>

    <v-card-text>
      <!-- Filters -->
      <v-row class="mb-4">
        <v-col cols="12" md="4">
          <v-select
            v-model="statusFilter"
            :items="statusOptions"
            label="Filter by Status"
            variant="outlined"
            clearable
            density="compact"
          />
        </v-col>
        
        <v-col cols="12" md="4">
          <v-select
            v-model="userFilter"
            :items="userOptions"
            label="Filter by User"
            variant="outlined"
            clearable
            density="compact"
          />
        </v-col>
        
        <v-col cols="12" md="4">
          <v-text-field
            v-model="searchQuery"
            label="Search tasks..."
            prepend-inner-icon="mdi-magnify"
            variant="outlined"
            density="compact"
            clearable
          />
        </v-col>
      </v-row>

      <v-data-table
        :headers="headers"
        :items="filteredTasks"
        :loading="loading"
        item-value="id"
        class="elevation-1"
      >
        <template #item.status="{ item }">
          <v-chip
            :color="getStatusColor(item.status)"
            size="small"
          >
            <v-icon :icon="getStatusIcon(item.status)" start />
            {{ item.status }}
          </v-chip>
        </template>

        <template #item.user="{ item }">
          <div v-if="item.user" class="d-flex align-center">
            <v-avatar size="24" color="secondary" class="mr-2">
              <span class="text-caption text-white">
                {{ getInitials(item.user.name) }}
              </span>
            </v-avatar>
            {{ item.user.name }}
          </div>
          <span v-else class="text-medium-emphasis">Unassigned</span>
        </template>

        <template #item.deadline="{ item }">
          <div v-if="item.deadline">
            <div :class="{ 'text-error': isOverdue(item.deadline) }">
              {{ formatDate(item.deadline) }}
            </div>
            <div v-if="isOverdue(item.deadline)" class="text-caption text-error">
              Overdue
            </div>
          </div>
          <span v-else class="text-medium-emphasis">No deadline</span>
        </template>

        <template #item.created_at="{ item }">
          {{ formatDate(item.created_at) }}
        </template>

        <template #item.actions="{ item }">
          <v-tooltip text="Edit Task">
            <template #activator="{ props }">
              <v-btn
                v-bind="props"
                icon="mdi-pencil"
                size="small"
                variant="text"
                @click="$emit('edit-task', item)"
              />
            </template>
          </v-tooltip>
          
          <v-tooltip text="Delete Task">
            <template #activator="{ props }">
              <v-btn
                v-bind="props"
                icon="mdi-delete"
                size="small"
                variant="text"
                color="error"
                @click="$emit('delete-task', item)"
              />
            </template>
          </v-tooltip>
        </template>

        <template #no-data>
          <div class="text-center pa-4">
            <v-icon size="64" color="grey-lighten-1">
              mdi-format-list-checks
            </v-icon>
            <div class="text-h6 mt-2">No tasks found</div>
            <div class="text-body-2 text-medium-emphasis">
              Create your first task to get started
            </div>
          </div>
        </template>
      </v-data-table>
    </v-card-text>
  </v-card>
</template>

<script setup lang="ts">
import { ref, computed } from 'vue'
import { formatDate, getInitials } from '@/utils/helpers'
import { TASK_STATUS, TASK_STATUS_COLORS, TASK_STATUS_ICONS } from '@/utils/constants'
import type { Task, User } from '@/services/api'

// Props
interface Props {
  tasks: Task[]
  users: User[]
  loading?: boolean
}

const props = defineProps<Props>()

// Emits
defineEmits<{
  'create-task': []
  'edit-task': [task: Task]
  'delete-task': [task: Task]
}>()

// State
const statusFilter = ref<string | null>(null)
const userFilter = ref<number | null>(null)
const searchQuery = ref('')

// Computed
const statusOptions = [
  { title: 'All Statuses', value: null },
  { title: 'Pending', value: TASK_STATUS.PENDING },
  { title: 'In Progress', value: TASK_STATUS.IN_PROGRESS },
  { title: 'Completed', value: TASK_STATUS.COMPLETED },
]

const userOptions = computed(() => [
  { title: 'All Users', value: null },
  ...props.users.map(user => ({
    title: user.name,
    value: user.id,
  }))
])

const filteredTasks = computed(() => {
  let filtered = props.tasks

  // Filter by status
  if (statusFilter.value) {
    filtered = filtered.filter(task => task.status === statusFilter.value)
  }

  // Filter by user
  if (userFilter.value) {
    filtered = filtered.filter(task => task.assigned_to === userFilter.value)
  }

  // Search filter
  if (searchQuery.value) {
    const query = searchQuery.value.toLowerCase()
    filtered = filtered.filter(task =>
      task.title.toLowerCase().includes(query) ||
      task.description?.toLowerCase().includes(query) ||
      task.user?.name.toLowerCase().includes(query)
    )
  }

  return filtered
})

// Table headers
const headers = [
  { title: 'Title', key: 'title', sortable: true },
  { title: 'Status', key: 'status', sortable: true },
  { title: 'Assigned To', key: 'user', sortable: false },
  { title: 'Deadline', key: 'deadline', sortable: true },
  { title: 'Created', key: 'created_at', sortable: true },
  { title: 'Actions', key: 'actions', sortable: false, width: 120 },
]

// Methods
const getStatusColor = (status: string) => {
  return TASK_STATUS_COLORS[status as keyof typeof TASK_STATUS_COLORS] || 'grey'
}

const getStatusIcon = (status: string) => {
  return TASK_STATUS_ICONS[status as keyof typeof TASK_STATUS_ICONS] || 'mdi-help'
}

const isOverdue = (deadline: string) => {
  return new Date(deadline) < new Date()
}
</script>
