<template>
  <v-card class="ec-comment" color="grey lighten-2" elevation="0" rounded="xl">
    <v-card-text class="py-2 px-3">
      <slot />
    </v-card-text>
  </v-card>
</template>

<script>
import UserAvatar from '@/components/user/UserAvatar.vue'

export default {
  name: 'Comment',
  components: { UserAvatar },
}
</script>

<style scoped>
.v-card.ec-comment {
  border-width: 0 0 6px !important;
  border-style: solid !important;
  border-color: #e1ecf3 !important;
  background-color: #cedbe1 !important;
}
</style>
