<template>
  <div class="px-md-4">
    <e-select
      v-model="options.periods"
      :items="periods"
      :label="$tc('print.config.periods')"
      multiple
      :filled="false"
      :readonly="periods.length === 1"
      @input="$emit('input')"
    />
  </div>
</template>

<script>
import SummaryConfig from '@/components/print/config/SummaryConfig.vue'
import { SUMMARY_CONTENTTYPES } from '@/components/print/config/SummaryConfig.vue'

export default {
  name: 'SafetyConsiderationsConfig',
  extends: SummaryConfig,
  defaultOptions(camp) {
    return {
      periods:
        camp.periods().items.length === 1 ? [camp.periods().items[0]._meta.self] : [],
      contentType: 'SafetyConsiderations',
    }
  },
  design: {
    multiple: false,
  },
  repairConfig(config, camp) {
    if (!config.options) config.options = {}
    if (!config.options.contentType) config.options.contentType = 'SafetyConsiderations'
    if (!SUMMARY_CONTENTTYPES.includes(config.options.contentType)) {
      config.options.contentType = 'SafetyConsiderations'
    }
    if (camp.periods().items.length === 1) {
      config.options.periods = [camp.periods().items[0]._meta.self]
    } else {
      if (!config.options.periods) config.options.periods = []
      const knownPeriods = camp.periods().items.map((p) => p._meta.self)
      config.options.periods = config.options.periods.filter((period) => {
        return knownPeriods.includes(period)
      })
    }
    return config
  },
}
</script>
