<template>
  <Form ref="form" v-slot="{ meta }" @submit="() => $emit('next-step')">
    <e-form name="camp">
      <v-card-text>
        <e-text-field
          v-model="localCamp.title"
          :placeholder="$t('components.campCreate.campCreateStep1.titlePlaceholder')"
          data-testid="create-camp-title-input"
          path="title"
          required
          vee-rules="required|max:32"
        />
        <e-text-field
          v-model="localCamp.organizer"
          data-testid="create-camp-organizer"
          path="organizer"
        />
        <e-text-field
          v-model="localCamp.motto"
          data-testid="create-camp-motto"
          path="motto"
        />
        <CreateCampPeriods
          :add-period="addPeriod"
          :delete-period="deletePeriod"
          :period-deletable="periodDeletable"
          :periods="localCamp.periods"
        />
      </v-card-text>

      <v-divider />
      <ContentActions>
        <v-spacer />
        <ButtonCancel :disabled="isSaving" @click="$router.go(-1)" />
        <ButtonContinue
          v-if="meta.dirty && meta.valid"
          data-testid="create-camp-next-step"
        />
        <v-tooltip v-else location="top">
          <template #activator="{ props }">
            <v-btn color="secondary" variant="flat" v-bind="props">
              {{ $t('global.button.continue') }}
            </v-btn>
          </template>
          {{ $t('components.campCreate.campCreateStep1.submitTooltip') }}
        </v-tooltip>
      </ContentActions>
    </e-form>
  </Form>
</template>
<script>
import ButtonCancel from '@/components/buttons/ButtonCancel.vue'
import ButtonContinue from '@/components/buttons/ButtonContinue.vue'
import ContentActions from '@/components/layout/ContentActions.vue'
import CreateCampPeriods from '@/components/campAdmin/CreateCampPeriods.vue'
import ETextField from '@/components/form/base/ETextField.vue'
import { Form } from 'vee-validate'

export default {
  name: 'CampCreateStep1',
  components: {
    ButtonCancel,
    ButtonContinue,
    ContentActions,
    CreateCampPeriods,
    Form,
    ETextField,
  },
  props: {
    camp: { type: Object, required: true },
    isSaving: { type: Boolean, required: true },
  },
  data: function () {
    return {
      localCamp: this.camp,
    }
  },
  computed: {
    periodDeletable() {
      return this.camp.periods.length > 1
    },
  },
  methods: {
    addPeriod: function () {
      this.$emit('add-period')
    },
    deletePeriod: function (idx) {
      this.$emit('delete-period', idx)
    },
  },
}
</script>
