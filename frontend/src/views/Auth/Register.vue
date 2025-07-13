<template>
  <ParticleBackground>
    <v-container fluid class="fill-height">
      <v-row justify="center" align="center" class="fill-height">
        <v-col cols="12" sm="10" md="8" lg="5" xl="4">
          <v-card class="elevation-24 auth-card" rounded="xl">
            <!-- Header Section -->
            <v-card-title class="text-center pa-8 pb-6">
              <div class="auth-header">
                <div class="auth-logo">
                  <v-icon size="48" color="primary">mdi-clipboard-check-multiple</v-icon>
                </div>
                <h1 class="auth-title">Join Task Management</h1>
                <p class="auth-subtitle">Create your account to get started</p>
              </div>
            </v-card-title>

            <!-- Form Section -->
            <v-card-text class="pa-8 pt-0">
              <RegisterForm />
            </v-card-text>

            <!-- Footer Section -->
            <v-card-actions class="pa-8 pt-0">
              <div class="auth-footer">
                <div class="auth-divider">
                  <span class="divider-text">Already have an account?</span>
                </div>
                <v-btn
                  :to="{ name: 'login' }"
                  variant="outlined"
                  color="white"
                  size="large"
                  block
                  class="auth-secondary-btn"
                >
                  Sign In Instead
                </v-btn>
              </div>
            </v-card-actions>
          </v-card>
        </v-col>
      </v-row>
    </v-container>
  </ParticleBackground>
</template>

<script setup lang="ts">
import RegisterForm from '@/components/auth/RegisterForm.vue'
import ParticleBackground from '@/components/common/ParticleBackground.vue'
</script>

<style scoped>
.fill-height {
  min-height: 100vh;
}

.auth-card {
  backdrop-filter: blur(30px);
  background: rgba(0, 0, 0, 0.6) !important;
  border: 1px solid rgba(255, 255, 255, 0.1);
  box-shadow: 0 20px 40px rgba(0, 0, 0, 0.4) !important;
  max-width: 480px;
  margin: 0 auto;
}

.auth-card .v-card-title,
.auth-card .v-card-text,
.auth-card .v-card-actions {
  background: transparent !important;
}

.auth-header {
  text-align: center;
}

.auth-logo {
  margin-bottom: 24px;
}

.auth-title {
  font-size: 32px;
  font-weight: 700;
  color: rgba(255, 255, 255, 0.95);
  margin-bottom: 8px;
  letter-spacing: -0.025em;
}

.auth-subtitle {
  font-size: 16px;
  color: rgba(255, 255, 255, 0.7);
  margin: 0;
  font-weight: 400;
}

.auth-footer {
  width: 100%;
}

.auth-divider {
  text-align: center;
  margin: 24px 0;
  position: relative;
}

.auth-divider::before {
  content: '';
  position: absolute;
  top: 50%;
  left: 0;
  right: 0;
  height: 1px;
  background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
}

.divider-text {
  background: rgba(0, 0, 0, 0.6);
  padding: 0 16px;
  font-size: 14px;
  color: rgba(255, 255, 255, 0.6);
  position: relative;
  z-index: 1;
}

.auth-secondary-btn {
  border: 1px solid rgba(255, 255, 255, 0.2) !important;
  background: rgba(255, 255, 255, 0.05) !important;
  color: rgba(255, 255, 255, 0.9) !important;
  height: 48px !important;
  border-radius: 12px !important;
  font-weight: 500 !important;
  text-transform: none !important;
  transition: all 0.3s ease;
}

.auth-secondary-btn:hover {
  background: rgba(255, 255, 255, 0.1) !important;
  border-color: rgba(255, 255, 255, 0.3) !important;
  transform: translateY(-1px);
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
}

/* Responsive adjustments */
@media (max-width: 600px) {
  .auth-card {
    margin: 16px;
    border-radius: 16px !important;
  }

  .auth-title {
    font-size: 28px;
  }

  .v-card-title {
    padding: 24px 24px 16px !important;
  }

  .v-card-text,
  .v-card-actions {
    padding: 24px !important;
  }

  .v-card-actions {
    padding-top: 0 !important;
  }
}
</style>
