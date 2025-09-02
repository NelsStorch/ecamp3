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
              <span v-for="(part, idx) in renderHighlighted(item.text)" :key="idx">
                <span v-if="part.h" style="background: yellowgreen">{{ part.text }}</span>
                <span v-else>{{ part.text }}</span>
              </span>
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
      search: null,
    }
  },
  methods: {
    tokensFilter(item, queryText, itemText) {
      const { ok } = this.checkAllTokensNonOverlapping(queryText, itemText, {
        normalizeInput: true,
      })
      return ok
    },
    renderHighlighted(text) {
      if (!this.search) return [{ text, h: false }]

      const { ok, matches } = this.checkAllTokensNonOverlapping(this.search, text, {
        normalizeInput: true,
      })
      if (!ok) return [{ text, h: false }]

      return this.highlightMatches(text, matches)
    },

    escapeRegex(str) {
      return str.replace(/[.*+?^${}()|[\]\\]/g, '\\$&')
    },
    normalize(str) {
      return str
        .toLowerCase()
        .normalize('NFD')
        .replace(/[\u0300-\u036f]/g, '')
    },
    findAllOccurrences(haystack, needle) {
      if (!needle) return []
      const rx = new RegExp(this.escapeRegex(needle), 'g')
      const res = []
      let m
      while ((m = rx.exec(haystack)) !== null) {
        res.push({ start: m.index, end: m.index + needle.length })
      }
      return res
    },

    checkAllTokensNonOverlapping(query, text, { normalizeInput = true } = {}) {
      if (!query || !text) return { ok: false }

      const baseText = normalizeInput ? this.normalize(text) : text
      const rawTokens = query.split(/\s+/).filter(Boolean)
      if (rawTokens.length === 0) return { ok: true, matches: [] }

      const tokens = normalizeInput ? rawTokens.map(this.normalize) : rawTokens

      const tokenOccs = tokens.map((t, i) => ({
        token: t,
        idx: i,
        occs: this.findAllOccurrences(baseText, t),
      }))

      if (tokenOccs.some((t) => t.occs.length === 0)) return { ok: false }

      tokenOccs.sort((a, b) => a.occs.length - b.occs.length)

      const chosen = []

      function overlaps(a, b) {
        return a.start < b.end && b.start < a.end
      }
      function backtrack(k) {
        if (k === tokenOccs.length) return true
        const { token, idx, occs } = tokenOccs[k]

        for (const occ of occs) {
          if (chosen.every((sel) => !overlaps(sel, occ))) {
            chosen.push({ ...occ, token, idx })
            if (backtrack(k + 1)) return true
            chosen.pop()
          }
        }
        return false
      }

      const ok = backtrack(0)
      if (!ok) return { ok: false }
      // chosen enthält die gematchten Intervalle in Sortierreihenfolge der Token-Listen
      // Für Ausgabe/Highlight sortieren wir sie nach Position:
      chosen.sort((a, b) => a.start - b.start)
      return { ok: true, matches: chosen }
    },
    highlightMatches(text, matches) {
      if (!matches || matches.length === 0) return [{ text, h: false }]
      let cursor = 0
      const items = []
      for (const m of matches) {
        items.push({ text: text.slice(cursor, m.start), h: false })
        items.push({ text: text.slice(m.start, m.end), h: true })
        cursor = m.end
      }
      items.push({ text: text.slice(cursor), h: false })
      return items
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
