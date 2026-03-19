<template>
  <v-list-item
    :readonly="loading"
    :lines="loading ? 'two' : 'one'"
    @click.stop="generatePdf"
  >
    <v-list-item-title>
      <v-progress-circular
        v-if="loading"
        :model-value="progress"
        :rotate="0"
        size="24"
        color="primary"
        class="mr-2"
      />
      <v-icon v-else start icon="mdi-printer" />
      {{ $t('components.print.printClient.downloadClientPdfListItem.label') }}
    </v-list-item-title>
    <v-list-item-subtitle v-if="loading">
      {{ state }}
    </v-list-item-subtitle>
  </v-list-item>
</template>

<script>
import { generatePdfMixin } from './generatePdfMixin.js'
import { useToast } from 'vue-toastification'

export default {
  name: 'DownloadClientPdfListItem',
  mixins: [generatePdfMixin],
  setup() {
    const toast = useToast()
    return { toast }
  },
}
</script>

<style scoped></style>
