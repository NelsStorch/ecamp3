import { config } from '@vue/test-utils'
import { vuetify } from '@/plugins/vuetify.js'

export function setupVuetify() {
  config.global.plugins = [vuetify]
}
