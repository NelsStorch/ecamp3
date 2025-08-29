<template>
  <content-group
    :title="$tc('components.campAdmin.campSharingSettings.title')"
    icon="mdi-share-variant"
  >
    <v-skeleton-loader v-if="camp._meta.loading" type="article" />
    <v-list class="py-0" color="transparent">
      <v-list-item class="px-0">
        <v-list-item-content>
          <v-list-item-title>
            {{ $tc(`components.campAdmin.campSharingSettings.${sharingStatus}.title`) }}
          </v-list-item-title>
          <i18n
            :path="`components.campAdmin.campSharingSettings.${sharingStatus}.description`"
            tag="div"
            class="body-2 grey--text text--darken-3"
          >
            <template #team>
              <span v-if="isOutsider">{{
                $tc('components.campAdmin.campSharingSettings.team')
              }}</span>
              <router-link v-else :to="teamRoute">{{
                $tc('components.campAdmin.campSharingSettings.team')
              }}</router-link>
            </template>
          </i18n>
        </v-list-item-content>
        <v-list-item-action v-if="camp.isShared" class="align-self-start">
          <v-tooltip top>
            <template #activator="{ on, attrs }">
              <v-btn
                icon
                v-bind="attrs"
                small
                v-on="on"
                @click="copyCampUrlToClipboard()"
              >
                <v-icon>mdi-clipboard-check-multiple-outline</v-icon>
              </v-btn>
            </template>
            {{ $tc(`components.campAdmin.campSharingSettings.copyCampLink`) }}
          </v-tooltip>
        </v-list-item-action>
        <v-list-item-action v-if="isManager" class="align-self-start">
          <api-form :entity="camp">
            <api-switch
              path="isShared"
              icon="mdi-share-variant"
              :disabled="disabled"
              class="mt-1"
            />
          </api-form>
        </v-list-item-action>
      </v-list-item>
    </v-list>
  </content-group>
</template>

<script>
import ContentGroup from '@/components/layout/ContentGroup.vue'
import ApiForm from '../form/api/ApiForm.vue'
import router, { adminRoute, campRoute } from '@/router.js'
import { campRoleMixin } from '@/mixins/campRoleMixin.js'

export default {
  name: 'CampSharingSettings',
  components: { ApiForm, ContentGroup },
  mixins: [campRoleMixin],
  props: {
    camp: { type: Object, required: true },
    disabled: { type: Boolean, default: false },
  },
  data() {
    return {}
  },
  computed: {
    sharingStatus() {
      return this.camp.isShared ? 'shared' : 'notShared'
    },
    teamRoute() {
      return adminRoute(this.camp, 'collaborators')
    },
    campUrl() {
      return window.location.origin + router.resolve(campRoute(this.camp)).href
    },
  },
  methods: {
    async copyCampUrlToClipboard() {
      await navigator.clipboard.writeText(this.campUrl)

      this.$toast.info(
        this.$tc('global.toast.copied', null, {
          source: this.$tc('components.campAdmin.campSharingSettings.publicCampUrl'),
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
