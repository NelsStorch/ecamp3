<!--
Displays a field as a color picker (can be used with v-model)
-->
<template>
  <div
    v-click-outside="{ handler: closePicker, closeConditional: closePickerConditional }"
    class="e-form-container"
  >
    <v-menu
      v-model="pickerOpen"
      transition="scale-transition"
      offset-y
      offset-overflow
      :open-on-click="false"
      persistent
      :close-on-content-click="false"
      min-width="290px"
      max-width="290px"
      @update:model-value="onPickerClose"
    >
      <template #activator="{ props }">
        <div v-bind="props">
          <EColorField
            v-bind="$attrs"
            :id="id"
            ref="input"
            :model-value="pickerValue"
            :vee-id="veeId"
            :vee-rules="veeRules"
            :filled="filled"
            :hide-details="hideDetails"
            :input-class="inputClass"
            :required="required"
            :path="path"
            :label="label"
            :validation-label-override="validationLabelOverride"
            :error-messages="errorMessages"
            @blur="onBlur"
            @update:model-value="onInput($event)"
            @click.prevent="onInputClick"
          >
            <template #prepend="{ serializedValue }">
              <ColorSwatch
                ref="inputSwatch"
                :color="serializedValue"
                class="mt-n1"
                :aria-label="
                  pickerOpen
                    ? $t('components.form.base.eColorPicker.closePicker')
                    : $t(
                        'components.form.base.eColorPicker.openPicker',
                        { label: labelOrEntityFieldLabel },
                        0
                      )
                "
                aria-haspopup="true"
                :aria-expanded="pickerOpen ? 'true' : 'false'"
                @click="onInputSwatchClick"
              />
            </template>
            <template #append>
              <slot name="append" />
            </template>
          </EColorField>
        </div>
      </template>
      <v-card ref="picker" :ripple="false" tabindex="-1" data-testid="colorpicker">
        <v-color-picker
          v-if="pickerNull"
          key="model"
          model-value="#FF0000"
          :style="{ '--picker-contrast-color': contrast }"
          flat
          @update:model-value="onPickerInput($event.hex)"
        />
        <v-color-picker
          v-else
          key="null"
          :model-value="pickerValue"
          :style="{ '--picker-contrast-color': contrast }"
          flat
          @update:model-value="debouncedPickerValue($event.hex)"
        />
        <v-divider />
        <div class="d-flex gap-2 pa-4 flex-wrap">
          <ColorSwatch
            v-for="swatch in swatchesWithReset"
            :key="swatch"
            :color="swatch"
            @select-color="onSwatchSelect($event)"
          />
          <ColorSwatch
            v-if="!required"
            color="#000000"
            class="reset"
            @select-color="onSwatchSelect(null)"
          />
        </div>
      </v-card>
    </v-menu>
  </div>
</template>

<script>
import { formComponentMixin } from '@/mixins/formComponentMixin.js'
import { contrastColor } from '@/common/helpers/colors.js'
import ColorSwatch from '@/components/form/base/ColorPicker/ColorSwatch.vue'
import { debounce } from 'lodash-es'
import { formComponentPropsMixin } from '@/mixins/formComponentPropsMixin.js'

export default {
  name: 'EColorPicker',
  components: { ColorSwatch },
  mixins: [formComponentMixin, formComponentPropsMixin],
  inheritAttrs: false,
  props: {
    modelValue: { type: String, required: false, default: null },
  },
  emits: ['input'],
  data: () => ({
    pickerOpen: false,
    pickerValue: null,
    pickerNull: false,
    debouncedPickerValue: null,
    swatches: [
      '#90B7E4',
      '#6EDBE9',
      '#4DBB52',
      '#FF9800',
      '#FD7A7A',
      '#D584E9',
      '#BBBBBB',

      '#1964B1',
      '#1E86CA',
      '#3DB842',
      '#F1810D',
      '#C71A1A',
      '#CF3BD6',
      '#575757',
    ],
  }),
  computed: {
    contrast() {
      try {
        // Vuetify returns invalid value #NANNAN in the initialization phase
        return this.modelValue && this.modelValue !== '#NANNAN'
          ? contrastColor(this.modelValue)
          : 'black'
      } catch {
        return 'black'
      }
    },
    swatchesWithReset() {
      return this.required
        ? this.swatches
        : this.swatches.slice(0, this.swatches.length - 1)
    },
  },
  watch: {
    modelValue: {
      handler(newValue) {
        console.log('watch modelValue')
        this.pickerValue = newValue
        this.pickerNull = [null, ''].includes(newValue)
      },
      immediate: true,
    },
    pickerOpen: {
      handler(open) {
        if (!open) {
          this.onPickerClose()
        }
      },
    },
  },
  created() {
    this.debouncedPickerValue = debounce(this.onPickerInput, 300)
  },
  mounted() {
    document.addEventListener('keydown', this.escapeHandler)
  },
  beforeUnmount() {
    document.removeEventListener('keydown', this.escapeHandler)
  },
  methods: {
    onInputClick() {
      this.activator = 'input'
      this.pickerOpen = true
    },
    onInputSwatchClick() {
      this.activator = 'swatch'
      this.pickerOpen = !this.pickerOpen
      if (this.pickerOpen) {
        setTimeout(() => {
          this.$refs.picker?.$el.focus()
        }, 100)
      }
    },
    onInput(value) {
      this.pickerValue = value
      this.$emit('update:model-value', this.pickerValue)
    },
    onBlur(event) {
      if (!this.pickerOpen) {
        this.$emit('blur', event)
      }
    },
    onPickerInput(value) {
      this.pickerValue = value.toUpperCase()
      this.pickerNull = false
      this.$emit('update:model-value', this.pickerValue)
    },
    onSwatchSelect(color) {
      this.pickerValue = color
      this.$emit('update:model-value', this.pickerValue)
      this.pickerOpen = false
      this.$refs.inputSwatch.$el.focus()
    },
    closePicker() {
      this.pickerOpen = false
    },
    closePickerConditional(event) {
      return this.pickerOpen && !this.$refs.picker?.$el.contains(event.target)
    },
    onPickerClose() {
      switch (this.activator) {
        case 'input':
          this.$refs.input.focus()
          break
        case 'swatch':
          this.$refs.inputSwatch.$el.focus()
          break
      }
      this.$emit('blur')
    },
    escapeHandler(event) {
      switch (event.code) {
        case 'Escape':
          this.closePicker()
          break
      }
    },
  },
}
</script>

<style scoped>
:deep(.v-color-picker__dot > div::before) {
  content: '•';
  color: var(--picker-contrast-color);
  display: block;
  width: 100%;
  line-height: 26px;
  font-size: 28px;
  text-align: center;
}

:deep(.e-colorswatch.reset::after) {
  content: '×';
}
</style>
