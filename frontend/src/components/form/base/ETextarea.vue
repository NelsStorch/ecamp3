<template>
  <Field
    v-slot="{ handleChange, errors: veeErrors }"
    :label="validationLabel"
    :name="veeId ?? path"
    :rules="veeRules"
  >
    <v-tiptap-editor
      :class="[inputClass]"
      :error-messages="(veeErrors ?? []).concat(errorMessages)"
      :hide-details="hideDetails"
      :label="labelOrEntityFieldLabel"
      :with-extensions="false"
      :on-input="
        ($event) => {
          handleChange($event)
          $emit('update:model-value', $event)
        }
      "
      v-bind="$attrs"
    >
      <!-- passing through all slots -->
      <template v-for="(_, slot) of $slots" #[slot]="slotData">
        <slot :name="slot" v-bind="slotData || {}"></slot>
      </template>
    </v-tiptap-editor>
  </Field>
</template>

<script>
import { Field } from 'vee-validate'
import { formComponentPropsMixin } from '@/mixins/formComponentPropsMixin.js'
import VTiptapEditor from '@/components/form/tiptap/VTiptapEditor.vue'
import { formComponentMixin } from '@/mixins/formComponentMixin.js'

export default {
  name: 'ETextarea',
  components: {
    Field,
    VTiptapEditor,
  },
  mixins: [formComponentPropsMixin, formComponentMixin],
}
</script>
