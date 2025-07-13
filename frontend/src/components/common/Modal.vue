<template>
  <v-dialog
    v-model="isOpen"
    :max-width="maxWidth"
    :persistent="persistent"
    :scrollable="scrollable"
  >
    <v-card>
      <v-card-title v-if="title || $slots.title" class="d-flex align-center">
        <slot name="title">
          <span class="text-h6">{{ title }}</span>
        </slot>
        
        <v-spacer />
        
        <v-btn
          v-if="showCloseButton"
          icon="mdi-close"
          variant="text"
          @click="handleClose"
        />
      </v-card-title>

      <v-divider v-if="title || $slots.title" />

      <v-card-text
        v-if="$slots.default"
        :class="{ 'pa-0': noPadding }"
      >
        <slot />
      </v-card-text>

      <v-card-actions v-if="$slots.actions || showDefaultActions">
        <slot name="actions">
          <v-spacer />
          
          <v-btn
            v-if="showCancelButton"
            variant="text"
            @click="handleCancel"
          >
            {{ cancelText }}
          </v-btn>
          
          <v-btn
            v-if="showConfirmButton"
            :color="confirmColor"
            :loading="loading"
            @click="handleConfirm"
          >
            {{ confirmText }}
          </v-btn>
        </slot>
      </v-card-actions>
    </v-card>
  </v-dialog>
</template>

<script setup lang="ts">
import { computed } from 'vue'

// Props
interface Props {
  modelValue: boolean
  title?: string
  maxWidth?: string | number
  persistent?: boolean
  scrollable?: boolean
  showCloseButton?: boolean
  showDefaultActions?: boolean
  showCancelButton?: boolean
  showConfirmButton?: boolean
  cancelText?: string
  confirmText?: string
  confirmColor?: string
  loading?: boolean
  noPadding?: boolean
}

const props = withDefaults(defineProps<Props>(), {
  maxWidth: 500,
  persistent: false,
  scrollable: false,
  showCloseButton: true,
  showDefaultActions: false,
  showCancelButton: true,
  showConfirmButton: true,
  cancelText: 'Cancel',
  confirmText: 'Confirm',
  confirmColor: 'primary',
  loading: false,
  noPadding: false,
})

// Emits
const emit = defineEmits<{
  'update:modelValue': [value: boolean]
  'close': []
  'cancel': []
  'confirm': []
}>()

// Computed
const isOpen = computed({
  get: () => props.modelValue,
  set: (value) => emit('update:modelValue', value)
})

// Methods
const handleClose = () => {
  emit('close')
  isOpen.value = false
}

const handleCancel = () => {
  emit('cancel')
  isOpen.value = false
}

const handleConfirm = () => {
  emit('confirm')
}
</script>
