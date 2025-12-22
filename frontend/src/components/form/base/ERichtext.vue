<template>
  <Field
    v-slot="{ handleChange, errors: veeErrors }"
    :model-value="modelValue"
    as="div"
    :label="validationLabel"
    :name="veeId ?? path ?? validationLabel"
    :rules="veeRules"
    class="e-form-container"
  >
    <v-tiptap-editor
      :class="[inputClass]"
      :error-messages="(veeErrors ?? []).concat(errorMessages)"
      :filled="filled"
      :hide-details="hideDetails"
      :label="labelOrEntityFieldLabel"
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
  </Field>
</template>

<script>
import { Field } from 'vee-validate'
import { formComponentPropsMixin } from '@/mixins/formComponentPropsMixin.js'
import VTiptapEditor from '@/components/form/tiptap/VTiptapEditor.vue'
import { formComponentMixin } from '@/mixins/formComponentMixin.js'

export default {
  name: 'ERichtext',
  components: {
    Field,
    VTiptapEditor,
  },
  mixins: [formComponentPropsMixin, formComponentMixin],
}
</script>
