<!--
Displays a single activity
-->

<template>
  <v-container fluid>
    <CommentWrapper>
      <ScheduleEntry :activity-id="activityId" :schedule-entry-id="scheduleEntryId" />
    </CommentWrapper>
  </v-container>
</template>

<script>
import ScheduleEntry from '@/components/activity/ScheduleEntry.vue'
import CommentWrapper from '@/components/comments/CommentWrapper.vue'

export default {
  name: 'Activity',
  components: {
    CommentWrapper,
    ScheduleEntry,
  },

  props: {
    activityId: {
      type: String,
      required: true,
    },
    scheduleEntryId: {
      type: String,
      default: null,
    },
  },
}
</script>
