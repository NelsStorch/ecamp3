<template>
  <v-btn color="error" v-bind="$attrs" v-on="$listeners">
    <v-icon start icon="mdi-refresh" />
    {{ $t('global.button.tryagain') }}
  </v-btn>
</template>

<script>
export default {
  name: 'ButtonRetry',
}
</script>
