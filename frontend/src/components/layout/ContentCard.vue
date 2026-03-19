<!--
Displays the content wrapped inside a card.
-->

<template>
  <v-card :max-width="maxWidth" width="100%" :tile="$vuetify.display.xs" class="mx-auto">
    <v-toolbar
      v-if="back || !$vuetify.display.mdAndUp || toolbar"
      class="ec-content-card__toolbar"
      :class="{ 'ec-content-card__toolbar--border': !noBorder }"
      elevation="0"
      color="surface"
      density="compact"
    >
      <v-toolbar-items>
        <button-back
          v-if="back || (!$vuetify.display.mdAndUp && !!$route.query.isDetail)"
        />
      </v-toolbar-items>

      <slot name="title">
        <v-toolbar-title tag="h1" class="font-weight-bold">
          {{ title }}
        </v-toolbar-title>
      </slot>
      <v-spacer />

      <template v-if="!loaded" #append>
        <v-skeleton-loader type="button" width="40" class="mr-2" />
      </template>
      <slot v-if="loaded" name="title-actions" />
    </v-toolbar>

    <!-- main content -->
    <v-sheet class="ec-content-card__content fill-height">
      <v-skeleton-loader v-if="!loaded" type="article" class="pa-4" />
      <slot v-else />
    </v-sheet>
  </v-card>
</template>

<script>
import ButtonBack from '@/components/buttons/ButtonBack.vue'

export default {
  name: 'ContentCard',
  components: {
    ButtonBack,
  },
  props: {
    loaded: { type: Boolean, required: false, default: true },
    title: { type: String, required: false, default: '' },
    toolbar: { type: Boolean, required: false, default: false },
    noBorder: { type: Boolean, required: false, default: false },
    back: { type: Boolean, required: false, default: false },
    maxWidth: { type: String, default: '' },
  },
}
</script>

<style scoped lang="scss">
@use 'vuetify/settings';
@use 'sass:map';

.ec-content-card__toolbar {
  @media #{map.get(settings.$display-breakpoints, 'xs')} {
    position: sticky;
    top: 0;
    z-index: 5;
  }
}

.ec-content-card__toolbar--border {
  border-bottom: 1px solid rgba(var(--v-border-color), 0.12) !important;
}
</style>
