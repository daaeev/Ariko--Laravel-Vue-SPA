<template>
  <div class="dialog" v-if="show === true" @click="this.$emit('update:show', false);">
    <div class="dialog_content" @click.stop>
      <slot></slot>
    </div>
  </div>
</template>

<script>
export default {
  props: {
    show: {
      type: Boolean,
      default: false
    }
  },
  emits: ['update:show'],
}
</script>

<style scoped>
.dialog {
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  position: fixed;
  background: rgba(0, 0, 0, 0.5);
  display: flex;
}
.dialog_content {
  margin: auto;
  background: white;
  border-radius: 12px;
  padding: 15px;
}
</style>