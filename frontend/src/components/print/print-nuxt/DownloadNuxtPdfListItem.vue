<template>
  <v-list-item :disabled="loading" @click.stop="generatePdf">
    <v-list-item-title>
      <v-icon v-if="loading" start class="mdi-spin">mdi-loading</v-icon>
      <v-icon v-else start>mdi-printer</v-icon>
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
