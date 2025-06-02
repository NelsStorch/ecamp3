<template>
  <dialog-form
    v-model="showDialog"
    :loading="entityDataLoading"
    :error="error"
    icon="mdi-calendar-plus"
    :title="$tc('entity.activity.new')"
    :submit-action="createActivity"
    :submit-label="$tc('global.button.create')"
    submit-icon="mdi-plus"
    submit-color="success"
    :cancel-action="cancelCreate"
    max-width="700px"
  >
    <template #activator="scope">
      <slot name="activator" v-bind="scope" />
    </template>

    <template #moreActions>
      <CopyActivityInfoDialog @closed="attemptLoadingEntityFromClipboard">
        <template #activator="{ on }">
          <v-btn v-show="showClipboardPrompt" v-on="on">
            <v-icon left>mdi-information-outline</v-icon>
            {{ $tc('components.program.dialogActivityCreate.copyPasteActivity') }}
          </v-btn>
        </template>
      </CopyActivityInfoDialog>
    </template>

    <div v-if="hasClipboardEntity">
      <div class="mb-8">
        <div v-if="!clipboardAccessDenied">
          {{ $tc('components.program.dialogActivityCreate.clipboard') }}
          <div style="float: right">
            <small>
              <a
                href="#"
                style="color: inherit; text-decoration: none"
                @click="clearClipboard"
              >
                {{ $tc('components.program.dialogActivityCreate.clearClipboard') }}
              </a>
            </small>
          </div>
        </div>
        <v-list-item
          class="ec-copy-source rounded-xl blue-grey lighten-5 blue-grey--text text--darken-4 mt-1"
        >
          <v-list-item-avatar>
            <v-icon color="blue-grey">mdi-clipboard-check-outline</v-icon>
          </v-list-item-avatar>
          <v-list-item-content>
            <v-list-item-title>
              <CategoryChip :category="clipboardEntity.category()" class="mx-1" dense />
              {{ clipboardEntity.title }}
            </v-list-item-title>
            <v-list-item-subtitle>
              {{ clipboardEntity.camp().title }}
            </v-list-item-subtitle>
          </v-list-item-content>
          <v-list-item-action>
            <e-checkbox
              :value="entityData.copyActivitySource !== null"
              :label="$tc('components.program.dialogActivityCreate.copyActivityContent')"
              @input="setCopyContentCheckbox"
            />
          </v-list-item-action>
        </v-list-item>
      </div>
    </div>
    <DialogActivityForm
      :activity="entityData"
      :period="scheduleEntry.period()"
      :autoselect-title="true"
    >
      <template v-if="clipboardAccessDenied" #textFieldTitleAppend>
        <PopoverPrompt
          v-model="showCopyActivityUrlPopover"
          icon="mdi-content-paste"
          :title="$tc('components.program.dialogActivityCreate.pasteActivity')"
        >
          <template #activator="scope">
            <v-btn
              :title="$tc('components.program.dialogActivityCreate.pasteActivity')"
              text
              class="v-btn--has-bg"
              height="56"
              v-on="scope.on"
            >
              <v-progress-circular v-if="clipboardEntityLoading" indeterminate />
              <v-icon v-else>mdi-content-paste</v-icon>
            </v-btn>
          </template>
          {{ $tc('components.program.dialogActivityCreate.copySourceInfo') }}
          <e-text-field
            v-model="clipboardEntityUrl"
            :label="$tc('components.program.dialogActivityCreate.copyActivity')"
            style="margin-bottom: 12px"
            autofocus
          />
        </PopoverPrompt>
      </template>
    </DialogActivityForm>
  </dialog-form>
</template>

<script>
import DialogForm from '@/components/dialog/DialogForm.vue'
import DialogBase from '@/components/dialog/DialogBase.vue'
import DialogActivityForm from '@/components/activity/dialog/DialogActivityForm.vue'
import CopyActivityInfoDialog from '@/components/activity/CopyActivityInfoDialog.vue'
import PopoverPrompt from '@/components/prompt/PopoverPrompt.vue'
import { uniqueId } from 'lodash-es'
import CategoryChip from '@/components/generic/CategoryChip.vue'
import { useClipboardEntity } from '../generic/useClipboardEntity.js'
import router from '@/router.js'
import { apiStore as api } from '@/plugins/store'
import { computed, nextTick, ref } from 'vue'
import { useEntityData } from '@/components/dialog/useEntityData.js'

