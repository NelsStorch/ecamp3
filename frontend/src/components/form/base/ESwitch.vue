<template>
  <!--  <ValidationProvider-->
  <!--    v-slot="{ errors: veeErrors }"-->
  <!--    :name="validationLabel"-->
  <!--    :vid="veeId"-->
  <!--    :rules="veeRules"-->
  <!--    :required="required"-->
  <!--  >-->
  <v-switch
    :class="[inputClass]"
    :error-messages="(veeErrors ?? []).concat(errorMessages)"
    :hide-details="hideDetails"
    :label="labelOrEntityFieldLabel"
    :model-value="value"
    inset
    v-bind="$attrs"
    @update:model-value="$emit('input', $event)"
  >
    <!-- passing through all slots -->
    <template v-for="(_, slot) of $slots" #[slot]="slotData">
      <slot :name="slot" v-bind="slotData || {}"></slot>
    </template>
  </v-switch>
  <!--  </ValidationProvider>-->
</template>

<script>
// import { ValidationProvider } from 'vee-validate'
import { formComponentPropsMixin } from '@/mixins/formComponentPropsMixin.js'
import { formComponentMixin } from '@/mixins/formComponentMixin.js'

export default {
  name: 'ESwitch',
  components: {
    // ValidationProvider
  },
  mixins: [formComponentPropsMixin, formComponentMixin],
  props: {
    value: { type: Boolean, required: false },
  },
}
</script>
