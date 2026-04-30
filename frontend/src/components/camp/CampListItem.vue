<template>
  <v-list-item lines="two" :to="campRoute(camp)">
    <v-list-item-title class="d-flex gap-x-2 justify-space-between">
      <strong class="whitespace-normal">{{ camp.title }}</strong>
      <v-chip
        v-if="camp.isShared === true"
        size="x-small"
        class="align-self-center px-1 v-btn--has-bg"
        >{{ $t('components.camp.campListItem.public') }}</v-chip
      >
      <span class="flex-grow-1"></span>
      <span class="whitespace-normal max-w-60vw text-right">
        <template v-for="(group, i) in date" :key="i">
          <template v-if="!!i"> | </template>
          <span class="break-inside-avoid">{{ group }}</span>
        </template>
      </span>
    </v-list-item-title>
    <v-list-item-subtitle class="d-flex gap-2 flex-wrap-reverse justify-space-between">
      <span class="whitespace-normal">{{ camp.motto }}</span>
      <span>{{ camp.organizer }}</span>
    </v-list-item-subtitle>
  </v-list-item>
</template>

<script>
import { campRoute } from '@/router.js'
import { componentI18n } from '@/plugins/i18n/index.js'
import groupBy from 'lodash-es/groupBy.js'

const YEAR_JOINER = '-'

export default {
  name: 'CampListItem',
  props: {
    camp: { type: Object, required: true },
    periods: { type: Array, default: () => [] },
  },
  computed: {
    formatMY() {
      return new Intl.DateTimeFormat(componentI18n.locale, {
        year: 'numeric',
        month: 'short',
      })
    },
    formatM() {
      return new Intl.DateTimeFormat(componentI18n.locale, { month: 'short' })
    },
    date() {
      return Object.entries(this.groupedPeriods).map(([key, periods]) => {
        if (key.includes(YEAR_JOINER))
          return periods
            .map(({ start, end }) => this.formatMY.formatRange(start, end))
            .join(' | ')
        else
          return (
            periods
              .map(({ start, end }) => this.formatM.formatRange(start, end))
              .join(', ') +
            ' ' +
            key
          )
      })
    },
    datePeriods() {
      return this.periods
        .map((period) => ({
          ...period,
          start: new Date(period.start),
          end: new Date(period.end),
        }))
        .toSorted((a, b) => a.start - b.start)
    },
    groupedPeriods() {
      return groupBy(this.datePeriods, (period) => {
        if (period.start.getFullYear() === period.end.getFullYear())
          return '' + period.start.getFullYear()
        else return period.start.getFullYear() + YEAR_JOINER + period.end.getFullYear()
      })
    },
  },
  methods: { campRoute },
}
</script>

<style scoped></style>