export default {
  name: 'DialogActivityCreate',
  components: {
    CategoryChip,
    DialogForm,
    DialogActivityForm,
    CopyActivityInfoDialog,
    PopoverPrompt,
  },
  extends: DialogBase,
  props: {
    scheduleEntry: { type: Object, required: true },
  },
  setup({ scheduleEntry }) {
    const {
      entityData,
      loading: entityDataLoading,
      entityProperties,
      embeddedEntities,
      setEntityData,
    } = useEntityData()
    const showCopyActivityUrlPopover = ref(false)

    const period = computed(() => scheduleEntry.period())
    const camp = computed(() => period.value.camp())

    const clipboard = useClipboardEntity(api.get.bind(this), {
      fetchClipboardEntity: async (url) => {
        if (url?.startsWith(window.location.origin)) {
          url = url.substring(window.location.origin.length)
          const match = router.matcher.match(url)

          if (match.name === 'camp/activity') {
            const result = await api.get().activities({ id: match.params['activityId'] })

            // if Paste-Popover is shown, close it now
            if (showCopyActivityUrlPopover.value) {
              nextTick(() => {
                showCopyActivityUrlPopover.value = false
              })
            }

            return result
          }
        }
        return null
      },
      onEntityLoaded: function () {
        setCopyContentCheckbox(true)
      },
      onEntityLoadFailed: function () {
        setCopyContentCheckbox(false)
      },
    })

    const clipboardEntity = clipboard.clipboardEntity

    const setCopyContentCheckbox = (val) => {
      if (val) {
        entityData.copyActivitySource = clipboardEntity.value._meta.self
        entityData.title = clipboardEntity.value.title
        entityData.location = clipboardEntity.value.location

        const sourceCamp = clipboardEntity.value.camp()
        const sourceCategory = clipboardEntity.value.category()

        if (camp.value._meta.self === sourceCamp._meta.self) {
          // same camp; use came category
          entityData.category = sourceCategory._meta.self
        } else {
          // different camp; use category with same short-name
          const categories = camp.value
            .categories()
            .allItems.filter((c) => c.short === sourceCategory.short)

          if (categories.length === 1) {
            entityData.category = categories[0]._meta.self
          }
        }
      } else {
        entityData.copyActivitySource = null
      }
    }

    return {
      ...clipboard,
      setCopyContentCheckbox,
      showCopyActivityUrlPopover,
      entityData,
      entityDataLoading,
      entityProperties,
      embeddedEntities,
      setEntityData,
    }
  },
  data() {
    return {
      entityUri: '',
    }
  },
  computed: {
    copyContent: {
      get() {
        return this.entityData.copyActivitySource != null
      },
      set(val) {
        this.setCopyContentCheckbox(val)
      },
    },
  },
  watch: {
    showDialog: function (showDialog) {
      if (showDialog) {
        this.attemptLoadingEntityFromClipboard()
        this.setEntityData({
          title: this.entityData?.title,
          location: '',
          scheduleEntries: [
            {
              period: this.scheduleEntry.period,
              start: this.scheduleEntry.start,
              end: this.scheduleEntry.end,
              key: uniqueId(),
              deleted: false,
            },
          ],
        })
      } else {
        // clear the variable parts of the form on exit
        this.clipboardEntityUrl = null
        this.entityData.location = ''
        this.entityData.scheduleEntries = []
      }
      this.clipboardEntity = null
    },
  },
  mounted() {
    this.entityProperties.push('title', 'location', 'scheduleEntries')
    this.embeddedEntities.push('category')
    this.api.href(this.api.get(), 'activities').then((url) => (this.entityUri = url))
  },
  methods: {
    cancelCreate() {
      this.close()
    },
    createActivity() {
      const payloadData = {
        ...this.entityData,

        scheduleEntries:
          this.entityData.scheduleEntries
            ?.filter((entry) => !entry.deleted)
            .map((entry) => ({
              period: entry.period()._meta.self,
              start: entry.start,
              end: entry.end,
            })) || [],
      }

      return this.create(payloadData)
    },
    onSuccess(activity) {
      this.close()
      this.$emit('activity-created', activity)
    },
  },
}
</script>

<style scoped></style>
