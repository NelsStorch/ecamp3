<template>
  <content-group
    :title="$t('components.campAdmin.campSharingSettings.title')"
    icon="mdi-earth"
  >
    <v-skeleton-loader v-if="camp._meta.loading" type="article" />
    <v-list class="py-0" color="transparent" two-line>
      <v-list-item class="px-0">
        <v-list-item-content>
          <v-list-item-title>
            {{ $t(`components.campAdmin.campSharingSettings.${sharingStatus}.title`) }}
          </v-list-item-title>
          <v-list-item-subtitle v-if="camp.isShared" class="pb-1 whitespace-normal">
            {{
              $t('components.campAdmin.campSharingSettings.sharedSince', 1, {
                sharedSince,
                sharedBy,
              })
            }}
          </v-list-item-subtitle>
        </v-list-item-content>
        <v-list-item-action>
          <DialogShare :title="$t('components.campAdmin.campSharingSettings.title')">
            <template #activator="{ on, attrs }">
              <ButtonEdit
                color="primary--text"
                text
                class="my-n1 v-btn--has-bg"
                icon="mdi-earth"
                v-bind="attrs"
                v-on="on"
                >{{
                  camp.isShared ? $t('global.button.edit') : $t('global.button.share')
                }}</ButtonEdit
              >
            </template>
            <p>
              <strong>{{
                $t(`components.campAdmin.campSharingSettings.${sharingStatus}.title`)
              }}</strong
              >{{
                $t(
                  `components.campAdmin.campSharingSettings.${sharingStatus}.description`
                )
              }}
            </p>
            <p>
              <v-btn
                v-if="isManager"
                :color="camp.isShared ? '' : 'error'"
                elevation="0"
                text
                class="v-btn--has-bg"
                :loading="loading"
                @click="toggleShare"
                ><v-icon start>mdi-alert</v-icon
                >{{
                  camp.isShared
                    ? $t('components.campAdmin.campSharingSettings.deactivate')
                    : $t('components.campAdmin.campSharingSettings.activate')
                }}</v-btn
              >
            </p>
            <p v-if="!camp.isShared">
              {{ $t('components.campAdmin.campSharingSettings.implications') }}
            </p>
            <template v-if="camp.isShared" #more-actions>
              <div class="d-flex gap-2 align-center w-100">
                <small class="flex-shrink-1 flex-grow-1 w-0"
                  ><a :href="campUrl">{{ campUrl }}</a></small
                >

                <v-btn text class="v-btn--has-bg" @click="copyCampUrlToClipboard()">
                  <v-icon start>mdi-clipboard-check-multiple-outline</v-icon>
                  {{ $t('global.button.copy') }}
                </v-btn>
              </div>
            </template>
          </DialogShare>
        </v-list-item-action>
      </v-list-item>
    </v-list>
  </content-group>
</template>

<script>
import ContentGroup from '@/components/layout/ContentGroup.vue'
import router, { campRoute } from '@/router.js'
import { campRoleMixin } from '@/mixins/campRoleMixin.js'
import userDisplayName from '@/common/helpers/userDisplayName.js'
import DialogShare from '@/components/campAdmin/DialogShare.vue'
import ButtonEdit from '@/components/buttons/ButtonEdit.vue'
import { useToast } from 'vue-toastification'

export default {
  name: 'CampSharingSettings',
  components: { ButtonEdit, DialogShare, ContentGroup },
  mixins: [campRoleMixin],
  props: {
    camp: { type: Object, required: true },
    disabled: { type: Boolean, default: false },
  },
  setup() {
    const toast = useToast()
    return { toast }
  },
  data() {
    return {
      loading: false,
    }
  },
  computed: {
    sharingStatus() {
      return this.camp.isShared ? 'shared' : 'notShared'
    },
    campUrl() {
      return window.location.origin + router.resolve(campRoute(this.camp)).href
    },
    sharedSince() {
      return this.$date(this.camp.sharedSince).format(
        this.$t('global.datetime.dateTimeLong')
      )
    },
    sharedBy() {
      return userDisplayName(this.camp.sharedBy())
    },
  },
  methods: {
    toggleShare() {
      this.loading = true
      this.api
        .patch(this.camp._meta.self, {
          isShared: !this.camp.isShared,
        })
        .then(() => {
          this.loading = false
        })
    },
    async copyCampUrlToClipboard() {
      await navigator.clipboard.writeText(this.campUrl)

      this.toast.info(
        this.$t('global.toast.copied', null, {
          source: this.$t('components.campAdmin.campSharingSettings.publicCampUrl'),
        }),
        {
          timeout: 2000,
        }
      )
    },
  },
}
</script>

<style scoped></style>
