<template>
  <ValidationProvider
    v-slot="{ errors: veeErrors }"
    :name="validationLabel"
    :vid="veeId"
    :rules="veeRules"
  >
    <component
      :is="inputComponent"
      v-bind="$attrs"
      :hide-details="hideDetails"
      :error-messages="veeErrors.concat(errorMessages)"
      :label.prop="labelOrEntityFieldLabel"
      :class="[inputClass]"
    >
      <!-- passing through all slots -->
      <template v-for="(_, slot) of $slots" #[slot]="slotData">
        <slot :name="slot" v-bind="slotData || {}"></slot>
      </template>
    </component>
  </ValidationProvider>
</template>

<script>
import { ValidationProvider } from 'vee-validate'
import { formComponentPropsMixin } from '@/mixins/formComponentPropsMixin.js'
import { VTextField } from 'vuetify/components'

export default {
  name: 'BaseComponent',
  components: { ValidationProvider, VTextField },
  mixins: [formComponentPropsMixin],
  props: {
    inputComponent: {
      type: String,
      required: true,
    },
  },
}
</script>
