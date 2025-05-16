<template>
  <DetailPane
    v-model="dialogOpen"
    max-width="900px"
    :title="$tc('components.print.config.dialogScheduleEntryFilter.title')"
    icon="mdi-filter"
    :cancel-action="close"
    :cancel-label="$tc('global.button.close')"
    @input="emit"
  >
    <template #activator="{ on, attrs }">
      <v-chip
        :input-value="dialogOpen"
        outlined
        :color="anyFilter ? 'info' : ''"
        class="align-self-center mt-4"
        v-bind="attrs"
        v-on="on"
      >
        <v-icon left size="20">mdi-filter</v-icon>
        {{ activatorLabel }}
        <CountBadge v-if="anyFilter" :count="filterFn(filter).length" />
      </v-chip>
    </template>
    <ScheduleEntryFilters
      v-model="localFilter"
      :camp="camp"
      :filter-fn="filterFn"
    />
  </DetailPane>
</template>
<script>
import ScheduleEntryFilters from '../../program/ScheduleEntryFilters.vue'
import CountBadge from '../../dashboard/CountBadge.vue'
import DetailPane from '../../generic/DetailPane.vue'

export default {
  name: 'DialogScheduleEntryFilter',
  components: { DetailPane, CountBadge, ScheduleEntryFilters },
  props: {
    camp: {},
    filterFn: {},
    filter: {},
  },
  data() {
    return {
      dialogOpen: false,
      localFilter: this.filter,
    }
  },
  computed: {
    activatorLabel() {
      if (this.anyFilter) return this.$tc('components.print.config.dialogScheduleEntryFilter.filterActive')
      return this.$tc('components.print.config.dialogScheduleEntryFilter.filterActivities')
    },
    anyFilter() {
      return this.filter.period ||
        (this.filter.responsible != null && this.filter.responsible.length > 0) ||
        (this.filter.category != null && this.filter.category.length > 0) ||
        (this.filter.progressLabel != null && this.filter.progressLabel.length > 0)
    }
  },
  methods: {
    emit(dialogOpen) {
      if (dialogOpen) return // only emit when closing dialog
      this.$emit('input', this.localFilter)
    },
    close() {
      this.dialogOpen = false
      this.emit()
    }
  },
}
</script>
