<template>
  <e-form name="campCollaboration">
    <e-text-field
      v-if="status"
      class="ec-status-field"
      :value="translatedStatus"
      readonly
      path="status"
    >
      <template #append>
        <slot name="statusChange" />
      </template>
    </e-text-field>
    <e-select
      v-if="readonlyRole"
      v-model="localCollaboration.role"
      path="role"
      readonly
      aria-readonly="true"
      aria-describedby="readonly"
      :items="Object.keys(items)"
      :hint="$t('components.collaborator.collaboratorForm.roleHint')"
      persistent-hint
      item-value="key"
      item-text="role"
      vee-rules="required"
    >
      <template #selection="{ item: { value: itemKey } }">
        <span
          >{{ items[itemKey].role }} &middot;
          <span class="text-grey"
            ><template v-for="icon in items[itemKey].icons" :key="icon"
              ><v-icon size="x-small">{{ icon }}</v-icon
              >&thinsp;</template
            ></span
          >
        </span>
      </template>
    </e-select>
    <e-select
      v-else
      v-model="localCollaboration.role"
      path="role"
      :items="Object.keys(items)"
      persistent-hint
      item-value="key"
      item-text="role"
      vee-rules="required"
    >
      <template #item="{ item: { value: itemKey }, props: bindProps }">
        <v-list-item :title="undefined" lines="two" v-bind="bindProps">
          <v-list-item-title>{{ items[itemKey].role }}</v-list-item-title>
          <span class="text-caption">{{ items[itemKey].abilities }}</span>

          <v-list-item-action class="text-right">
            <span
              ><template v-for="icon in items[itemKey].icons" :key="icon"
                ><v-icon size="small">{{ icon }}</v-icon
                >&thinsp;</template
              ></span
            >
          </v-list-item-action>
        </v-list-item>
      </template>
      <template #selection="{ item: { value: itemKey } }">
        <span
          >{{ items[itemKey].role }} &middot;
          <span class="text-grey"
            ><template v-for="icon in items[itemKey].icons" :key="icon"
              ><v-icon size="x-small">{{ icon }}</v-icon
              >&thinsp;</template
            ></span
          >
        </span>
      </template>
    </e-select>

    <fieldset
      v-if="!!initialCollaboration"
      class="e-form-container e-avatar-field v-card__text rounded-t"
    >
      <legend>
        {{ $t('components.collaborator.collaboratorForm.overrideAvatar') }}
      </legend>

      <div class="d-flex gap-4 align-center">
        <UserAvatar
          :user="initialCollaboration?.user?.()"
          :camp-collaboration="avatarCollaboration"
        />
        <div class="flex-grow-1">
          <e-text-field
            v-model="localCollaboration.abbreviation"
            path="abbreviation"
            :filled="false"
            vee-rules="oneEmojiOrTwoCharacters"
          />

          <e-color-picker
            v-model="localCollaboration.color"
            :filled="false"
            path="color"
          />
        </div>
      </div>
    </fieldset>
  </e-form>
</template>

<script>
import UserAvatar from '@/components/user/UserAvatar.vue'

export default {
  name: 'SettingsCollaboratorForm',
  components: { UserAvatar },
  props: {
    collaboration: { type: Object, required: true },
    status: { type: [String, Boolean], required: false, default: false },
    readonlyRole: { type: [String, Boolean], required: false, default: false },
    initialCollaboration: { type: Object, required: false, default: null },
  },
  computed: {
    items() {
      return {
        manager: {
          role: this.$t('entity.camp.collaborators.manager'),
          abilities: this.$t('global.collaborationAbilities.manager'),
          icons: ['mdi-eye-outline', 'mdi-pencil-outline', 'mdi-cog-outline'],
        },
        member: {
          role: this.$t('entity.camp.collaborators.member'),
          abilities: this.$t('global.collaborationAbilities.member'),
          icons: ['mdi-eye-outline', 'mdi-pencil-outline'],
        },
        guest: {
          key: 'guest',
          role: this.$t('entity.camp.collaborators.guest'),
          abilities: this.$t('global.collaborationAbilities.guest'),
          icons: ['mdi-eye-outline'],
        },
      }
    },
    localCollaboration() {
      return this.collaboration
    },
    avatarCollaboration() {
      return {
        ...this.initialCollaboration,
        ...this.localCollaboration,
      }
    },
    translatedStatus() {
      return this.$t(`entity.campCollaboration.status.${this.status}`)
    },
  },
}
</script>

<style scoped>
.ec-status-field::v-deep(.v-input__append-inner) {
  margin-top: 0;
  align-self: center;
  margin-right: -4px;
}
.e-avatar-field {
  display: grid;
  border: none;
  background: #eee;
  padding: 12px;
  border-bottom: 1px solid rgba(0, 0, 0, 0.42) !important;
}
.e-avatar-field legend {
  float: left;
}
</style>
