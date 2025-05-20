<template>
  <div class="px-md-4 flex-grow-1 d-flex flex-column justify-content-between">
    <e-select
      v-model="options.periods"
      :items="periods"
      :label="$tc('print.config.periods')"
      multiple
      :filled="false"
      :readonly="periods.length === 1"
      @input="$emit('input')"
    />
    <e-checkbox
      v-model="options.dayOverview"
      :label="$tc('components.print.config.programConfig.dayOverview')"
      @input="$emit('input')"
    />
    <div class="flex-grow-1"></div>
    <DialogScheduleEntryFilter
      :camp="camp"
      :filter-fn="filterFn()"
      :filter="options.filter"
      hide-period-filter
      @input="updateFilter"
    />
  </div>
</template>

<script>
import { filterMatchScheduleEntry } from '@/common/helpers/filterMatchScheduleEntry.js'
import DialogScheduleEntryFilter from './DialogScheduleEntryFilter.vue'
import { isEqual } from 'lodash-es'

export default {
  name: 'ProgramConfig',
  components: { DialogScheduleEntryFilter },
  props: {
    value: { type: Object, required: true },
    camp: { type: Object, required: true },
  },
  computed: {
    options: {
      get() {
        return this.value
      },
      set(v) {
        this.$emit('input', v)
      },
    },
    periods() {
      return this.camp.periods().items.map((p) => ({
        value: p._meta.self,
        text: p.description,
      }))
    },
    selectedPeriods() {
      if (!this.options.filter.period) return this.camp.periods().items
      return this.camp.periods().items.filter((period) => {
        return this.filter.periods.includes(period._meta.self)
      })
    },
  },
  methods: {
    filterFn() {
      return (filter) =>
        this.selectedPeriods
          .flatMap((period) => period.scheduleEntries().items)
          .filter((scheduleEntry) => filterMatchScheduleEntry(scheduleEntry, filter))
    },
    updateFilter(newFilter) {
      this.options.filter = newFilter
      this.$emit('input')
    },
  },
  defaultOptions(camp) {
    return {
      periods:
        camp.periods().items.length === 1 ? [camp.periods().items[0]._meta.self] : [],
      dayOverview: true,
    }
  },
  design: {
    multiple: true,
  },
  repairConfig(config, camp) {
    if (!config.options) config.options = {}
    if (camp.periods().items.length === 1) {
      config.options.periods = [camp.periods().items[0]._meta.self]
    } else {
      if (!config.options.periods) config.options.periods = []
      const knownPeriods = camp.periods().items.map((p) => p._meta.self)
      config.options.periods = config.options.periods.filter((period) => {
        return knownPeriods.includes(period)
      })
    }
    if (typeof config.options.dayOverview !== 'boolean') config.options.dayOverview = true
    if (!config.options.filter || typeof config.options.filter !== 'object') {
      config.options.filter = {
        category: [],
        responsible: [],
        progressLabel: [],
      }
    }
    if (!config.options.filter.period) config.options.filter.period = null
    if (!knownPeriods.includes(config.options.filter.period))
      config.options.filter.period = null
    if (!config.options.filter.category) config.options.filter.category = []
    const knownCategories = camp.categories().items.map((c) => c._meta.self)
    config.options.filter.category = config.options.filter.category.filter((category) => {
      return knownCategories.includes(category)
    })
    if (!config.options.filter.responsible) config.options.filter.responsible = []
    const knownCampCollaborations = camp
      .campCollaborations()
      .items.map((c) => c._meta.self)
    if (!isEqual(config.options.filter.responsible, ['none'])) {
      config.options.filter.responsible = config.options.filter.responsible.filter(
        (responsible) => {
          return knownCampCollaborations.includes(responsible)
        }
      )
    }
    if (!config.options.filter.progressLabel) config.options.filter.progressLabel = []
    const knownProgressLabels = camp.progressLabels().items.map((l) => l._meta.self)
    config.options.filter.progressLabel = config.options.filter.progressLabel.filter(
      (progressLabel) => {
        return knownProgressLabels.includes(progressLabel) || 'none' === progressLabel
      }
    )
    return config
  },
}
</script>
