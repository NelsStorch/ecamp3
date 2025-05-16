<template>
  <v-dialog
    v-model="dialogOpen"
    :fullscreen="$vuetify.breakpoint.smAndDown"
    width="1000"
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
    <v-card>
      <ScheduleEntryFilters
        v-model="localFilter"
        class="py-4 justify-center"
        :camp="camp"
        :filter-fn="filterFn"
      />
    </v-card>
  </v-dialog>
</template>
<script>
import ScheduleEntryFilters from '../../program/ScheduleEntryFilters.vue'
import CountBadge from '../../dashboard/CountBadge.vue'

export default {
  name: 'DialogScheduleEntryFilter',
  components: { CountBadge, ScheduleEntryFilters },
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
    }
  },
}
</script>
