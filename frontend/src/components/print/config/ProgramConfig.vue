<template>
  <div>
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
    <v-dialog
      v-model="openFilter"
      :fullscreen="$vuetify.breakpoint.smAndDown"
      width="1000"
    >
      <template #activator="{ on, attrs }">
        <v-chip
          :input-value="openFilter"
          outlined
          class="align-self-center mt-4"
          v-bind="attrs"
          v-on="on"
        >
          <v-icon left size="20">mdi-filter</v-icon>
          {{ $tc('components.print.config.programConfig.filterActivities') }}
        </v-chip>
      </template>
      <v-card>
        <ScheduleEntryFilters
          v-model="options.filter"
          class="py-4 justify-center"
          :camp="camp"
          :filter-fn="filterFn()"
          @input="$emit('input')"
        />
      </v-card>
    </v-dialog>
  </div>
</template>

<script>
import ScheduleEntryFilters from '../../program/ScheduleEntryFilters.vue'
import { filterMatchScheduleEntry } from '@/common/helpers/filterMatchScheduleEntry.js'

export default {
  name: 'ProgramConfig',
  components: { ScheduleEntryFilters },
  props: {
    value: { type: Object, required: true },
    camp: { type: Object, required: true },
  },
  data() {
    return {
      openFilter: false,
    }
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
    if (!config.options.filter.category) config.options.filter.category = []
    const knownCategories = camp.categories().items.map((c) => c._meta.self)
    config.options.filter.category = config.options.filter.category.filter((category) => {
      return knownCategories.includes(category)
    })
    if (!config.options.filter.responsible) config.options.filter.responsible = []
    const knownCampCollaborations = camp
      .campCollaborations()
      .items.map((c) => c._meta.self)
    config.options.filter.responsible = config.options.filter.responsible.filter(
      (responsible) => {
        return knownCampCollaborations.includes(responsible)
      }
    )
    if (!config.options.filter.progressLabel) config.options.filter.progressLabel = []
    const knownProgressLabels = camp.progressLabels().items.map((l) => l._meta.self)
    config.options.filter.progressLabel = config.options.filter.progressLabel.filter(
      (progressLabel) => {
        return knownProgressLabels.includes(progressLabel)
      }
    )
    return config
  },
}
</script>
