export default (i18n) =>
  /**
   * @param {string} value value of a float number
   * @param number min   Comparison value (interpreted as float)
   * @param {string} label Field label
   * @returns {boolean}       validation result
   */
  (value, { min }, { label }) => {
    const validate = parseFloat(value) > min

    if (validate) {
      return true
    }

    return i18n.global.t('global.validation.greaterThan', {
      min: min,
      field: label,
    })
  }
