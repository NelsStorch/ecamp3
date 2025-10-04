import { isEqual } from 'lodash-es'

export default function repairFilterConfig(filter, camp) {
  if (!filter || typeof filter !== 'object') {
    return {
      category: [],
      day: [],
      responsible: [],
      progressLabel: [],
    }
  }
  if (!filter.period) filter.period = null
  const knownPeriods = camp.periods().items.map((p) => p._meta.self)
  if (!knownPeriods.includes(filter.period)) filter.period = null
  if (!filter.day) filter.day = []
  const knownDays = camp
    .periods()
    .items.flatMap((period) => period.days().items)
    .map((d) => d._meta.self)
  filter.day = filter.day.filter((day) => {
    return knownDays.includes(day)
  })
  if (!filter.category) filter.category = []
  const knownCategories = camp.categories().items.map((c) => c._meta.self)
  filter.category = filter.category.filter((category) => {
    return knownCategories.includes(category)
  })
  if (!filter.responsible) filter.responsible = []
  const knownCampCollaborations = camp.campCollaborations().items.map((c) => c._meta.self)
  if (!isEqual(filter.responsible, ['none'])) {
    filter.responsible = filter.responsible.filter((responsible) => {
      return knownCampCollaborations.includes(responsible)
    })
  }
  if (!filter.progressLabel) filter.progressLabel = []
  const knownProgressLabels = camp.progressLabels().items.map((l) => l._meta.self)
  filter.progressLabel = filter.progressLabel.filter((progressLabel) => {
    return knownProgressLabels.includes(progressLabel) || 'none' === progressLabel
  })
  if (!filter.activityCount) filter.activityCount = 0
  return filter
}
