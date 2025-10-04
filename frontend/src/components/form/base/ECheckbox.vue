<template>
  <Field
    :name="validationLabel"
    :rules="veeRules"
    v-slot="{ handleChange, errors: veeErrors }"
  >
    <v-checkbox
      :id="id"
      :class="[inputClass]"
      :error-messages="(veeErrors ?? []).concat(errorMessages)"
      :hide-details="hideDetails"
      :label="labelOrEntityFieldLabel"
      :model-value="modelValue"
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
    </v-checkbox>
  </Field>
</template>

<script>
import { formComponentPropsMixin } from '@/mixins/formComponentPropsMixin.js'
import { formComponentMixin } from '@/mixins/formComponentMixin.js'
import { Field } from 'vee-validate'

export default {
  name: 'ECheckbox',
  components: {
    Field,
  },
  mixins: [formComponentPropsMixin, formComponentMixin],
  props: {
    modelValue: { type: Boolean, required: false },
  },
}
</script>

<style scoped>
[required]:deep(label::after) {
  content: '\a0*';
  font-size: 12px;
  color: #d32f2f;
}

[required]:deep(.v-input--is-label-active label::after) {
  color: gray;
}
</style>
