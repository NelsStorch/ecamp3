<template>
  <DialogBottomSheet
    v-if="$vuetify.display.smAndDown"
    :saving-override.sync="isSaving"
    :value="modelValue"
    v-bind="$attrs"
  >
    <slot v-for="(_, name) in $slots" :slot="name" :name="name" />
    <template v-for="(_, name) in $slots" #[name]="slotData">
      <slot :name="name" v-bind="slotData" />
    </template>
  </DialogBottomSheet>
  <DialogForm
    v-else
    :saving-override.sync="isSaving"
    content-class="ec-dialog-form"
    eager
    :value="value"
    v-bind="$attrs"
  >
    <!-- passing through all slots -->
    <template v-for="(_, slot) of $slots" #[slot]="slotData">
      <slot :name="slot" v-bind="slotData || {}"></slot>
    </template>
  </DialogForm>
</template>

<script>
import DialogForm from '@/components/dialog/DialogForm.vue'
import DialogBottomSheet from '@/components/dialog/DialogBottomSheet.vue'
import DialogUiBase from '@/components/dialog/DialogUiBase.vue'

export default {
  name: 'DetailPane',
  components: { DialogBottomSheet, DialogForm },
  extends: DialogUiBase,
}
</script>
