<template>
  <v-list-item :disabled="loading" @click.stop="generatePdf">
    <template #prepend>
      <v-icon v-if="loading" class="mdi-spin">mdi-loading</v-icon>
      <v-icon v-else>mdi-printer</v-icon>
    </template>
    <v-list-item-title>
      {{ $t('components.print.printNuxt.downloadNuxtPdfListItem.label') }}
    </v-list-item-title>
  </v-list-item>
</template>

<script>
import { generatePdfMixin } from './generatePdfMixin.js'

export default {
  name: 'DownloadNuxtPdfListItem',
  mixins: [generatePdfMixin],
}
</script>
