import { ref, computed } from 'vue'
import { defineStore } from 'pinia'
import { useToast } from 'vue-toastification'
import { apiService, type Task, type TaskRequest, type TaskStatusUpdate, type User } from '@/services/api'
import { TASK_STATUS } from '@/utils/constants'

export const useTasksStore = defineStore('tasks', () => {
  // State
  const tasks = ref<Task[]>([])
  const myTasks = ref<Task[]>([])
  const users = ref<User[]>([])
  const isLoading = ref(false)
  const isCreating = ref(false)
  const isUpdating = ref(false)
  const isDeleting = ref(false)

  // Getters
  const allTasks = computed(() => tasks.value)
  const userTasks = computed(() => myTasks.value)
  
  const tasksByStatus = computed(() => ({
    pending: tasks.value.filter(task => task.status === TASK_STATUS.PENDING),
    inProgress: tasks.value.filter(task => task.status === TASK_STATUS.IN_PROGRESS),
    completed: tasks.value.filter(task => task.status === TASK_STATUS.COMPLETED),
  }))

  const myTasksByStatus = computed(() => ({
    pending: myTasks.value.filter(task => task.status === TASK_STATUS.PENDING),
    inProgress: myTasks.value.filter(task => task.status === TASK_STATUS.IN_PROGRESS),
    completed: myTasks.value.filter(task => task.status === TASK_STATUS.COMPLETED),
  }))

  const taskStats = computed(() => ({
    total: tasks.value.length,
    pending: tasksByStatus.value.pending.length,
    inProgress: tasksByStatus.value.inProgress.length,
    completed: tasksByStatus.value.completed.length,
  }))

  const myTaskStats = computed(() => ({
    total: myTasks.value.length,
    pending: myTasksByStatus.value.pending.length,
    inProgress: myTasksByStatus.value.inProgress.length,
    completed: myTasksByStatus.value.completed.length,
  }))

  const overdueTasks = computed(() => {
    const now = new Date()
    return tasks.value.filter(task => {
      if (!task.deadline || task.status === TASK_STATUS.COMPLETED) return false
      return new Date(task.deadline) < now
    })
  })

  const myOverdueTasks = computed(() => {
    const now = new Date()
    return myTasks.value.filter(task => {
      if (!task.deadline || task.status === TASK_STATUS.COMPLETED) return false
      return new Date(task.deadline) < now
    })
  })

  // Actions
  const toast = useToast()

  // Fetch all tasks (admin)
  const fetchTasks = async (): Promise<void> => {
    try {
      isLoading.value = true
      const response = await apiService.getTasks()
      tasks.value = response.data || []
    } catch (error) {
      console.error('Error fetching tasks:', error)
      toast.error('Failed to load tasks')
    } finally {
      isLoading.value = false
    }
  }

  // Fetch user's tasks
  const fetchMyTasks = async (): Promise<void> => {
    try {
      isLoading.value = true
      const response = await apiService.getMyTasks()
      myTasks.value = response.data || []
    } catch (error) {
      console.error('Error fetching my tasks:', error)
      toast.error('Failed to load your tasks')
    } finally {
      isLoading.value = false
    }
  }

  // Fetch users for task assignment (admin)
  const fetchUsers = async (): Promise<void> => {
    try {
      const usersData = await apiService.getUsers()
      users.value = usersData
    } catch (error) {
      console.error('Error fetching users:', error)
      toast.error('Failed to load users')
    }
  }

  // Create task (admin)
  const createTask = async (taskData: TaskRequest): Promise<boolean> => {
    try {
      isCreating.value = true
      const response = await apiService.createTask(taskData)
      
      if (response.success && response.data) {
        tasks.value.unshift(response.data)
        toast.success('Task created successfully')
        return true
      }
      
      return false
    } catch (error: any) {
      console.error('Error creating task:', error)
      
      if (error.response?.status === 422) {
        const errors = error.response.data?.errors
        if (errors) {
          Object.values(errors).flat().forEach((message: any) => {
            toast.error(message)
          })
        }
      } else {
        toast.error('Failed to create task')
      }
      
      return false
    } finally {
      isCreating.value = false
    }
  }

  // Update task
  const updateTask = async (id: number, taskData: Partial<TaskRequest>): Promise<boolean> => {
    try {
      isUpdating.value = true
      const response = await apiService.updateTask(id, taskData)
      
      if (response.success && response.data) {
        // Update in tasks array
        const taskIndex = tasks.value.findIndex(task => task.id === id)
        if (taskIndex !== -1) {
          tasks.value[taskIndex] = response.data
        }
        
        // Update in myTasks array
        const myTaskIndex = myTasks.value.findIndex(task => task.id === id)
        if (myTaskIndex !== -1) {
          myTasks.value[myTaskIndex] = response.data
        }
        
        toast.success('Task updated successfully')
        return true
      }
      
      return false
    } catch (error: any) {
      console.error('Error updating task:', error)
      
      if (error.response?.status === 422) {
        const errors = error.response.data?.errors
        if (errors) {
          Object.values(errors).flat().forEach((message: any) => {
            toast.error(message)
          })
        }
      } else {
        toast.error('Failed to update task')
      }
      
      return false
    } finally {
      isUpdating.value = false
    }
  }

  // Update task status
  const updateTaskStatus = async (id: number, status: TaskStatusUpdate): Promise<boolean> => {
    try {
      const response = await apiService.updateTaskStatus(id, status)
      
      if (response.success && response.data) {
        // Update in tasks array
        const taskIndex = tasks.value.findIndex(task => task.id === id)
        if (taskIndex !== -1) {
          tasks.value[taskIndex] = response.data
        }
        
        // Update in myTasks array
        const myTaskIndex = myTasks.value.findIndex(task => task.id === id)
        if (myTaskIndex !== -1) {
          myTasks.value[myTaskIndex] = response.data
        }
        
        toast.success(`Task status updated to ${status.status}`)
        return true
      }
      
      return false
    } catch (error) {
      console.error('Error updating task status:', error)
      toast.error('Failed to update task status')
      return false
    }
  }

  // Delete task (admin)
  const deleteTask = async (id: number): Promise<boolean> => {
    try {
      isDeleting.value = true
      const response = await apiService.deleteTask(id)
      
      if (response.success) {
        // Remove from tasks array
        tasks.value = tasks.value.filter(task => task.id !== id)
        
        // Remove from myTasks array
        myTasks.value = myTasks.value.filter(task => task.id !== id)
        
        toast.success('Task deleted successfully')
        return true
      }
      
      return false
    } catch (error) {
      console.error('Error deleting task:', error)
      toast.error('Failed to delete task')
      return false
    } finally {
      isDeleting.value = false
    }
  }

  // Get task by ID
  const getTaskById = (id: number): Task | undefined => {
    return tasks.value.find(task => task.id === id) || 
           myTasks.value.find(task => task.id === id)
  }

  // Clear all data
  const clearTasks = (): void => {
    tasks.value = []
    myTasks.value = []
    users.value = []
  }

  // Refresh tasks based on user role
  const refreshTasks = async (isAdmin: boolean): Promise<void> => {
    if (isAdmin) {
      await Promise.all([fetchTasks(), fetchUsers()])
    } else {
      await fetchMyTasks()
    }
  }

  return {
    // State
    tasks,
    myTasks,
    users,
    isLoading,
    isCreating,
    isUpdating,
    isDeleting,
    
    // Getters
    allTasks,
    userTasks,
    tasksByStatus,
    myTasksByStatus,
    taskStats,
    myTaskStats,
    overdueTasks,
    myOverdueTasks,
    
    // Actions
    fetchTasks,
    fetchMyTasks,
    fetchUsers,
    createTask,
    updateTask,
    updateTaskStatus,
    deleteTask,
    getTaskById,
    clearTasks,
    refreshTasks,
  }
})
