<template>
  <div class="admin-users">
    <v-row>
      <!-- Page Header -->
      <v-col cols="12">
        <div class="d-flex align-center mb-4">
          <div>
            <h1 class="text-h4 font-weight-bold">User Management</h1>
            <p class="text-subtitle-1 text-medium-emphasis">
              Manage system users and their roles
            </p>
          </div>
        </div>
      </v-col>

      <!-- Users List -->
      <v-col cols="12">
        <UsersList
          :users="users"
          :loading="isLoading"
          @create-user="openCreateDialog"
          @edit-user="openEditDialog"
          @delete-user="openDeleteDialog"
        />
      </v-col>
    </v-row>

    <!-- Create/Edit User Modal -->
    <Modal
      v-model="showUserDialog"
      :title="isEditMode ? 'Edit User' : 'Create New User'"
      max-width="600"
      @close="closeUserDialog"
    >
      <UserForm
        :user="selectedUser"
        :loading="isSubmitting"
        @submit="handleUserSubmit"
        @cancel="closeUserDialog"
      />
    </Modal>

    <!-- Delete Confirmation Modal -->
    <Modal
      v-model="showDeleteDialog"
      title="Delete User"
      max-width="400"
      show-default-actions
      show-cancel-button
      show-confirm-button
      confirm-text="Delete"
      confirm-color="error"
      :loading="isDeleting"
      @confirm="handleDeleteConfirm"
      @cancel="closeDeleteDialog"
    >
      <div v-if="userToDelete">
        <p class="mb-3">
          Are you sure you want to delete the user 
          <strong>{{ userToDelete.name }}</strong>?
        </p>
        
        <v-alert
          type="warning"
          variant="tonal"
          class="mb-3"
        >
          This action cannot be undone. All tasks assigned to this user will need to be reassigned.
        </v-alert>
      </div>
    </Modal>

    <!-- Loading Overlay -->
    <LoadingSpinner
      v-if="isLoading && users.length === 0"
      overlay
      text="Loading users..."
    />
  </div>
</template>

<script setup lang="ts">
import { ref, computed, onMounted } from 'vue'
import { useToast } from 'vue-toastification'
import { apiService, type User, type UserRequest } from '@/services/api'
import UsersList from '@/components/admin/UsersList.vue'
import UserForm from '@/components/admin/UserForm.vue'
import Modal from '@/components/common/Modal.vue'
import LoadingSpinner from '@/components/common/LoadingSpinner.vue'

// State
const users = ref<User[]>([])
const isLoading = ref(false)
const isSubmitting = ref(false)
const isDeleting = ref(false)

// Dialog state
const showUserDialog = ref(false)
const showDeleteDialog = ref(false)
const selectedUser = ref<User | null>(null)
const userToDelete = ref<User | null>(null)

// Toast
const toast = useToast()

// Computed
const isEditMode = computed(() => !!selectedUser.value)

// Methods
const fetchUsers = async () => {
  try {
    isLoading.value = true
    users.value = await apiService.getUsers()
  } catch (error) {
    console.error('Error fetching users:', error)
    toast.error('Failed to load users')
  } finally {
    isLoading.value = false
  }
}

const openCreateDialog = () => {
  selectedUser.value = null
  showUserDialog.value = true
}

const openEditDialog = (user: User) => {
  selectedUser.value = user
  showUserDialog.value = true
}

const openDeleteDialog = (user: User) => {
  userToDelete.value = user
  showDeleteDialog.value = true
}

const closeUserDialog = () => {
  showUserDialog.value = false
  selectedUser.value = null
}

const closeDeleteDialog = () => {
  showDeleteDialog.value = false
  userToDelete.value = null
}

const handleUserSubmit = async (userData: UserRequest) => {
  try {
    isSubmitting.value = true
    
    if (isEditMode.value && selectedUser.value) {
      // Update existing user
      const updatedUser = await apiService.updateUser(selectedUser.value.id, userData)
      const index = users.value.findIndex(u => u.id === selectedUser.value!.id)
      if (index !== -1) {
        users.value[index] = updatedUser
      }
      toast.success('User updated successfully')
    } else {
      // Create new user
      const newUser = await apiService.createUser(userData)
      users.value.unshift(newUser)
      toast.success('User created successfully')
    }
    
    closeUserDialog()
  } catch (error: any) {
    console.error('Error saving user:', error)
    
    if (error.response?.status === 422) {
      const errors = error.response.data?.errors
      if (errors) {
        Object.values(errors).flat().forEach((message: any) => {
          toast.error(message)
        })
      }
    } else {
      toast.error('Failed to save user')
    }
  } finally {
    isSubmitting.value = false
  }
}

const handleDeleteConfirm = async () => {
  if (!userToDelete.value) return
  
  try {
    isDeleting.value = true
    await apiService.deleteUser(userToDelete.value.id)
    
    users.value = users.value.filter(u => u.id !== userToDelete.value!.id)
    toast.success('User deleted successfully')
    closeDeleteDialog()
  } catch (error) {
    console.error('Error deleting user:', error)
    toast.error('Failed to delete user')
  } finally {
    isDeleting.value = false
  }
}

// Lifecycle
onMounted(() => {
  fetchUsers()
})
</script>

<style scoped>
.admin-users {
  padding: 1rem;
}
</style>
