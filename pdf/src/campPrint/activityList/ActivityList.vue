<template>
  <Page :id="id" class="page">
    <slot></slot>
    <TocSectionStartMarker :id="id" />
    <ActivityListPeriod
      v-for="period in periods"
      :id="id"
      :period="period"
      :config="config"
      :content-type-names="['LearningObjectives', 'LearningTopics', 'Checklist']"
      :filter="content.options.filter"
    />
  </Page>
</template>
<script>
import PdfComponent from '@/PdfComponent.js'
import ActivityListPeriod from './ActivityListPeriod.vue'
import TocSectionStartMarker from '../TocSectionStartMarker.vue'

export default {
  name: 'ActivityList',
  components: { TocSectionStartMarker, ActivityListPeriod },
  extends: PdfComponent,
  props: {
    content: { type: Object, required: true },
    config: { type: Object, required: true },
  },
  computed: {
    periods() {
      return this.content.options.periods.map((periodUri) => this.api.get(periodUri))
    },
  },
}
</script>
