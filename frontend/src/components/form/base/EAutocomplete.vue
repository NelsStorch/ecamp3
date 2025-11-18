<template>
  <Field
    v-slot="{ errors: veeErrors }"
    :label="validationLabel"
    :name="veeId ?? path"
    :rules="veeRules"
  >
    <v-autocomplete
      :class="[inputClass]"
      :custom-filter="tokensFilter"
      :error-messages="(veeErrors ?? []).concat(errorMessages)"
      :filled="filled"
      :hide-details="hideDetails"
      :label="labelOrEntityFieldLabel"
      :menu-icon="readonly ? null : '$dropdown'"
      item-title="text"
      item-value="value"
      :readonly="readonly"
      :search.sync="search"
      v-bind="$attrs"
    >
      <template #item="{ item, props }">
        <v-list-item v-bind="props">
          <v-list-item-title>
            <span v-for="(part, idx) in renderHighlighted(item)" :key="idx">
              <mark v-if="part.h">{{ part.text }}</mark>
              <span v-else>{{ part.text }}</span>
            </span>
          </v-list-item-title>
        </v-list-item>
      </template>

      <!-- passing through all slots -->
      <template v-for="(_, slot) of $slots" #[slot]="slotData">
        <slot :name="slot" v-bind="slotData || {}"></slot>
      </template>
    </v-autocomplete>
  </Field>
</template>

<script>
import { Field } from 'vee-validate'
import { formComponentPropsMixin } from '@/mixins/formComponentPropsMixin.js'
import { formComponentMixin } from '@/mixins/formComponentMixin.js'
import uFuzzy from '@leeoniya/ufuzzy'

export default {
  name: 'EAutocomplete',
  components: { Field },
  mixins: [formComponentPropsMixin, formComponentMixin],
  props: {
    immediateValidation: { type: Boolean, default: false },
    skipIfEmpty: { type: Boolean, default: true },
    readonly: { type: Boolean, default: false },
  },
  data() {
    return {
      fuzzy: new uFuzzy({ intraMode: 1 }),
      search: null,
      searchInfos: new Map(),
    }
  },
  methods: {
    tokensFilter(value, queryText) {
      const [idxs, info] = this.fuzzy.search([value], queryText, true, 1e3)
      this.searchInfos.set(value, info)
      return idxs && idxs.length > 0
    },

    renderHighlighted(item) {
      if (this.searchInfos.size > 0) {
        if (this.searchInfos.has(item.title)) {
          const info = this.searchInfos.get(item.title)
          if (info) {
            return uFuzzy.highlight(
              item.title,
              info.ranges[0],
              (p, m) => ({ h: m, text: p }),
              [],
              (a, p) => {
                a.push(p)
              }
            )
          }
        }
      }
      return [{ h: false, text: item.title }]
    },
  },
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
