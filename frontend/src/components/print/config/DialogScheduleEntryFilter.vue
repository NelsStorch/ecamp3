<template>
  <v-dialog
    v-model="openFilter"
    :fullscreen="$vuetify.breakpoint.smAndDown"
    width="1000"
    @close="emit"
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
        :filter-fn="filterFn"
        @input="$emit('input', options)"
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
    options: {},
  },
  data() {
    return {
      openFilter: false,
    }
  },
  methods: {
    emit() {
      this.$emit('input', this.options)
    }
  },
}
</script>
