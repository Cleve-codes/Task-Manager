<template>
  <div class="user-dashboard">
    <v-row>
      <!-- Page Header -->
      <v-col cols="12">
        <div class="d-flex align-center mb-4">
          <div>
            <h1 class="text-h4 font-weight-bold">My Tasks</h1>
            <p class="text-subtitle-1 text-medium-emphasis">
              Welcome back, {{ authStore.userName }}
            </p>
          </div>
          <v-spacer />
          <v-btn
            color="primary"
            prepend-icon="mdi-refresh"
            @click="refreshTasks"
            :loading="isRefreshing"
          >
            Refresh
          </v-btn>
        </div>
      </v-col>

      <!-- Statistics Cards -->
      <v-col cols="12" sm="6" md="3">
        <v-card color="primary" dark>
          <v-card-text>
            <div class="d-flex align-center">
              <div>
                <div class="text-h4 font-weight-bold">
                  {{ tasksStore.myTaskStats.total }}
                </div>
                <div class="text-subtitle-2">Total Tasks</div>
              </div>
              <v-spacer />
              <v-icon size="40">mdi-format-list-checks</v-icon>
            </div>
          </v-card-text>
        </v-card>
      </v-col>

      <v-col cols="12" sm="6" md="3">
        <v-card color="warning" dark>
          <v-card-text>
            <div class="d-flex align-center">
              <div>
                <div class="text-h4 font-weight-bold">
                  {{ tasksStore.myTaskStats.pending }}
                </div>
                <div class="text-subtitle-2">Pending</div>
              </div>
              <v-spacer />
              <v-icon size="40">mdi-clock-outline</v-icon>
            </div>
          </v-card-text>
        </v-card>
      </v-col>

      <v-col cols="12" sm="6" md="3">
        <v-card color="info" dark>
          <v-card-text>
            <div class="d-flex align-center">
              <div>
                <div class="text-h4 font-weight-bold">
                  {{ tasksStore.myTaskStats.inProgress }}
                </div>
                <div class="text-subtitle-2">In Progress</div>
              </div>
              <v-spacer />
              <v-icon size="40">mdi-progress-clock</v-icon>
            </div>
          </v-card-text>
        </v-card>
      </v-col>

      <v-col cols="12" sm="6" md="3">
        <v-card color="success" dark>
          <v-card-text>
            <div class="d-flex align-center">
              <div>
                <div class="text-h4 font-weight-bold">
                  {{ tasksStore.myTaskStats.completed }}
                </div>
                <div class="text-subtitle-2">Completed</div>
              </div>
              <v-spacer />
              <v-icon size="40">mdi-check-circle</v-icon>
            </div>
          </v-card-text>
        </v-card>
      </v-col>

      <!-- Overdue Tasks Alert -->
      <v-col v-if="tasksStore.myOverdueTasks.length > 0" cols="12">
        <v-alert
          type="error"
          variant="tonal"
          prominent
          border="start"
        >
          <v-alert-title>
            <v-icon class="mr-2">mdi-alert-circle</v-icon>
            You have {{ tasksStore.myOverdueTasks.length }} overdue task{{ tasksStore.myOverdueTasks.length > 1 ? 's' : '' }}
          </v-alert-title>
          
          <div class="mt-2">
            <div v-for="task in tasksStore.myOverdueTasks.slice(0, 3)" :key="task.id" class="mb-1">
              <strong>{{ task.title }}</strong> - Due {{ formatDate(task.deadline!) }}
            </div>
            <div v-if="tasksStore.myOverdueTasks.length > 3" class="text-caption">
              And {{ tasksStore.myOverdueTasks.length - 3 }} more...
            </div>
          </div>
        </v-alert>
      </v-col>

      <!-- Task Filters -->
      <v-col cols="12">
        <v-card>
          <v-card-text>
            <v-row>
              <v-col cols="12" md="6">
                <v-select
                  v-model="statusFilter"
                  :items="statusFilterOptions"
                  label="Filter by Status"
                  variant="outlined"
                  density="compact"
                  clearable
                />
              </v-col>
              
              <v-col cols="12" md="6">
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
          </v-card-text>
        </v-card>
      </v-col>

      <!-- Tasks Grid -->
      <v-col cols="12">
        <div v-if="filteredTasks.length === 0" class="text-center pa-8">
          <v-icon size="64" color="grey-lighten-1">
            mdi-format-list-checks
          </v-icon>
          <div class="text-h6 mt-2">
            {{ tasksStore.userTasks.length === 0 ? 'No tasks assigned' : 'No tasks match your filters' }}
          </div>
          <div class="text-body-2 text-medium-emphasis">
            {{ tasksStore.userTasks.length === 0 ? 'Tasks will appear here when assigned to you' : 'Try adjusting your search or filters' }}
          </div>
        </div>

        <v-row v-else>
          <v-col
            v-for="task in filteredTasks"
            :key="task.id"
            cols="12"
            md="6"
            lg="4"
          >
            <TaskCard
              :task="task"
              :loading="updatingTaskId === task.id"
              @update-status="handleStatusUpdate"
            />
          </v-col>
        </v-row>
      </v-col>
    </v-row>

    <!-- Loading Overlay -->
    <LoadingSpinner
      v-if="tasksStore.isLoading && tasksStore.userTasks.length === 0"
      overlay
      text="Loading your tasks..."
    />
  </div>
