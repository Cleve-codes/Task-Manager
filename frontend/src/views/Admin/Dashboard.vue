<template>
  <div class="admin-dashboard">
    <v-row>
      <!-- Page Header -->
      <v-col cols="12">
        <div class="d-flex align-center mb-4">
          <div>
            <h1 class="text-h4 font-weight-bold">Admin Dashboard</h1>
            <p class="text-subtitle-1 text-medium-emphasis">
              Welcome back, {{ authStore.userName }}
            </p>
          </div>
          <v-spacer />
          <v-btn
            color="primary"
            prepend-icon="mdi-refresh"
            @click="refreshData"
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
                  {{ tasksStore.taskStats.total }}
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
                  {{ tasksStore.taskStats.pending }}
                </div>
                <div class="text-subtitle-2">Pending Tasks</div>
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
                  {{ tasksStore.taskStats.inProgress }}
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
                  {{ tasksStore.taskStats.completed }}
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
      <v-col v-if="tasksStore.overdueTasks.length > 0" cols="12">
        <v-alert
          type="error"
          variant="tonal"
          prominent
          border="start"
        >
          <v-alert-title>
            <v-icon class="mr-2">mdi-alert-circle</v-icon>
            {{ tasksStore.overdueTasks.length }} Overdue Task{{ tasksStore.overdueTasks.length > 1 ? 's' : '' }}
          </v-alert-title>
          
          <div class="mt-2">
            <div v-for="task in tasksStore.overdueTasks.slice(0, 3)" :key="task.id" class="mb-1">
              <strong>{{ task.title }}</strong> - Due {{ formatDate(task.deadline!) }}
            </div>
            <div v-if="tasksStore.overdueTasks.length > 3" class="text-caption">
              And {{ tasksStore.overdueTasks.length - 3 }} more...
            </div>
          </div>
          
          <template #append>
            <v-btn
              color="error"
              variant="outlined"
              :to="{ name: 'admin-tasks' }"
            >
              View All
            </v-btn>
          </template>
        </v-alert>
      </v-col>

      <!-- Recent Tasks -->
      <v-col cols="12" lg="8">
        <v-card>
          <v-card-title class="d-flex align-center">
            <v-icon class="mr-2">mdi-clock-outline</v-icon>
            Recent Tasks
            <v-spacer />
            <v-btn
              :to="{ name: 'admin-tasks' }"
              variant="text"
              color="primary"
            >
              View All
            </v-btn>
          </v-card-title>
          
          <v-card-text>
            <div v-if="recentTasks.length === 0" class="text-center pa-4">
              <v-icon size="48" color="grey-lighten-1">
                mdi-format-list-checks
              </v-icon>
              <div class="text-h6 mt-2">No tasks yet</div>
              <div class="text-body-2 text-medium-emphasis">
                Create your first task to get started
              </div>
            </div>
            
            <v-list v-else>
              <v-list-item
                v-for="task in recentTasks"
                :key="task.id"
                class="px-0"
              >
                <template #prepend>
                  <v-icon
                    :color="getStatusColor(task.status)"
                    :icon="getStatusIcon(task.status)"
                  />
                </template>
                
                <v-list-item-title>{{ task.title }}</v-list-item-title>
                <v-list-item-subtitle>
                  Assigned to {{ task.user?.name || 'Unassigned' }} • 
                  {{ timeAgo(task.created_at) }}
                </v-list-item-subtitle>
                
                <template #append>
                  <v-chip
                    :color="getStatusColor(task.status)"
                    size="small"
                  >
                    {{ task.status }}
                  </v-chip>
                </template>
              </v-list-item>
            </v-list>
          </v-card-text>
        </v-card>
      </v-col>

      <!-- Quick Actions -->
      <v-col cols="12" lg="4">
        <v-card>
          <v-card-title>
            <v-icon class="mr-2">mdi-lightning-bolt</v-icon>
            Quick Actions
          </v-card-title>
          
          <v-card-text>
            <div class="d-flex flex-column gap-2">
              <v-btn
                color="primary"
                variant="outlined"
                block
                prepend-icon="mdi-plus"
                :to="{ name: 'admin-tasks' }"
              >
                Create New Task
              </v-btn>
              
              <v-btn
                color="secondary"
                variant="outlined"
                block
                prepend-icon="mdi-account-plus"
                :to="{ name: 'admin-users' }"
              >
                Add New User
              </v-btn>
              
              <v-btn
                color="info"
                variant="outlined"
                block
                prepend-icon="mdi-account-group"
                :to="{ name: 'admin-users' }"
              >
                Manage Users
              </v-btn>
            </div>
          </v-card-text>
        </v-card>
      </v-col>
    </v-row>

    <!-- Loading Overlay -->
    <LoadingSpinner
      v-if="tasksStore.isLoading"
      overlay
      text="Loading dashboard data..."
    />
  </div>
</template>

<script setup lang="ts">
import { computed, onMounted, ref } from 'vue'
import { useAuthStore } from '@/stores/auth'
import { useTasksStore } from '@/stores/tasks'
import { formatDate, timeAgo } from '@/utils/helpers'
import { TASK_STATUS_COLORS, TASK_STATUS_ICONS } from '@/utils/constants'
import LoadingSpinner from '@/components/common/LoadingSpinner.vue'

// Stores
const authStore = useAuthStore()
const tasksStore = useTasksStore()

// State
const isRefreshing = ref(false)

// Computed
const recentTasks = computed(() => {
  return tasksStore.allTasks
    .slice()
    .sort((a, b) => new Date(b.created_at).getTime() - new Date(a.created_at).getTime())
    .slice(0, 5)
})

// Methods
const getStatusColor = (status: string) => {
  return TASK_STATUS_COLORS[status as keyof typeof TASK_STATUS_COLORS] || 'grey'
}

const getStatusIcon = (status: string) => {
  return TASK_STATUS_ICONS[status as keyof typeof TASK_STATUS_ICONS] || 'mdi-help'
}

const refreshData = async () => {
  isRefreshing.value = true
  try {
    await tasksStore.refreshTasks(true)
  } finally {
    isRefreshing.value = false
  }
}

// Lifecycle
onMounted(() => {
  tasksStore.refreshTasks(true)
})
</script>

<style scoped>
.admin-dashboard {
  padding: 1rem;
}
</style>
