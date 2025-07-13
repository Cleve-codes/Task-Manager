<template>
  <div class="admin-email-preferences">
    <!-- Page Header -->
    <div class="d-flex align-center justify-space-between mb-6">
      <div>
        <h1 class="text-h4 font-weight-bold">Email Preferences Management</h1>
        <p class="text-subtitle-1 text-medium-emphasis">
          Monitor and manage email notification preferences for all users
        </p>
      </div>
    </div>

    <!-- Email Preferences Overview Component -->
    <EmailPreferencesOverview />
  </div>
</template>

<script setup lang="ts">
import EmailPreferencesOverview from '@/components/admin/EmailPreferencesOverview.vue'
</script>

<style scoped>
.admin-email-preferences {
  padding: 1rem;
}
</style>
