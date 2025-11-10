import { computed } from 'vue'

export const props = {
  modelValue: {
    type: String,
    default: undefined,
  },

  id: {
    type: String,
    required: false,
    default: null,
  },

  // vuetify property hideDetails
  filled: {
    type: Boolean,
    default: true,
  },

  // vuetify property hideDetails
  hideDetails: {
    type: [String, Boolean],
    default: 'auto',
  },

  // set classes on input
  inputClass: {
    type: String,
    default: '',
    required: false,
  },

  /**
   * used as field path for validation
   * and together with entityName as label (if no override label is provided)
   */
  path: {
    type: String,
    required: true,
    default: null,
  },

  /**
   * override the automatic entity field label
   */
  label: {
    type: String,
    required: false,
    default: null,
  },

  /**
   * override the automatic validation field name
   */
  validationLabelOverride: {
    type: String,
    required: false,
    default: null,
  },

  // error messages from outside which should be displayed on the component
  errorMessages: {
    type: Array,
    required: false,
    default: () => [],
  },
}

export function useFormComponent(label, path, validationLabelOverride, entityName, t) {
  const labelOrEntityFieldLabel = computed(() => {
    if (label !== undefined && label !== null) {
      return label
    }
    if (!entityName || !path) {
      return null
    }
    return t(`entity.${entityName}.fields.${path}`)
  })

  const validationLabel = computed(() => {
    if (validationLabelOverride) {
      return validationLabelOverride
    }
    if (label) {
      return label
    }

    return t(`entity.${entityName}.fields.${path}`)
  })

  return { labelOrEntityFieldLabel, validationLabel }
}
