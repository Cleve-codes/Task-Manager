<template>
  <v-container fluid class="fill-height">
    <v-row justify="center" align="center" class="fill-height">
      <v-col cols="12" sm="8" md="6" lg="4" class="text-center">
        <div class="error-content">
          <!-- 404 Icon -->
          <v-icon
            size="120"
            color="primary"
            class="mb-4"
          >
            mdi-file-question-outline
          </v-icon>

          <!-- Error Code -->
          <h1 class="text-h1 font-weight-bold text-primary mb-2">
            404
          </h1>

          <!-- Error Message -->
          <h2 class="text-h4 font-weight-medium mb-4">
            Page Not Found
          </h2>

          <p class="text-body-1 text-medium-emphasis mb-6">
            The page you're looking for doesn't exist or has been moved.
            Don't worry, it happens to the best of us!
          </p>

          <!-- Action Buttons -->
          <div class="d-flex flex-column flex-sm-row justify-center gap-3">
            <v-btn
              color="primary"
              size="large"
              prepend-icon="mdi-home"
              @click="goHome"
            >
              Go Home
            </v-btn>

            <v-btn
              variant="outlined"
              size="large"
              prepend-icon="mdi-arrow-left"
              @click="goBack"
            >
              Go Back
            </v-btn>
          </div>

          <!-- Additional Help -->
          <div class="mt-8">
            <p class="text-body-2 text-medium-emphasis mb-3">
              Need help? Here are some useful links:
            </p>

            <div class="d-flex justify-center gap-4">
              <v-btn
                v-if="authStore.isAuthenticated && authStore.isAdmin"
                variant="text"
                size="small"
                :to="{ name: 'admin-dashboard' }"
              >
                Admin Dashboard
              </v-btn>

              <v-btn
                v-if="authStore.isAuthenticated && !authStore.isAdmin"
                variant="text"
                size="small"
                :to="{ name: 'user-dashboard' }"
              >
                My Tasks
              </v-btn>

              <v-btn
                v-if="!authStore.isAuthenticated"
                variant="text"
                size="small"
                :to="{ name: 'login' }"
              >
                Login
              </v-btn>
            </div>
          </div>
        </div>
      </v-col>
    </v-row>
  </v-container>
</template>

<script setup lang="ts">
import { useRouter } from 'vue-router'
import { useAuthStore } from '@/stores/auth'
import { ROUTE_NAMES } from '@/utils/constants'

// Router and Store
const router = useRouter()
const authStore = useAuthStore()

// Methods
const goHome = () => {
  if (authStore.isAuthenticated) {
    if (authStore.isAdmin) {
      router.push({ name: ROUTE_NAMES.ADMIN_DASHBOARD })
    } else {
      router.push({ name: ROUTE_NAMES.USER_DASHBOARD })
    }
  } else {
    router.push({ name: ROUTE_NAMES.LOGIN })
  }
}

const goBack = () => {
  if (window.history.length > 1) {
    router.go(-1)
  } else {
    goHome()
  }
}
</script>

<style scoped>
.fill-height {
  min-height: 100vh;
  background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
}

.error-content {
  max-width: 500px;
  margin: 0 auto;
}

.gap-3 {
  gap: 0.75rem;
}

.gap-4 {
  gap: 1rem;
}

@media (max-width: 600px) {
  .text-h1 {
    font-size: 4rem !important;
  }
  
  .text-h4 {
    font-size: 1.5rem !important;
  }
}
</style>
