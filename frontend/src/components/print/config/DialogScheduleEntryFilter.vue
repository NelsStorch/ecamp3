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
        class="align-self-center mt-4"
        v-bind="attrs"
        v-on="on"
      >
        <v-icon left size="20">mdi-filter</v-icon>
        {{ $tc('components.print.config.dialogScheduleEntryFilter.filterActivities') }}
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

export default {
  name: 'DialogScheduleEntryFilter',
  components: { ScheduleEntryFilters },
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
  methods: {
    emit(dialogOpen) {
      if (dialogOpen) return // only emit when closing dialog
      this.$emit('input', this.localFilter)
    }
  },
}
</script>
