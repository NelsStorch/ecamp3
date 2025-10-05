<!--
Displays a field as a textfield (can be used with v-model)
-->

<template>
  <Field
    v-slot="{ handleChange, errors: veeErrors }"
    ref="validationField"
    :name="veeId ?? path"
    :label="validationLabel"
    :rules="veeRules"
  >
    <v-text-field
      v-bind="$attrs"
      ref="textField"
      :class="[inputClass]"
      :error-messages="(veeErrors ?? []).concat(combinedErrorMessages)"
      :filled="filled"
      :hide-details="hideDetails"
      :label="labelOrEntityFieldLabel"
      :required="required"
      :model-value="stringValue"
      type="text"
      @blur="onBlur($event, handleChange)"
      @update:model-value="onInput"
    >
      <template #prepend>
        <slot
          :internal-value="internalValue"
          :serialized-value="serializedValue"
          :string-value="stringValue"
          name="prepend"
        />
      </template>
      <template #append>
        <slot
          :internal-value="internalValue"
          :serialized-value="serializedValue"
          :string-value="stringValue"
          name="append"
        />
      </template>
    </v-text-field>
  </Field>
</template>

<script>
import { debounce } from 'lodash-es'
import { formComponentPropsMixin } from '@/mixins/formComponentPropsMixin.js'
import { Field } from 'vee-validate'
import { formComponentMixin } from '@/mixins/formComponentMixin.js'

export default {
  name: 'EParseField',
  components: {
    Field,
  },
  mixins: [formComponentPropsMixin, formComponentMixin],
  props: {
    modelValue: { validator: () => true, required: true },
    readonly: { type: Boolean, required: false, default: false },
    disabled: { type: Boolean, required: false, default: false },
    errorMessages: { type: Array, required: false, default: () => [] },

    /**
     * Debounce the parse function to avoid unnecessary calls and invalid values
     */
    debouncedParse: { type: Boolean, default: false },

    resetOnBlur: { type: Boolean, default: false },

    /**
     * Format internal value to string
     */
    format: { type: Function, required: false, default: null },

    /**
     * Parse a user-supplied value into the internal value type
     */
    parse: { type: Function, required: false, default: null },

    /**
     * Serialize the internal value for output
     */
    serialize: { type: Function, required: false, default: null },

    /**
     * Deserialize the serialized value into the internal value type
     */
    deserialize: { type: Function, required: false, default: null },

    /**
     * Override the default serialized values comparison function
     */
    compare: { type: Function, required: false, default: (a, b) => a === b },

    /**
     * Remove unwanted characters from the input value
     */
    inputFilter: { type: Function, required: false, default: null },
  },
  emits: ['update:model-value'],
  data() {
    return {
      /**
       * string displayed in the text field
       */
      stringValue: null,

      /**
       * parsed value (parsed type or null)
       */
      internalValue: null,

      /**
       * serialized value (for output)
       */
      serializedValue: null,

      intermediateParseError: null,
      parseError: null,

      /**
       * note that it is necessary to debounce in data to have one debounced function per instance, whereas
       * debouncing in watch or methods results in one global debounced function which has unwanted effects
       * when there are multiple instances rendered at the same time
       */
      debounceParseValue: debounce(this.parseValue, 500),
    }
  },
  computed: {
    combinedErrorMessages() {
      if (this.parseError == null) {
        return this.errorMessages
      }
      return [...this.errorMessages, this.parseError.message]
    },
  },
  watch: {
    modelValue: {
      handler(val) {
        console.log('wathch:modelValue')
        console.log(val)
        // if the value is the same, we don't need to parse it again
        this.parseError = null
        this.intermediateParseError = null
        if (this.compare(val, this.serializedValue)) {
          return
        }
        this.serializedValue = val
        this.internalValue = this.deserialize?.(val) ?? val
        this.stringValue = this.format?.(this.internalValue) ?? val
      },
      immediate: true,
    },
  },
  methods: {
    onInput(value) {
      console.log('onInput')
      console.log(value)
      if (this.inputFilter) {
        value = this.inputFilter(value)
        this.stringValue = value
        // TODO: is the next line still needed (haven't found any documentation on lazyValue)
        //this.$refs.textField.lazyValue = value
      } else {
        this.stringValue = value
      }
      if (this.debouncedParse) {
        this.debounceParseValue(value)
      } else {
        this.parseValue(value)
      }
    },
    onBlur(event, handleChange) {
      console.log('onBlur')
      console.log(event)
      if (this.resetOnBlur && !this.intermediateParseError) {
        this.stringValue = this.format?.(this.internalValue) ?? this.internalValue
        // TODO: is the next line still needed (haven't found any documentation on lazyValue)
        //this.$refs.textField.lazyValue = this.stringValue
      }
      this.parseError = this.intermediateParseError
      //
      //this.$refs.validationProvider.validate(this.serializedValue)
      handleChange(this.serializedValue)
      console.log('trigger validation')
      console.log(this.serializedValue)
      this.$emit('blur', event)
    },
    setValue(val) {
      console.log('setValue')
      console.log(val)
      if (!this.compare(this.serializedValue, this.serialize?.(val) ?? val)) {
        this.internalValue = val

        this.serializedValue = this.serialize?.(val) ?? val
        this.$emit('update:model-value', this.serialize?.(val) ?? val)
      }
    },
    async parseValue(val) {
      console.log('parseVal')
      console.log(val)
      try {
        if (this.parse != null) {
          val = await this.parse(val)
        }
        this.setValue(val)
        this.parseError = null
        this.intermediateParseError = null
      } catch (error) {
        this.intermediateParseError = error
      }
    },
    focus() {
      this.$refs.textField.focus()
    },
  },
}
</script>
