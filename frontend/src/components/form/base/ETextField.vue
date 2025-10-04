<template>
  <div class="e-form-container">
    <v-text-field
      :id="name"
      ref="textField"
      :class="[inputClass]"
      :error-messages="errorMessage"
      :label="label || name"
      :model-value="inputValue"
      :name="name"
      :type="type"
      v-bind="$attrs"
      :filled="filled"
      :required="required"
      :hide-details="hideDetails"
      @blur="handleBlur"
      @update:model-value="handleChange"
    >
      <!-- passing through all slots -->
      <template v-for="(_, name) in $slots" #[name]="slotData">
        <slot :name="name" v-bind="slotData" />
      </template>
    </v-text-field>
  </div>
</template>

<script setup>
import { toRef } from 'vue'
import { useField } from 'vee-validate'

import { props as formComponentProps } from './formComponentProps'
import {
  props as formComponentValidationProps,
  useValidation,
} from './formComponentValidation'

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

const name = toRef(props, 'name')
const rules = toRef(props, 'veeRules')

const {
  value: inputValue,
  errorMessage,
  errors,
  handleBlur,
  handleChange,
  meta,
} = useField(name, rules, {
  initialValue: props.modelValue,
})
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
