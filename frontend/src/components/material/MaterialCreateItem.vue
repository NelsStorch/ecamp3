<template>
  <Form v-if="materialListsSorted.length > 0" v-slot="{ handleSubmit }" as="tr">
    <td class="pt-1">
      <e-number-field
        ref="quantity"
        v-model="materialItem.quantity"
        dense
        inputmode="decimal"
        path="quantity"
        vee-rules="greaterThan:0"
      />
    </td>
    <td class="pt-1">
      <e-text-field v-model="materialItem.unit" dense maxlength="32" path="unit" />
    </td>
    <td class="pt-1">
      <e-text-field
        v-model="materialItem.article"
        dense
        maxlength="64"
        path="article"
        vee-rules="required"
      />
    </td>
    <td :colspan="columns - 4" class="pt-1">
      <e-select
        v-model="materialItem.materialList"
        :items="materialListsSorted"
        :label="$t('entity.materialList.name')"
        dense
        path="materialList"
        vee-rules="required"
      />
    </td>
    <td class="pt-1">
      <ButtonAdd height="52" hide-label @click="handleSubmit(createMaterialItem)" />
    </td>
  </Form>
  <tr v-else>
    <td :colspan="columns">
      <div>
        <p>
          {{ $t('components.material.materialCreateItem.noMaterialListAvailable') }}
        </p>
        <v-btn :to="campRoute(camp, 'admin')">
          <v-icon :left="$vuetify.display.mdAndUp">mdi-cogs</v-icon>
          {{ $t('components.material.materialCreateItem.campSettingsButton') }}
        </v-btn>
      </div>
    </td>
  </tr>
</template>

<script>
import { campRoute } from '@/router.js'
import { Form } from 'vee-validate'
import ButtonAdd from '@/components/buttons/ButtonAdd.vue'
import materialListsSorted from '@/common/helpers/materialListsSorted.js'

export default {
  name: 'MaterialCreateItem',
  components: {
    Form,
    ButtonAdd,
  },
  provide() {
    return {
      entityName: 'materialItem',
    }
  },
  props: {
    camp: { type: Object, required: true },

    materialList: { type: Object, required: false, default: null },

    /* number of colums currently visible in table */
    columns: { type: Number, required: true },
  },
  data() {
    return {
      materialItem: {},
    }
  },
  computed: {
    materialListsSorted() {
      return materialListsSorted(this.camp.materialLists().items).map((list) => ({
        value: list._meta.self,
        text: list.name,
      }))
    },
  },
  created() {
    this.initEntity()
  },
  methods: {
    initEntity() {
      this.materialItem = {
        materialList: this.materialList?._meta.self ?? undefined,
      }
    },
    createMaterialItem() {
      const key = Date.now()
      const data = this.materialItem

      this.initEntity()
      this.$refs.quantity.focus()

      // fire event to allow for eager adding before post has finished
      this.$emit('item-adding', key, data)
    },
    campRoute,
  },
}
</script>

<style scoped>
/* eslint-disable-next-line vue-scoped-css/no-unused-selector */
.newItemRow {
  line-height: 80px;
  vertical-align: top;
}

/* eslint-disable-next-line vue-scoped-css/no-unused-selector */
.v-btn {
  vertical-align: text-bottom;
}
</style>
