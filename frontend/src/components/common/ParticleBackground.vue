<template>
  <div class="particle-background">
    <vue-particles
      id="tsparticles"
      :options="particleOptions"
      @particles-loaded="particlesLoaded"
    />
    <div class="content-overlay">
      <slot />
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref } from 'vue'

// Particle configuration for elegant dark theme
const particleOptions = ref({
  background: {
    color: {
      value: '#0a0a0a'
    }
  },
  fpsLimit: 120,
  interactivity: {
    events: {
      onClick: {
        enable: true,
        mode: 'push'
      },
      onHover: {
        enable: true,
        mode: 'repulse'
      },
      resize: true
    },
    modes: {
      push: {
        quantity: 2
      },
      repulse: {
        distance: 100,
        duration: 0.4
      }
    }
  },
  particles: {
    color: {
      value: ['#667eea', '#764ba2', '#f093fb', '#f5576c', '#4facfe', '#00f2fe']
    },
    links: {
      color: '#667eea',
      distance: 150,
      enable: true,
      opacity: 0.3,
      width: 1
    },
    move: {
      direction: 'none',
      enable: true,
      outModes: {
        default: 'bounce'
      },
      random: false,
      speed: 1,
      straight: false
    },
    number: {
      density: {
        enable: true,
        area: 800
      },
      value: 80
    },
    opacity: {
      value: 0.5,
      random: {
        enable: true,
        minimumValue: 0.1
      },
      animation: {
        enable: true,
        speed: 1,
        minimumValue: 0.1,
        sync: false
      }
    },
    shape: {
      type: 'circle'
    },
    size: {
      value: { min: 1, max: 3 },
      random: {
        enable: true,
        minimumValue: 1
      },
      animation: {
        enable: true,
        speed: 2,
        minimumValue: 1,
        sync: false
      }
    }
  },
  detectRetina: true
})

// Callback when particles are loaded
const particlesLoaded = async (container: any) => {
  // Particles container loaded successfully
}
</script>

<style scoped>
.particle-background {
  position: relative;
  width: 100%;
  height: 100vh;
  overflow: hidden;
}

.content-overlay {
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  z-index: 1;
  display: flex;
  align-items: center;
  justify-content: center;
}

/* Ensure particles canvas is behind content */
:deep(#tsparticles) {
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  z-index: 0;
}
</style>
