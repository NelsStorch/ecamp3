<template>
  <e-form name="period">
    <e-text-field
      v-model="localPeriod.description"
      path="description"
      vee-rules="required"
    />

    <e-date-picker
      v-model="localPeriod.start"
      path="start"
      vee-rules="required"
      :max="localPeriod.end"
    />

    <!-- vee-rules="required|greaterThanOrEqual_date:@start" -->
    <e-date-picker v-model="localPeriod.end" path="end" :min="localPeriod.start" />
  </e-form>
</template>

<script>
export default {
  name: 'DialogPeriodForm',
  props: {
    period: { type: Object, required: true },
  },
  data() {
    return {
      localPeriod: this.period,
    }
  },
}
</script>
