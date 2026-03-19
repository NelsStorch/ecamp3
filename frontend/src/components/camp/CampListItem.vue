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
      <span>{{ date }}</span>
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
export default {
  name: 'CampListItem',
  props: {
    camp: { type: Object, required: true },
    periods: { type: Array, default: () => [] },
  },
  computed: {
    date() {
      if (!this.periods.length) return
      const formatMY = new Intl.DateTimeFormat(componentI18n.locale, {
        year: 'numeric',
        month: 'short',
      })
      return [...this.periods]
        .sort((a, b) => new Date(a.start) - new Date(b.start))
        .map((period) => {
          return formatMY.formatRange(new Date(period.start), new Date(period.end))
        })
        .join(' | ')
    },
  },
  methods: { campRoute },
}
</script>

<style scoped></style>
