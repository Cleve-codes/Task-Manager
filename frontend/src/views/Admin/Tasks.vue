<template>
  <div class="admin-tasks">
    <v-row>
      <!-- Page Header -->
      <v-col cols="12">
        <div class="d-flex align-center mb-4">
          <div>
            <h1 class="text-h4 font-weight-bold">Task Management</h1>
            <p class="text-subtitle-1 text-medium-emphasis">
              Create, assign, and manage all tasks
            </p>
          </div>
        </div>
      </v-col>

      <!-- Tasks List -->
      <v-col cols="12">
        <TasksList
          :tasks="tasksStore.allTasks"
          :users="tasksStore.users"
          :loading="tasksStore.isLoading"
          @create-task="openCreateDialog"
          @edit-task="openEditDialog"
          @delete-task="openDeleteDialog"
        />
      </v-col>
    </v-row>

    <!-- Create/Edit Task Modal -->
    <Modal
      v-model="showTaskDialog"
      :title="isEditMode ? 'Edit Task' : 'Create New Task'"
      max-width="700"
      @close="closeTaskDialog"
    >
      <TaskForm
        :task="selectedTask"
        :users="tasksStore.users"
        :loading="tasksStore.isCreating || tasksStore.isUpdating"
        @submit="handleTaskSubmit"
        @cancel="closeTaskDialog"
      />
    </Modal>

    <!-- Delete Confirmation Modal -->
    <Modal
      v-model="showDeleteDialog"
      title="Delete Task"
      max-width="400"
      show-default-actions
      show-cancel-button
      show-confirm-button
      confirm-text="Delete"
      confirm-color="error"
      :loading="tasksStore.isDeleting"
      @confirm="handleDeleteConfirm"
      @cancel="closeDeleteDialog"
    >
      <div v-if="taskToDelete">
        <p class="mb-3">
          Are you sure you want to delete the task 
          <strong>{{ taskToDelete.title }}</strong>?
        </p>
        
        <v-alert
          type="warning"
          variant="tonal"
          class="mb-3"
        >
          This action cannot be undone.
        </v-alert>
      </div>
    </Modal>

    <!-- Loading Overlay -->
    <LoadingSpinner
      v-if="tasksStore.isLoading && tasksStore.allTasks.length === 0"
      overlay
      text="Loading tasks..."
    />
  </div>
</template>

<script setup lang="ts">
import { ref, computed, onMounted } from 'vue'
import { useTasksStore } from '@/stores/tasks'
import { type Task, type TaskRequest } from '@/services/api'
import TasksList from '@/components/admin/TasksList.vue'
import TaskForm from '@/components/admin/TaskForm.vue'
import Modal from '@/components/common/Modal.vue'
import LoadingSpinner from '@/components/common/LoadingSpinner.vue'

// Store
const tasksStore = useTasksStore()

// Dialog state
const showTaskDialog = ref(false)
const showDeleteDialog = ref(false)
const selectedTask = ref<Task | null>(null)
const taskToDelete = ref<Task | null>(null)

// Computed
const isEditMode = computed(() => !!selectedTask.value)

// Methods
const openCreateDialog = () => {
  selectedTask.value = null
  showTaskDialog.value = true
}

const openEditDialog = (task: Task) => {
  selectedTask.value = task
  showTaskDialog.value = true
}

const openDeleteDialog = (task: Task) => {
  taskToDelete.value = task
  showDeleteDialog.value = true
}

const closeTaskDialog = () => {
  showTaskDialog.value = false
  selectedTask.value = null
}

const closeDeleteDialog = () => {
  showDeleteDialog.value = false
  taskToDelete.value = null
}

const handleTaskSubmit = async (taskData: TaskRequest) => {
  let success = false
  
  if (isEditMode.value && selectedTask.value) {
    // Update existing task
    success = await tasksStore.updateTask(selectedTask.value.id, taskData)
  } else {
    // Create new task
    success = await tasksStore.createTask(taskData)
  }
  
  if (success) {
    closeTaskDialog()
  }
}

const handleDeleteConfirm = async () => {
  if (!taskToDelete.value) return
  
  const success = await tasksStore.deleteTask(taskToDelete.value.id)
  
  if (success) {
    closeDeleteDialog()
  }
}

// Lifecycle
onMounted(() => {
  tasksStore.refreshTasks(true)
})
</script>

<style scoped>
.admin-tasks {
  padding: 1rem;
}
</style>
