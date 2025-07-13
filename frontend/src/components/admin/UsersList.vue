<template>
  <v-card>
    <v-card-title class="d-flex align-center">
      <v-icon class="mr-2">mdi-account-group</v-icon>
      Users Management
      
      <v-spacer />
      
      <v-btn
        color="primary"
        prepend-icon="mdi-plus"
        @click="$emit('create-user')"
      >
        Add User
      </v-btn>
    </v-card-title>

    <v-card-text>
      <v-data-table
        :headers="headers"
        :items="users"
        :loading="loading"
        item-value="id"
        class="elevation-1"
      >
        <template #item.role="{ item }">
          <v-chip
            :color="item.role === 'admin' ? 'warning' : 'info'"
            size="small"
          >
            {{ item.role.toUpperCase() }}
          </v-chip>
        </template>

        <template #item.created_at="{ item }">
          {{ formatDate(item.created_at) }}
        </template>

        <template #item.actions="{ item }">
          <v-tooltip text="Edit User">
            <template #activator="{ props }">
              <v-btn
                v-bind="props"
                icon="mdi-pencil"
                size="small"
                variant="text"
                @click="$emit('edit-user', item)"
              />
            </template>
          </v-tooltip>

          <v-tooltip text="Delete User">
            <template #activator="{ props }">
              <v-btn
                v-bind="props"
                icon="mdi-delete"
                size="small"
                variant="text"
                color="error"
                @click="$emit('delete-user', item)"
              />
            </template>
          </v-tooltip>
        </template>

        <template #no-data>
          <div class="text-center pa-4">
            <v-icon size="64" color="grey-lighten-1">
              mdi-account-group-outline
            </v-icon>
            <div class="text-h6 mt-2">No users found</div>
            <div class="text-body-2 text-medium-emphasis">
              Create your first user to get started
            </div>
          </div>
        </template>
      </v-data-table>
    </v-card-text>
  </v-card>
</template>

<script setup lang="ts">
import { formatDate } from '@/utils/helpers'
import type { User } from '@/services/api'

// Props
interface Props {
  users: User[]
  loading?: boolean
}

defineProps<Props>()

// Emits
defineEmits<{
  'create-user': []
  'edit-user': [user: User]
  'delete-user': [user: User]
}>()

// Table headers
const headers = [
  { title: 'Name', key: 'name', sortable: true },
  { title: 'Email', key: 'email', sortable: true },
  { title: 'Role', key: 'role', sortable: true },
  { title: 'Created', key: 'created_at', sortable: true },
  { title: 'Actions', key: 'actions', sortable: false, width: 120 },
]
</script>
