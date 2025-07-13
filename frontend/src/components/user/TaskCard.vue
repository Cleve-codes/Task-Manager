<template>
  <v-card
    :class="[
      'task-card',
      {
        'task-card--overdue': isOverdue,
        'task-card--completed': task.status === TASK_STATUS.COMPLETED
      }
    ]"
    :elevation="2"
  >
    <v-card-title class="d-flex align-center">
      <v-icon
        :icon="statusIcon"
        :color="statusColor"
        class="mr-2"
      />
      
      <span class="text-truncate">{{ task.title }}</span>
      
      <v-spacer />
      
      <v-chip
        :color="statusColor"
        size="small"
        variant="outlined"
      >
        {{ task.status }}
      </v-chip>
    </v-card-title>

    <v-card-text>
      <div v-if="task.description" class="mb-3">
        <p class="text-body-2">{{ task.description }}</p>
      </div>

      <div class="d-flex align-center justify-space-between">
        <div v-if="task.deadline" class="d-flex align-center">
          <v-icon
            :color="isOverdue ? 'error' : 'primary'"
            size="small"
            class="mr-1"
          >
            mdi-calendar
          </v-icon>
          <span
            :class="[
              'text-caption',
              { 'text-error': isOverdue }
            ]"
          >
            {{ formatDate(task.deadline) }}
            <span v-if="isOverdue" class="font-weight-bold">
              (Overdue)
            </span>
          </span>
        </div>

        <div class="text-caption text-medium-emphasis">
          Created {{ timeAgo(task.created_at) }}
        </div>
      </div>
    </v-card-text>

    <v-card-actions class="pt-0">
      <TaskStatusButton
        :task="task"
        :loading="loading"
        @update-status="$emit('update-status', $event)"
      />
      
      <v-spacer />
      
      <v-btn
        icon="mdi-information-outline"
        variant="text"
        size="small"
        @click="showDetails = !showDetails"
      />
    </v-card-actions>

    <v-expand-transition>
      <div v-show="showDetails">
        <v-divider />
        <v-card-text class="pt-3">
          <div class="text-caption text-medium-emphasis mb-2">
            Task Details
          </div>
          
          <div class="d-flex flex-column gap-2">
            <div class="d-flex justify-space-between">
              <span class="font-weight-medium">Status:</span>
              <v-chip
                :color="statusColor"
                size="x-small"
              >
                {{ task.status }}
              </v-chip>
            </div>
            
            <div class="d-flex justify-space-between">
              <span class="font-weight-medium">Created:</span>
              <span class="text-caption">{{ formatDate(task.created_at) }}</span>
            </div>
            
            <div class="d-flex justify-space-between">
              <span class="font-weight-medium">Updated:</span>
              <span class="text-caption">{{ formatDate(task.updated_at) }}</span>
            </div>
            
            <div v-if="task.deadline" class="d-flex justify-space-between">
              <span class="font-weight-medium">Deadline:</span>
              <span
                :class="[
                  'text-caption',
                  { 'text-error font-weight-bold': isOverdue }
                ]"
              >
                {{ formatDate(task.deadline) }}
              </span>
            </div>
          </div>
        </v-card-text>
      </div>
    </v-expand-transition>
  </v-card>
</template>

<script setup lang="ts">
import { ref, computed } from 'vue'
import { formatDate, timeAgo } from '@/utils/helpers'
import { TASK_STATUS, TASK_STATUS_COLORS, TASK_STATUS_ICONS } from '@/utils/constants'
import TaskStatusButton from './TaskStatusButton.vue'
import type { Task, TaskStatusUpdate } from '@/services/api'

// Props
interface Props {
  task: Task
  loading?: boolean
}

const props = defineProps<Props>()

// Emits
defineEmits<{
  'update-status': [data: { id: number; status: TaskStatusUpdate }]
}>()

// State
const showDetails = ref(false)

// Computed
const statusColor = computed(() => {
  return TASK_STATUS_COLORS[props.task.status as keyof typeof TASK_STATUS_COLORS] || 'grey'
})

const statusIcon = computed(() => {
  return TASK_STATUS_ICONS[props.task.status as keyof typeof TASK_STATUS_ICONS] || 'mdi-help'
})

const isOverdue = computed(() => {
  if (!props.task.deadline || props.task.status === TASK_STATUS.COMPLETED) {
    return false
  }
  return new Date(props.task.deadline) < new Date()
})
</script>

<style scoped>
.task-card {
  transition: all 0.3s ease;
}

.task-card:hover {
  transform: translateY(-2px);
}

.task-card--overdue {
  border-left: 4px solid rgb(var(--v-theme-error));
}

.task-card--completed {
  opacity: 0.8;
}

.task-card--completed .v-card-title {
  text-decoration: line-through;
}
</style>
