<template>
  <ValidationField
    v-slot="{ handleChange, errors: veeErrors }"
    :label="validationLabel"
    :name="veeId ?? path ?? validationLabel"
    :rules="veeRules"
  >
    <v-tiptap-editor
      :class="[inputClass]"
      :error-messages="(veeErrors ?? []).concat(errorMessages)"
      :hide-details="hideDetails"
      :on-input="
        ($event) => {
          handleChange($event)
          $emit('update:model-value', $event)
        }
      "
      :with-extensions="true"
      v-bind="$attrs"
    >
      <!-- passing through all slots -->
      <template v-for="(_, slot) of $slots" #[slot]="slotData">
        <slot :name="slot" v-bind="slotData || {}"></slot>
      </template>
    </v-tiptap-editor>
  </ValidationField>
</template>

<script>
import { formComponentPropsMixin } from '@/mixins/formComponentPropsMixin.js'
import VTiptapEditor from '@/components/form/tiptap/VTiptapEditor.vue'
import { formComponentMixin } from '@/mixins/formComponentMixin.js'
import ValidationField from './ValidationField.vue'

export default {
  name: 'ERichtext',
  components: {
    ValidationField,
    VTiptapEditor,
  },
  mixins: [formComponentPropsMixin, formComponentMixin],
}
</script>
