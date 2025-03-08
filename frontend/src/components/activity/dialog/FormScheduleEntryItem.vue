<template>
  <div class="d-flex px-4">
    <div>
      <e-date-picker
        v-model="localScheduleEntry.start"
        value-format="YYYY-MM-DDTHH:mm:ssZ"
        path="startDate"
        vee-rules="required"
        :allowed-dates="dateIsInAnyPeriod"
        :filled="false"
        class="float-left date-picker mr-3 mt-1"
        required
      />

      <e-time-dropdown
        v-model="localScheduleEntry.start"
        path="startDatetime"
        vee-rules="required"
        :filled="false"
        input-class="float-left mt-1"
        required
        value-format="YYYY-MM-DDTHH:mm:ssZ"
        :items="startTimeList"
        :menu-props="{
          maxHeight: '320',
          offsetOverflow: true,
          offsetY: true,
          nudgeLeft: '16px',
        }"
      />
    </div>
    <div
      class="pt-1 mx-2 v-input v-input--hide-details v-input--is-label-active v-input--is-dirty theme--light v-text-field v-text-field--is-booted"
    >
      <div class="v-text-field input">-</div>
    </div>
    <div>
      <e-time-dropdown
        v-model="localScheduleEntry.end"
        value-format="YYYY-MM-DDTHH:mm:ssZ"
        path="endDatetime"
        :vee-rules="endTimeValidation"
        :min="minEndTime"
        :filled="false"
        input-class="float-left mt-1 mr-3"
        required
        :items="endTimeList"
        :menu-props="{
          maxHeight: '320',
          offsetOverflow: true,
          offsetY: true,
          nudgeLeft: '16px',
        }"
      >
        <template #item="{ item }">
          <span class="tabular-nums">{{ item.label }}</span
          >&nbsp;<span class="opacity-60">({{ item.duration }})</span>
        </template>
      </e-time-dropdown>

      <e-date-picker
        v-if="!isSameDay"
        v-model="localScheduleEntry.end"
        value-format="YYYY-MM-DDTHH:mm:ssZ"
        path="endDate"
        vee-rules="required|greaterThanOrEqual_date:@startDate"
        :min="localScheduleEntry.start"
        :allowed-dates="dateIsInSelectedPeriod"
        :filled="false"
        class="float-left date-picker mr-3 mt-1"
        required
      />

      <e-text-field
        v-else
        readonly
        label="Dauer"
        :filled="false"
        class="float-left date-picker mt-1"
        :value="timeDurationShort(localScheduleEntry.start, localScheduleEntry.end)"
      />
    </div>
    <div class="text-field-alignment">
      <button-delete v-if="deletable" icon-only @click="$emit('delete')" />
    </div>
  </div>
</template>
<script>
import dayjs from '@/common/helpers/dayjs.js'

import ButtonDelete from '@/components/buttons/ButtonDelete.vue'
import { dateHelperUTCFormatted } from '@/mixins/dateHelperUTCFormatted.js'
import ETimeDropdown from '@/components/form/base/ETimeDropdown.vue'

export default {
  name: 'FormScheduleEntryItem',
  components: { ETimeDropdown, ButtonDelete },
  mixins: [dateHelperUTCFormatted],
  provide() {
    return {
      entityName: 'scheduleEntry',
    }
  },
  props: {
    // scheduleEntry to display
    scheduleEntry: {
      type: Object,
      required: true,
    },

    // List of available periods
    periods: {
      type: Array,
      required: true,
    },

    // true if current item is the last scheduleEntry
    deletable: {
      type: Boolean,
      required: false,
    },
  },
  data() {
    return {
      localScheduleEntry: this.scheduleEntry,
    }
  },
  computed: {
    // detect selected period based on start date
    period() {
      const startDate = dayjs.utc(this.localScheduleEntry.start)

      return this.periods.find((period) => {
        return startDate.isBetween(
          dayjs.utc(period.start),
          dayjs.utc(period.end),
          'date',
          '[]'
        )
      })
    },
    startUTC() {
      return this.$date.utc(this.localScheduleEntry.start)
    },
    endUTC() {
      return this.$date.utc(this.localScheduleEntry.end)
    },
    isSameDay() {
      return this.startUTC.isSame(this.endUTC, 'day')
    },
    endTimeValidation() {
      const validator = {
        required: true,
      }

      // only compare time if date is the same day
      if (this.isSameDay) {
        validator.greaterThan_time = {
          min: this.$date.utc(this.localScheduleEntry.start),
        }
      }
      return validator
    },
    minEndTime() {
      if (!this.isSameDay) return null
      return this.$date
        .utc(this.localScheduleEntry.start)
        .add(this.$date.duration(15, 'm'))
        .format('HH:mm')
    },
    startTimeList() {
      const start = this.$date.utc(this.localScheduleEntry.start).startOf('day')
      const times = []

      for (let i = 0; i <= 4 * 24; i++) {
        const value = start.add(i * 15, 'm')
        times.push({
          date: value,
          value: value.format('YYYY-MM-DDTHH:mm:ssZ'),
          label: value.format('HH:mm'),
        })
      }

      return times
    },
    endTimeList() {
      const start = this.$date.utc(this.localScheduleEntry.start)
      const times = []

      for (let i = 0; i <= 4 * 26; i++) {
        const value = start.add(i * 15, 'm')
        times.push({
          date: value,
          value: value.format('YYYY-MM-DDTHH:mm:ssZ'),
          label: value.format('HH:mm'),
          duration: this.timeDurationShort(start, value),
        })
      }

      return times
    },
  },
  watch: {
    'period._meta.self': function (value) {
      if (value === undefined || this.period === undefined) return

      const period = this.period

      // change period in object
      this.localScheduleEntry.period = () => period
    },

    // watch start and automatically shift end if start changes (=keep duration)
    'localScheduleEntry.start': function (newValue, oldValue) {
      const delta = dayjs.utc(newValue).diff(dayjs.utc(oldValue))
      this.localScheduleEntry.end = dayjs
        .utc(this.localScheduleEntry.end)
        .add(delta)
        .format()
    },
  },
  methods: {
    dateIsInAnyPeriod: function (val) {
      const calendarDate = dayjs.utc(val)
      return this.periods.some((period) => {
        return calendarDate.isBetween(
          dayjs.utc(period.start),
          dayjs.utc(period.end),
          'date',
          '[]'
        )
      })
    },
    dateIsInSelectedPeriod: function (val) {
      if (this.period === undefined) {
        return this.dateIsInAnyPeriod(val)
      }
      const calendarDate = dayjs.utc(val)
      return calendarDate.isBetween(
        dayjs.utc(this.period.start),
        dayjs.utc(this.period.end),
        'date',
        '[]'
      )
    },
  },
}
</script>
<style scoped lang="scss">
.date-picker {
  width: 130px;
}

.text-field-alignment {
  padding-top: 12px;
  margin-top: 4px;
}
</style>
