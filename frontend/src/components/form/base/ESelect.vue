<template>
  <Field
    v-slot="{ handleChange, errors: veeErrors }"
    as="div"
    :name="veeId ?? path ?? validationLabel"
    :label="validationLabel"
    :rules="veeRules"
    class="e-form-container"
  >
    <v-select
      :class="[inputClass]"
      :error-messages="(veeErrors ?? []).concat(errorMessages)"
      :hide-details="hideDetails"
      :label="labelOrEntityFieldLabel"
      v-bind="$attrs"
      :readonly="readonly"
      :menu-icon="readonly ? null : '$dropdown'"
      item-title="text"
      item-value="value"
      @update:model-value="
        ($event) => {
          handleChange($event)
          $emit('update:model-value', $event)
        }
      "
    >
      <!-- passing through all slots -->
      <template v-for="(_, slot) of $slots" #[slot]="slotData">
        <slot :name="slot" v-bind="slotData || {}"></slot>
      </template>
    </v-select>
  </Field>
</template>

<script>
import { Field } from 'vee-validate'
import { formComponentPropsMixin } from '@/mixins/formComponentPropsMixin.js'
import { formComponentMixin } from '@/mixins/formComponentMixin.js'

export default {
  name: 'ESelect',
  components: {
    Field,
  },
  mixins: [formComponentPropsMixin, formComponentMixin],
  props: {
    // TODO: implement immediateValidation
    immediateValidation: { type: Boolean, default: false },
    // TODO: implement skipIfEmpty
    skipIfEmpty: { type: Boolean, default: true },
    readonly: { type: Boolean, default: false },
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
