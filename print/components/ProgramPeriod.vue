<template>
  <div class="tw-break-after-page" :class="pageSize">
    <div>
      <h1 class="tw-text-center tw-font-semibold tw-mb-6">
        {{ $t('print.program.title') }}: {{ period.description }}
      </h1>
    </div>
    <generic-error-message v-if="error" :error="error" />
    <program-day
      v-for="day in days"
      v-else
      :key="'day' + day.id"
      :day="day"
      :filter="filter"
      :show-daily-summary="showDailySummary"
      :show-activities="showActivities"
      :index="index"
    />
  </div>
</template>

<script setup>
import { filterMatchScheduleEntry } from '@/common/helpers/filterMatchScheduleEntry.js'

const props = defineProps({
  period: { type: Object, required: true },
  filter: { type: Object, default: () => ({}) },
  showDailySummary: { type: Boolean, required: true },
  showActivities: { type: Boolean, required: true },
  index: { type: Number, required: true },
  pageSize: { type: String, default: 'a4' },
})

const { data: days, error } = await useAsyncData(
  `ProgramPeriod-${props.period._meta.self}`,
  async () => {
    await Promise.all([
      props.period.days().$loadItems(),
      props.period.scheduleEntries().$loadItems(),
      props.period.contentNodes().$loadItems(),
    ])

    return props.period.days().items.filter((day) => {
      return (
        props.period
          .scheduleEntries()
          .items.filter(
            (scheduleEntry) =>
              scheduleEntry.day()._meta.self === day._meta.self &&
              filterMatchScheduleEntry(scheduleEntry, props.filter)
          ).length > 0
      )
    })
  }
)
</script>
