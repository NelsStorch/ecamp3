<template>
  <div style="display: flex" class="gap-4 flex-wrap">
    <div style="flex-grow: 1; flex-shrink: 1">
      <slot />
    </div>
    <div
      v-if="$vuetify.breakpoint.width > 1360"
      style="flex-basis: 320px"
      class="d-flex flex-column gap-2 items-center"
    >
      <Comment deletable>
        <p class="mb-2">
          Lorem ipsum dolor sit amet, consectetur adipisicing elit. Commodi magnam nobis
          perferendis rerum sequi. Culpa cupiditate dolorum, ea earum eveniet harum illo,
          illum nemo neque pariatur quasi quia.
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
    </div>
    <v-navigation-drawer
      v-else
      v-model="openComments"
      clipped
      right
      app
      permanent
      :temporary="$vuetify.breakpoint.smAndDown"
      :color="$vuetify.breakpoint.smAndDown ? 'blue-grey' : 'transparent'"
      floating
    >
      <div class="py-3 pr-3 pl-3 pl-md-0 d-flex flex-column gap-2 items-center">
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
      </div>
    </v-navigation-drawer>
    <v-btn
      fab
      fixed
      bottom
      right
      color="primary"
      class="mb-4 mr-4"
      @click="openComments = !openComments"
    >
      <v-icon>mdi-chat</v-icon>
    </v-btn>
  </div>
</template>

<script>
import UserAvatar from '@/components/user/UserAvatar.vue'
import { mapGetters } from 'vuex'

export default {
  name: 'CommentWrapper',
  components: { UserAvatar },
  data() {
    return {
      openComments: false,
    }
  },
  computed: {
    ...mapGetters({
      authUser: 'getLoggedInUser',
    }),
  },
}
</script>

<style scoped></style>
