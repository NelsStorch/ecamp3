<!--
Displays a single activity
-->

<template>
  <v-container fluid>
    <CommentWrapper>
      <ScheduleEntry :activity-id="activityId" :schedule-entry-id="scheduleEntryId" />
      <template #comments>
        <Comment deletable>
          <p class="mb-2">
            Lorem ipsum dolor sit amet, consectetur adipisicing elit. Commodi magnam nobis
            perferendis rerum sequi. Culpa cupiditate dolorum, ea earum eveniet harum
            illo, illum nemo neque pariatur quasi quia.
          </p>
          <div class="d-flex align-center justify-space-between mt-1 gap-2">
            <span
              ><UserAvatar :user="authUser" size="24" class="mr-1" />
              {{ authUser.displayName }}</span
            >
            <span>31.02.2025 12:00</span>
          </div>
        </Comment>
        <Comment class="relative">
          <e-textarea dense placeholder="Kommentar" style="margin: -8px -12px 0" />
          <div class="d-flex align-center mt-2 gap-2">
            <span
              ><UserAvatar :user="authUser" size="24" class="mr-1" />
              {{ authUser.displayName }}</span
            >
          </div>
          <v-btn absolute text style="bottom: 2px; right: 1px">Send</v-btn>
        </Comment>
      </template>
    </CommentWrapper>
  </v-container>
</template>

<script>
import ScheduleEntry from '@/components/activity/ScheduleEntry.vue'
import CommentWrapper from '@/components/comments/CommentWrapper.vue'
import UserAvatar from '@/components/user/UserAvatar.vue'
import { mapGetters } from 'vuex'

export default {
  name: 'Activity',
  components: {
    UserAvatar,
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
  computed: {
    ...mapGetters({
      authUser: 'getLoggedInUser',
    }),
  },
}
</script>
