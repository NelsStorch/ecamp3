export default (i18n) =>
  /**
   * @param {string} value value of a float number
   * @param number min   Comparison value (interpreted as float)
   */
  (value, [min], ctx) => {
    const validate = parseFloat(value) > min

    if (validate) {
      return true
    }

    return i18n.global.t('global.validation.greaterThan', {
      min: min,
      field: ctx.label,
    })
  }
