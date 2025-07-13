<template>
  <v-menu offset-y>
    <template #activator="{ props }">
      <v-btn
        v-bind="props"
        :color="currentStatusColor"
        :loading="loading"
        variant="outlined"
        size="small"
        :disabled="task.status === TASK_STATUS.COMPLETED"
      >
        <v-icon :icon="currentStatusIcon" start />
        Update Status
      </v-btn>
    </template>

    <v-list>
      <v-list-item
        v-for="status in availableStatuses"
        :key="status.value"
        :disabled="status.value === task.status"
        @click="handleStatusUpdate(status.value)"
      >
        <template #prepend>
          <v-icon
            :icon="status.icon"
            :color="status.color"
          />
        </template>
        
        <v-list-item-title>
          {{ status.title }}
        </v-list-item-title>
        
        <template #append>
          <v-icon
            v-if="status.value === task.status"
            icon="mdi-check"
            color="success"
          />
        </template>
      </v-list-item>
    </v-list>
  </v-menu>
</template>

<script setup lang="ts">
import { computed } from 'vue'
import { TASK_STATUS, TASK_STATUS_COLORS, TASK_STATUS_ICONS } from '@/utils/constants'
import type { Task, TaskStatusUpdate } from '@/services/api'

// Props
interface Props {
  task: Task
  loading?: boolean
}

const props = defineProps<Props>()

// Emits
const emit = defineEmits<{
  'update-status': [data: { id: number; status: TaskStatusUpdate }]
}>()

// Computed
const currentStatusColor = computed(() => {
  return TASK_STATUS_COLORS[props.task.status as keyof typeof TASK_STATUS_COLORS] || 'grey'
})

const currentStatusIcon = computed(() => {
  return TASK_STATUS_ICONS[props.task.status as keyof typeof TASK_STATUS_ICONS] || 'mdi-help'
})

const availableStatuses = computed(() => {
  const statuses = [
    {
      title: 'Pending',
      value: TASK_STATUS.PENDING,
      color: TASK_STATUS_COLORS[TASK_STATUS.PENDING],
      icon: TASK_STATUS_ICONS[TASK_STATUS.PENDING],
    },
    {
      title: 'In Progress',
      value: TASK_STATUS.IN_PROGRESS,
      color: TASK_STATUS_COLORS[TASK_STATUS.IN_PROGRESS],
      icon: TASK_STATUS_ICONS[TASK_STATUS.IN_PROGRESS],
    },
    {
      title: 'Completed',
      value: TASK_STATUS.COMPLETED,
      color: TASK_STATUS_COLORS[TASK_STATUS.COMPLETED],
      icon: TASK_STATUS_ICONS[TASK_STATUS.COMPLETED],
    },
  ]

  // Filter based on current status to show logical transitions
  const currentStatus = props.task.status
  
  if (currentStatus === TASK_STATUS.PENDING) {
    return statuses.filter(s => 
      s.value === TASK_STATUS.PENDING || 
      s.value === TASK_STATUS.IN_PROGRESS
    )
  } else if (currentStatus === TASK_STATUS.IN_PROGRESS) {
    return statuses.filter(s => 
      s.value === TASK_STATUS.IN_PROGRESS || 
      s.value === TASK_STATUS.COMPLETED ||
      s.value === TASK_STATUS.PENDING
    )
  } else {
    // Completed - show all for reference but disable in template
    return statuses
  }
})

// Methods
const handleStatusUpdate = (status: string) => {
  if (status !== props.task.status) {
    emit('update-status', {
      id: props.task.id,
      status: { status } as TaskStatusUpdate
    })
  }
}
</script>