</template>

<script setup lang="ts">
import { ref, computed, onMounted } from 'vue'
import { useAuthStore } from '@/stores/auth'
import { useTasksStore } from '@/stores/tasks'
import { formatDate } from '@/utils/helpers'
import { TASK_STATUS } from '@/utils/constants'
import TaskCard from '@/components/user/TaskCard.vue'
import LoadingSpinner from '@/components/common/LoadingSpinner.vue'
import type { TaskStatusUpdate } from '@/services/api'

// Stores
const authStore = useAuthStore()
const tasksStore = useTasksStore()

// State
const isRefreshing = ref(false)
const statusFilter = ref<string | null>(null)
const searchQuery = ref('')
const updatingTaskId = ref<number | null>(null)

// Computed
const statusFilterOptions = [
  { title: 'All Tasks', value: null },
  { title: 'Pending', value: TASK_STATUS.PENDING },
  { title: 'In Progress', value: TASK_STATUS.IN_PROGRESS },
  { title: 'Completed', value: TASK_STATUS.COMPLETED },
]

const filteredTasks = computed(() => {
  let filtered = tasksStore.userTasks

  // Filter by status
  if (statusFilter.value) {
    filtered = filtered.filter(task => task.status === statusFilter.value)
  }

  // Search filter
  if (searchQuery.value) {
    const query = searchQuery.value.toLowerCase()
    filtered = filtered.filter(task =>
      task.title.toLowerCase().includes(query) ||
      task.description?.toLowerCase().includes(query)
    )
  }

  // Sort by deadline (overdue first, then by deadline, then by created date)
  return filtered.sort((a, b) => {
    const now = new Date()
    const aOverdue = a.deadline && new Date(a.deadline) < now
    const bOverdue = b.deadline && new Date(b.deadline) < now
    
    if (aOverdue && !bOverdue) return -1
    if (!aOverdue && bOverdue) return 1
    
    if (a.deadline && b.deadline) {
      return new Date(a.deadline).getTime() - new Date(b.deadline).getTime()
    }
    
    if (a.deadline && !b.deadline) return -1
    if (!a.deadline && b.deadline) return 1
    
    return new Date(b.created_at).getTime() - new Date(a.created_at).getTime()
  })
})

// Methods
const refreshTasks = async () => {
  isRefreshing.value = true
  try {
    await tasksStore.fetchMyTasks()
  } finally {
    isRefreshing.value = false
  }
}

const handleStatusUpdate = async ({ id, status }: { id: number; status: TaskStatusUpdate }) => {
  updatingTaskId.value = id
  try {
    await tasksStore.updateTaskStatus(id, status)
  } finally {
    updatingTaskId.value = null
  }
}

// Lifecycle
onMounted(() => {
  tasksStore.fetchMyTasks()
})
</script>

<style scoped>
.user-dashboard {
  padding: 1rem;
}
</style>
