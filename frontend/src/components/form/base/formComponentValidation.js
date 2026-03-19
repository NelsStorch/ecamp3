import { computed } from 'vue'

export const props = {
  // ID for vee-validation
  veeId: {
    type: String,
    required: false,
    default: null,
  },

  // rules for vee-validation
  veeRules: {
    type: [String, Object],
    required: false,
    default: '',
  },
}

export function useValidation(rules) {
  const required = computed(() => {
    if ('object' === typeof rules) {
      return Object.keys(rules).includes('required')
    }
    return rules
      .split('|')
      .map((rule) => rule.replace(/:.*/, ''))
      .includes('required')
  })

  return { required }
}
