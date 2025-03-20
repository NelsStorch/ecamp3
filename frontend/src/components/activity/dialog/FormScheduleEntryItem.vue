<template>
  <div class="ec-form-schedule-entry-item px-3 w-100">
    <e-date-picker
      v-model="localScheduleEntry.start"
      value-format="YYYY-MM-DDTHH:mm:ssZ"
      path="startDate"
      vee-rules="required"
      :allowed-dates="dateIsInAnyPeriod"
      :filled="false"
      class="area-startdate float-left date-picker mr-1 mt-md-1"
      required
    />

    <div class="area-starttime">
      <e-time-dropdown
        v-model="localScheduleEntry.start"
        path="startDatetime"
        vee-rules="required"
        :filled="false"
        input-class="float-left mt-md-1"
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
    <div class="area-dash mt-2">
      <div class="text-field-alignment">-</div>
    </div>
    <e-date-picker
      v-if="!$vuetify.breakpoint.mdAndUp"
      v-model="localScheduleEntry.end"
      value-format="YYYY-MM-DDTHH:mm:ssZ"
      path="endDate"
      vee-rules="required|greaterThanOrEqual_date:@startDate"
      :min="localScheduleEntry.start"
      :allowed-dates="dateIsInSelectedPeriod"
      :filled="false"
      class="area-enddate float-left date-picker mr-3 mt-md-1"
      required
    />

    <div class="area-endtime">
      <e-time-dropdown
        v-model="localScheduleEntry.end"
        value-format="YYYY-MM-DDTHH:mm:ssZ"
        path="endDatetime"
        :vee-rules="endTimeValidation"
        :min="minEndTime"
        :filled="false"
        input-class="float-left mt-md-1 mr-1"
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
    </div>

    <e-date-picker
      v-if="$vuetify.breakpoint.mdAndUp"
      v-model="localScheduleEntry.end"
      value-format="YYYY-MM-DDTHH:mm:ssZ"
      path="endDate"
      vee-rules="required|greaterThanOrEqual_date:@startDate"
      :min="localScheduleEntry.start"
      :allowed-dates="dateIsInSelectedPeriod"
      :filled="false"
      :class="{ 'hide-control': isSameDay }"
      class="area-enddate float-left date-picker mr-3 mt-md-1"
      required
    />
    <e-text-field
      readonly
      label="Dauer"
      :filled="false"
      class="area-duration float-left mr-3 mt-md-1"
      :value="timeDurationShort(localScheduleEntry.start, localScheduleEntry.end)"
    />
    <div class="area-delete text-field-alignment ml-auto">
      <button-delete :disabled="!deletable" icon-only @click="$emit('delete')" />
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
      const start = this.startUTC.startOf('day')

      // Show whole start day
      const hours = 24
      const minuteInterval = 15
      return Array.from(
        { length: hours * (60 / minuteInterval) },
        (_, i) => i * minuteInterval
      ).map((minutes) => {
        const value = start.add(minutes, 'm')
        return {
          date: value,
          value: value.format('YYYY-MM-DDTHH:mm:ssZ'),
          label: value.format('HH:mm'),
          duration: this.timeDurationShort(start, value),
        }
      })
    },
    endTimeList() {
      // be able to select end time on the end day
      if (this.isSameDay) {
        // Show 25 hours from start time
        const hours = this.startUTC.format('HH:mm') === '00:00' ? 25 : 24
        const minuteInterval = 15
        return Array.from(
          { length: hours * (60 / minuteInterval) },
          (_, i) => (i + 1) * minuteInterval
        ).map((minutes) => {
          const value = this.startUTC.add(minutes, 'm')
          return {
            date: value,
            value: value.format('YYYY-MM-DDTHH:mm:ssZ'),
            label: value.format('HH:mm'),
            duration: this.timeDurationShort(this.startUTC, value),
          }
        })
      } else {
        const startEndDay = this.endUTC.startOf('day')
        const hours = 24
        const minuteInterval = 30
        return Array.from(
          { length: hours * (60 / minuteInterval) },
          (_, i) => i * minuteInterval
        ).map((minutes) => {
          const value = startEndDay.add(minutes, 'm')
          return {
            date: value,
            value: value.format('YYYY-MM-DDTHH:mm:ssZ'),
            label: value.format('HH:mm'),
            duration: this.timeDurationShort(this.startUTC, value),
          }
        })
      }
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
    alert() {
      console.log('test')
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

.area-startdate {
  grid-area: startdate;
}

.area-starttime {
  grid-area: starttime;
}

.area-enddate {
  grid-area: enddate;
}

.area-endtime {
  grid-area: endtime;
}

.area-dash {
  display: none;
  grid-area: dash;
}

@media (min-width: 768px) {
  .area-dash {
    display: block;
  }
  .area-duration {
    width: 130px;
    margin-left: 0;
  }
}

.area-duration {
  grid-area: duration;
  margin-left: 8px;
}

.hide-control:not(:hover):not(:focus):not(:focus-within):not(:has(.error--text))
  :deep(.v-input__control) {
  display: none;
}

.area-delete {
  grid-area: delete;
}

.ec-form-schedule-entry-item {
  display: grid !important;
  row-gap: 4px;
  column-gap: 0px;
  grid-template-areas:
    'startdate starttime duration'
    'enddate endtime delete';
  grid-template-columns: auto auto 1fr;
}

@media (min-width: 768px) {
  .ec-form-schedule-entry-item {
    column-gap: 8px;
    grid-template-areas: 'startdate starttime dash endtime enddate duration delete';
    grid-template-columns: auto auto auto auto 1fr auto auto;
  }
}
</style>
