<script>
import { h, reactive, toRefs, toRef, computed } from 'vue'
import { VField } from 'vuetify/components'
import TiptapEditor from './TiptapEditor.vue'

export default {
  name: 'VTiptapEditor',
  components: {
    TiptapEditor,
  },
  extends: VField,
  props: {
    withExtensions: {
      type: Boolean,
      default: false,
    },
    onInput: {
      type: Function,
      required: true,
    },
    modelValue: {
      type: String,
      default: '',
    },
    placeholder: {
      type: String,
      default: '',
    },
    readonly: {
      type: Boolean,
      default: false,
    },
    disabled: {
      type: Boolean,
      default: false,
    },
  },
  setup(props, ctx) {
    const readonlyRef = toRef(() => props.readonly)
    const disabledRef = toRef(() => props.disabled)
    const tiptap = () =>
      h(
        TiptapEditor,
        reactive({
          ...toRefs(props),
          ...ctx.attrs,
          editable: computed(() => !readonlyRef.value && !disabledRef.value),
        }),
        ctx.slots
      )
    return VField.setup(
      reactive({
        ...ctx.attrs,
        loading: toRef(() => props.loading),
      }),
      { ...ctx, slots: { default: tiptap, ...ctx.slots } }
    )
  },
}
</script>

<style scoped>
div.v-text-field--solo div.v-input__slot {
  align-items: normal;
}

div.v-text-field__slot {
  align-items: normal;
  width: 100%;
}

.v-text-field.v-text-field--box.v-text-field--single-line:not(.v-input--dense) .editor,
.v-text-field.v-text-field--box.v-text-field--outlined:not(.v-input--dense) .editor,
.v-text-field.v-text-field--enclosed.v-text-field--single-line:not(.v-input--dense)
  .editor,
.v-text-field.v-text-field--enclosed.v-text-field--outlined:not(.v-input--dense) .editor {
  margin-top: 10px;
  margin-bottom: 10px;
  display: flex;
  align-items: baseline;
}
</style>
