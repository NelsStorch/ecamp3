<template>
  <v-stepper-content :step="1" class="pa-0">
    <Form ref="form" v-slot="{ meta }" @submit="() => $emit('next-step')">
      <v-card-text>
        <e-text-field
          v-model="localCamp.title"
          path="title"
          :placeholder="$t('components.campCreate.campCreateStep1.titlePlaceholder')"
          vee-rules="required|max:32"
          data-testid="create-camp-title-input"
          required
        />
        <e-text-field
          v-model="localCamp.organizer"
          path="organizer"
          data-testid="create-camp-organizer"
        />
        <e-text-field
          v-model="localCamp.motto"
          path="motto"
          data-testid="create-camp-motto"
        />
        <CreateCampPeriods
          :add-period="addPeriod"
          :periods="localCamp.periods"
          :delete-period="deletePeriod"
          :period-deletable="periodDeletable"
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
            <v-btn elevation="0" color="secondary" v-bind="props">
              {{ $t('global.button.continue') }}
            </v-btn>
          </template>
          {{ $t('components.campCreate.campCreateStep1.submitTooltip') }}
        </v-tooltip>
      </ContentActions>
    </Form>
  </v-stepper-content>
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
