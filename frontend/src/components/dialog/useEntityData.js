import { reactive, ref } from 'vue'

export function useEntityData() {
  const entityData = reactive({})
  const loading = ref(true)

  // specifies entity properties available in the form / dialog
  const entityProperties = ref([])

  // specifies single-select related entities (e.g. activity.category) available in the form / dialog.
  // suitable for ManyToOne relations. Property is sent as IRI to the API.
  const embeddedEntities = ref([])

  // specifies multi-select related entities (e.g. category.preferredContentTypes) available in the form / dialog.
  // suitable for ManyToMany relations. Property is sent as array of IRIs to the API.
  // not suitable, if data of the embedded entities should be edited as well (e.g. OneToMany relations)
  const embeddedCollections = ref([])

  const clearEntityData = () => {
    loading.value = true
    Object.keys(entityData).forEach((key) => delete entityData[key])
  }

  const setEntityData = (data) => {
    const loadingPromises = []

    entityProperties.value.forEach((key) => {
      entityData[key] = data[key]
    })
    embeddedEntities.value.forEach((key) => {
      if (data[key]) {
        const promise =
          typeof data[key] === 'function' ? data[key]()._meta.load : data[key]._meta.load
        loadingPromises.push(promise)
        promise.then((obj) => (entityData[key] = obj._meta.self))
      }
    })
    embeddedCollections.value.forEach((key) => {
      if (data[key]) {
        loadingPromises.push(data[key]().$loadItems())
        data[key]()
          .$loadItems()
          .then((obj) => {
            entityData[key] = obj.items.map((entity) => entity._meta.self)
          })
      }
    })

    // wait for all loading promises to finish before showing any content
    Promise.all(loadingPromises).then(() => {
      loading.value = false
    })
  }

  return {
    entityData,
    loading,
    entityProperties,
    embeddedEntities,
    embeddedCollections,
    clearEntityData,
    setEntityData,
  }
}
