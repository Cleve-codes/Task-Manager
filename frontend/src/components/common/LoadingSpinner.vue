<template>
  <div
    :class="[
      'loading-spinner',
      {
        'loading-spinner--overlay': overlay,
        'loading-spinner--fullscreen': fullscreen
      }
    ]"
  >
    <div class="loading-spinner__content">
      <v-progress-circular
        :size="size"
        :width="width"
        :color="color"
        indeterminate
      />
      
      <div
        v-if="text"
        class="loading-spinner__text mt-4"
      >
        {{ text }}
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
// Props
interface Props {
  size?: string | number
  width?: string | number
  color?: string
  text?: string
  overlay?: boolean
  fullscreen?: boolean
}

withDefaults(defineProps<Props>(), {
  size: 40,
  width: 4,
  color: 'primary',
  text: '',
  overlay: false,
  fullscreen: false,
})
</script>

<style scoped>
.loading-spinner {
  display: flex;
  align-items: center;
  justify-content: center;
  padding: 2rem;
}

.loading-spinner--overlay {
  position: absolute;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background: rgba(255, 255, 255, 0.8);
  z-index: 1000;
}

.loading-spinner--fullscreen {
  position: fixed;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background: rgba(255, 255, 255, 0.9);
  z-index: 9999;
}

.loading-spinner__content {
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
}

.loading-spinner__text {
  font-size: 0.875rem;
  color: rgba(0, 0, 0, 0.6);
  text-align: center;
}
</style>
