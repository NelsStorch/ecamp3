import { useHead } from '@unhead/vue'
import { createHead } from '@unhead/vue/client'
import { getEnv } from '@/environment.js'

const env = getEnv().SENTRY_ENVIRONMENT.split('.')[0]
const environment =
  env === 'app' || env === ''
    ? null
    : env.match('^pr[0-9]+')
      ? `[${env.toUpperCase()}]`
      : `[${env.substring(0, 1).toUpperCase() + env.substring(1)}]`

export const unhead = createHead()
//
// useHead({
//   title: null,
//   templateParams: {
//     site: 'eCamp v3',
//     separator: '·',
//     environment,
//     section: null,
//   },
//   titleTemplate: '%environment %s %separator %section %separator %site',
// })
