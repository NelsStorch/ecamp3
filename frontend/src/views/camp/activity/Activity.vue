<!--
Displays a single activity
-->

<template>
  <v-container fluid>
    <CommentWrapper
      v-if="!comments._meta.loading"
    >
      <ScheduleEntry :activity-id="activityId" :schedule-entry-id="scheduleEntryId" />
      <template #comments>
        <Comment
          deletable
          v-for="comment in comments.items"
          :comment="comment"
          :key="comment._meta.self"
        >
          <e-richtext
            class="e-story-day e-story-day-readonly"
            :value="comment.textHtml"
            :readonly="true"
            :outlined="false"
            :solo="false"
            auto-grow
            dense
            label=""
          />
          <div class="d-flex align-center justify-space-between mt-1 gap-2">
            <span
              ><UserAvatar :user="comment.author()" size="24" class="mr-1" />
              {{ comment.author().displayName }}</span
            >
            <span>{{ $date(comment.createTime).format($tc('global.datetime.dateTimeLong')) }}</span>
          </div>
        </Comment>
        <Comment class="relative">
          <e-richtext
            class="e-story-day e-story-day--textmode"
            :value="newComment"
            placeholder="Kommentar"
            @input="onInput"
          />
          <div class="d-flex align-center mt-2 gap-2">
            <span
              ><UserAvatar :user="authUser" size="24" class="mr-1" />
              {{ authUser.displayName }}</span
            >
          </div>
          <v-btn absolute text style="bottom: 2px; right: 1px" @click="addComment" :disabled="newComment.length === 0">Send</v-btn>
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
import dayjs from '@/common/helpers/dayjs.js'

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
  data() {
    return {
      newComment: '',
    }
  },
  computed: {
    ...mapGetters({
      authUser: 'getLoggedInUser',
    }),
    comments() {
      return this.api.get().activities({ id: this.activityId }).comments()
    },
  },
  methods: {
    onInput(newValue) {
      this.newComment = newValue
    },
    async addComment() {
      const activity = this.api.get().activities({ id: this.activityId })
      await this.api.post(await this.api.href(this.api.get(), 'comments'), { textHtml: this.newComment, activity: activity._meta.self, camp: activity.camp()._meta.self })
      await this.comments.$reload()
      this.newComment = ''
    }
  }
}
</script>

<style scoped>

.e-story-day :deep(.v-text-field) {
  margin-top: 0;
  padding-top: 0;
}

.e-story-day.e-story-day-readonly :deep(.v-input__slot) {
  padding: 0 !important;
}

/* this disables the bottom border which is displayed for VTextField in "regular" style */
.e-story-day--textmode :deep(.v-input__slot)::before {
  display: none;
}
</style>
