<template>
  <v-chip label outlined :color="value ? 'primary' : null" @click="$emit('input', !value)"
    >{{ label }}
    <v-badge
      v-if="resultCount !== null"
      inline
      bordered
      color="#e8e8e8"
      :content="resultCount"
  /></v-chip>
</template>

<script>
export default {
  name: 'BooleanFilter',
  props: {
    value: Boolean,
    label: { type: String, required: true },
    resultCount: { type: Number, default: null },
  },
}
</script>

<style scoped>
::v-deep(.v-badge__badge) {
  color: #000 !important;
}
</style>
