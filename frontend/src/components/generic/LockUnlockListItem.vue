<template>
  <v-list-item :disabled="disabled" v-bind="$attrs">
    <template #prepend>
      <v-icon v-if="value" :disabled="disabled">mdi-lock</v-icon>
      <v-icon v-else :disabled="disabled">mdi-lock-open-variant</v-icon>
    </template>
    <v-list-item-title>
      {{ modelValue ? $t('global.button.lock') : $t('global.button.unlock') }}
    </v-list-item-title>
  </v-list-item>
</template>

<script>
export default {
  props: {
    modelValue: {
      type: Boolean,
      required: true,
    },
    disabled: {
      type: Boolean,
      required: false,
      default: false,
    },
  },
}
</script>
