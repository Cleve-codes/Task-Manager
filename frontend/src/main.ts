import './assets/main.css'
import '@mdi/font/css/materialdesignicons.css'

import { createApp } from 'vue'
import { createPinia } from 'pinia'
import { createVuetify } from 'vuetify'
import * as components from 'vuetify/components'
import * as directives from 'vuetify/directives'
import 'vuetify/styles'
import Toast from 'vue-toastification'
import 'vue-toastification/dist/index.css'
import Particles from '@tsparticles/vue3'
import { loadSlim } from '@tsparticles/slim'

import App from './App.vue'
import router from './router'

// Vuetify configuration
const vuetify = createVuetify({
  components,
  directives,
  theme: {
    defaultTheme: 'light',
    themes: {
      light: {
        colors: {
          // Modern light theme with good contrast
          primary: '#5865F2',           // Consistent with dark theme
          secondary: '#6C757D',         // Professional gray
          accent: '#00D4AA',            // Consistent teal accent
          error: '#DC3545',             // Bootstrap-inspired red
          info: '#0DCAF0',              // Light blue for info
          success: '#198754',           // Professional green
          warning: '#FFC107',           // Warm amber
          background: '#FFFFFF',        // Pure white background
          surface: '#FFFFFF',           // White surface
          'surface-variant': '#F8F9FA', // Very light gray for variety
          'on-background': '#212529',   // Dark text on light background
          'on-surface': '#212529',      // Dark text on surface
          'on-primary': '#FFFFFF',      // White text on primary
          'on-secondary': '#FFFFFF',    // White text on secondary
          'surface-bright': '#F8F9FA',  // Bright surface
          'surface-light': '#FFFFFF',   // Light surface
          'surface-container': '#F8F9FA', // Container surface
          'surface-container-high': '#E9ECEF', // High emphasis container
          'surface-container-highest': '#DEE2E6', // Highest emphasis container
          outline: '#DEE2E6',           // Subtle borders
          'outline-variant': '#E9ECEF', // Even more subtle borders
        },
      },
      dark: {
        dark: true,
        colors: {
          // Modern dark theme inspired by Discord/GitHub Dark/Material Design 3
          primary: '#5865F2',           // Discord blurple - modern and vibrant
          secondary: '#4F545C',         // Subtle gray for secondary elements
          accent: '#00D4AA',            // Teal accent for highlights
          error: '#ED4245',             // Softer red for errors
          info: '#5865F2',              // Consistent with primary
          success: '#57F287',           // Bright green for success states
          warning: '#FEE75C',           // Warm yellow for warnings
          background: '#0D1117',        // GitHub dark background - not pure black
          surface: '#161B22',           // GitHub dark surface - cards, modals
          'surface-variant': '#21262D', // Slightly lighter surface for variety
          'on-background': '#F0F6FC',   // High contrast text on background
          'on-surface': '#F0F6FC',      // High contrast text on surface
          'on-primary': '#FFFFFF',      // White text on primary
          'on-secondary': '#FFFFFF',    // White text on secondary
          'surface-bright': '#30363D',  // Brighter surface for hover states
          'surface-light': '#21262D',   // Light surface variant
          'surface-container': '#161B22', // Container surface
          'surface-container-high': '#21262D', // High emphasis container
          'surface-container-highest': '#30363D', // Highest emphasis container
          outline: '#30363D',           // Subtle borders
          'outline-variant': '#21262D', // Even more subtle borders
        },
      },
    },
  },
})

// Toast configuration
const toastOptions = {
  position: 'top-right' as const,
  timeout: 5000,
  closeOnClick: true,
  pauseOnFocusLoss: true,
  pauseOnHover: true,
  draggable: true,
  draggablePercent: 0.6,
  showCloseButtonOnHover: false,
  hideProgressBar: false,
  closeButton: 'button',
  icon: true,
  rtl: false,
}

const app = createApp(App)

app.use(createPinia())
app.use(router)
app.use(vuetify)
app.use(Toast, toastOptions)
app.use(Particles, {
  init: async engine => {
    await loadSlim(engine)
  },
})

app.mount('#app')
