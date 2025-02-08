import { firstActivityScheduleEntry } from '@/router.js'
import { apiStore } from '@/plugins/store'

/**
 * Loads first valid schedule entry for activity if current one is missing
 * @param activity
 * @param to
 * @param from
 * @param next
 * @return {Promise<*>}
 */
export default async function scheduleEntryRouteChange(activity, to, from, next) {
  if (to.params.scheduleEntryId !== from.params.scheduleEntryId) {
    return await apiStore
      .get()
      .scheduleEntries({ id: to.params.scheduleEntryId })
      ._meta.load.then(() => next())
      .catch(async () => {
        return next({
          name: 'camp/activity',
          params: {
            ...to.params,
            scheduleEntryId: (await firstActivityScheduleEntry(activity)).id,
          },
        })
      })
  } else {
    return next()
  }
}
