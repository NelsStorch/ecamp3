<template>
  <div class="e-form-container">
    <Field
      v-slot="{ errors: veeFieldErrors }"
      :name="veeId ?? path"
      :label="validationLabel"
      :rules="veeRules"
    >
      <v-text-field
        ref="textField"
        :class="[inputClass]"
        :error-messages="(veeFieldErrors ?? []).concat(errorMessages)"
        :label="labelOrEntityFieldLabel"
        :model-value="inputValue"
        :type="type"
        v-bind="$attrs"
        :filled="filled"
        :required="required"
        :hide-details="hideDetails"
        @blur="handleBlur"
        @update:model-value="handleUpdate"
      >
        <!-- passing through all slots -->
        <template v-for="(_, slot) of $slots" #[slot]="slotData">
          <slot :name="slot" v-bind="slotData || {}"></slot>
        </template>
      </v-text-field>
    </Field>
  </div>
</template>

<script setup>
import { inject, watch } from 'vue'
import { Field, useField } from 'vee-validate'
import { useI18n } from 'vue-i18n'

import { props as formComponentProps, useFormComponent } from './formComponentProps'
import {
  props as formComponentValidationProps,
  useValidation,
} from './formComponentValidation'

const { t } = useI18n()

const entityName = inject('entityName', null)

const emit = defineEmits(['input'])

const props = defineProps({
  ...formComponentProps,
  ...formComponentValidationProps,
  /**
   * additional props for ETextField
   */
  type: {
    type: String,
    default: 'text',
  },
})

const { required } = useValidation(props.veeRules)
const { labelOrEntityFieldLabel, validationLabel } = useFormComponent(
  props.label,
  props.path,
  props.validationLabelOverride,
  entityName,
  t
)

const {
  value: inputValue,
  handleBlur,
  handleChange,
  setValue,
} = useField(props.veeId ?? props.path, props.veeRules, {
  initialValue: props.modelValue,
  label: validationLabel,
})

const handleUpdate = (value) => {
  handleChange(value)
  emit('update:model-value', value)
}

watch(
  () => props.modelValue,
  async (newValue, _) => {
    setValue(newValue)
  }
)
</script>

<!-- <script>
import { Field } from 'vee-validate'
import { formComponentPropsMixin } from '@/mixins/formComponentPropsMixin.js'
import { formComponentMixin } from '@/mixins/formComponentMixin.js'

export default {
  name: 'ETextField',
  components: {
    Field,
  },
  data() {
    return {
      preventValidationOnBlur: false,
    }
  },
  computed: {
    inputListeners: function () {
      const vm = this
      return Object.assign(
        {},
        // attach all $parent listeners
        this.$listeners,
        // override @input listener for correct handling of numeric values
        {
          input: function (value) {
            vm.$data.preventValidationOnBlur = false
            vm.$emit('input', value)
          },
          blur: function () {
            vm.$emit('blur')
            if (vm.$data.preventValidationOnBlur) {
              vm.$refs.validationProvider.reset()
            }
            vm.$data.preventValidationOnBlur = false
          },
        }
      )
    },
  },
  mounted() {
    this.preventValidationOnBlur =
      'autofocus' in this.$attrs &&
      /*'required' in this.$refs.validationProvider.$attrs && */
      this.$refs.textField.value == ''
  },
  methods: {
    focus() {
      this.$refs.textField.focus()
    },
    isRequired(value) {
      if (value && value.trim()) {
        return true
      }
      return 'This is required'
    },
  },
}
</script> -->

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
