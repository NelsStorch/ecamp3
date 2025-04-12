<template>
  <div style="display: flex" class="gap-4 flex-wrap">
    <div style="flex-grow: 1; flex-shrink: 1">
      <slot />
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
    <div
      v-if="$vuetify.breakpoint.width > 1360"
      style="flex-basis: 320px"
      class="d-flex flex-column gap-2 items-center"
    >
      <Comment>
        <p class="mb-2">
          Lorem ipsum dolor sit amet, consectetur adipisicing elit. Commodi magnam nobis
          perferendis rerum sequi. Culpa cupiditate dolorum, ea earum eveniet harum illo,
          illum nemo neque pariatur quasi quia.
        </p>
        <div class="d-flex align-center mt-1 gap-2">
          <UserAvatar :user="authUser" size="24" /> <span>Linux – 31.02.2025 12:00</span>
        </div>
      </Comment>
      <Comment>
        <e-textarea />
        <div class="d-flex align-center mt-2 gap-2">
          <UserAvatar :user="authUser" size="24" /> <span>Linux</span>
        </div>
      </Comment>
    </div>
    <v-navigation-drawer v-else v-model="openComments" clipped right app permanent>
      Gugus
    </v-navigation-drawer>
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
