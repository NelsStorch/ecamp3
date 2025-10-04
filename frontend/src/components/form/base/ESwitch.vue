<template>
  <Field
    :name="validationLabel"
    :rules="veeRules"
    v-slot="{ handleChange, errors: veeErrors }"
  >
    <v-switch
      :class="[inputClass]"
      :error-messages="(veeErrors ?? []).concat(errorMessages)"
      :hide-details="hideDetails"
      :label="labelOrEntityFieldLabel"
      :model-value="modelValue"
      inset
      color="primary"
      v-bind="$attrs"
      @update:model-value="
        ($event) => {
          handleChange($event)
          $emit('input', $event)
        }
      "
    >
      <!-- passing through all slots -->
      <template v-for="(_, slot) of $slots" #[slot]="slotData">
        <slot :name="slot" v-bind="slotData || {}"></slot>
      </template>
    </v-switch>
  </Field>
</template>

<script>
import { Field } from 'vee-validate'
import { formComponentPropsMixin } from '@/mixins/formComponentPropsMixin.js'
import { formComponentMixin } from '@/mixins/formComponentMixin.js'

export default {
  name: 'ESwitch',
  components: {
    Field,
  },
  mixins: [formComponentPropsMixin, formComponentMixin],
  props: {
    modelValue: { type: Boolean, required: false },
  },
}
</script>
