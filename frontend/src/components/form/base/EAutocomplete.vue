<template>
  <ValidationProvider
    v-slot="{ errors: veeErrors }"
    tag="div"
    :name="validationLabel"
    :vid="veeId"
    :rules="veeRules"
    :skip-if-empty="skipIfEmpty"
    :required="required"
    :immediate="immediateValidation"
    class="e-form-container"
  >
    <v-autocomplete
      v-bind="$attrs"
      :search-input.sync="search"
      :filled="filled"
      :hide-details="hideDetails"
      :error-messages="veeErrors.concat(errorMessages)"
      :label="labelOrEntityFieldLabel"
      :class="[inputClass]"
      :readonly="readonly"
      :append-icon="readonly ? null : '$dropdown'"
      :filter="tokensFilter"
      v-on="$listeners"
    >
      <template #item="{ item, on, attrs }">
        <v-list-item v-bind="attrs" v-on="on">
          <v-list-item-content>
            <v-list-item-title>
              <span v-html="renderHighlighted(item.text)" />
              <!--
                <span v-for="(part, idx) in renderHighlighted(item.text)" :key="idx">
                  <span v-if="part.h" style="background: yellowgreen">{{ part.text }}</span>
                  <span v-else>{{ part.text }}</span>
                </span>
                -->
            </v-list-item-title>
          </v-list-item-content>
        </v-list-item>
      </template>

      <!-- passing through all slots -->
      <slot v-for="(_, name) in $slots" :slot="name" :name="name" />
      <template v-for="(_, name) in $scopedSlots" :slot="name" slot-scope="slotData">
        <slot :name="name" v-bind="slotData" />
      </template>
    </v-autocomplete>
  </ValidationProvider>
</template>

<script>
import { ValidationProvider } from 'vee-validate'
import { formComponentPropsMixin } from '@/mixins/formComponentPropsMixin.js'
import { formComponentMixin } from '@/mixins/formComponentMixin.js'
import uFuzzy from '@leeoniya/ufuzzy'

export default {
  name: 'EAutocomplete',
  components: { ValidationProvider },
  mixins: [formComponentPropsMixin, formComponentMixin],
  props: {
    immediateValidation: { type: Boolean, default: false },
    skipIfEmpty: { type: Boolean, default: true },
    readonly: { type: Boolean, default: false },
  },
  data() {
    return {
      fuzzy: new uFuzzy({ intraMode: 1  }),
      search: null,
    }
  },
  methods: {
    tokensFilter(item, queryText, itemText) {
      const idxs = this.fuzzy.filter([itemText], queryText)
      return idxs && idxs.length > 0
    },

    renderHighlighted(text) {
      if (!this.search) return text

      const info = this.fuzzy.info([0], [text], this.search)
      return uFuzzy.highlight(
        text, info.ranges[0], (s, m) => {
          return m
            ? '<span style="background: yellowgreen">' + s + '</span>'
            : '<span>' + s + '</span>'
        }
      )
      
    },
  }
}
</script>

<style scoped>
[required]:deep(label::after) {
  content: '\a0*';
  font-size: 12px;
  color: #d32f2f;
}
[required]:deep(.v-input--is-label-active label::after) {
  color: gray;
}
</style>
