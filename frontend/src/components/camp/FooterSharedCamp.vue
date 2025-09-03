<template>
  <v-footer v-if="isOutsider || showSharedWarning" app class="shared-camp">
    <p v-if="isOutsider" class="mb-0">
      <strong>{{ $tc('components.camp.footerSharedCamp.outsider') }}</strong>
      {{ $tc('components.camp.footerSharedCamp.outsiderDescription') }}
    </p>
    <p v-if="showSharedWarning" class="mb-0">
      <strong>{{ $tc('components.camp.footerSharedCamp.shared') }}</strong>
      {{ $tc('components.camp.footerSharedCamp.sharedDescription') }}
    </p>
  </v-footer>
</template>

<script>
import { campFromRoute } from '@/router.js'
import { campRoleMixin } from '@/mixins/campRoleMixin.js'

export default {
  name: 'FooterSharedCamp',
  mixins: [campRoleMixin],
  computed: {
    camp() {
      return campFromRoute(this.$route)
    },
    showSharedWarning() {
      // The warning is intended for people with write access to the camp,
      // so that they know that the data they enter is publicly accessible.
      return this.camp?.isShared && !this.isOutsider && !this.isGuest
    },
  },
}
</script>

<style scoped>
/* <v-footer> is transformed to <footer class="v-footer"> */
/* eslint-disable-next-line vue-scoped-css/no-unused-selector */
.v-footer.shared-camp {
  border-top: 3px solid #c8930d;
  z-index: 4;
  background: #fbf0df;
  color: #7a4f0f;
  font-size: 80%;
}
</style>
