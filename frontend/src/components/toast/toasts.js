import MultiLineToast from '@/components/toast/MultiLineToast.vue'
import { componentI18n } from '@/plugins/i18n'
import { violationsToFlatArray } from '@/helpers/serverError'

function multiLineToast(lines) {
  return {
    component: MultiLineToast,
    props: {
      lines,
      generalErrorText: componentI18n.t(
        'components.toast.toasts.multiLineToast.generalError'
      ),
    },
  }
}

function errorToMultiLineToast(error) {
  return multiLineToast(violationsToFlatArray(error, componentI18n))
}

export { errorToMultiLineToast, multiLineToast }
