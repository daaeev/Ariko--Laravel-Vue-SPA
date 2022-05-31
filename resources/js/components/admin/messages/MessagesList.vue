<template>
  <div
    :class="[
      'message-block',
      'd-flex',
      'justify-between',
      'mb-5',
      'message-' + item.id,
    ]"
    v-for="item in messages"
    :key="item.id"
  >
    <div class="col-10 content">
      <span class="name user-info">{{ item.name }}</span>
      <span class="email user-info">({{ item.email }})</span>

      <p class="text">
        {{ item.message }}
      </p>
    </div>
    <div class="col-2 controls">
      <button class="delete-button mb-2" @click="this.$emit('delete', item.id)">Delete</button>
    </div>
  </div>
</template>

<script>
export default {
  props: {
    messages: {
      required: true,
    },
  },

  emits: ['delete'],
};
</script>

<style scoped>
.message-block {
  width: 100%;
  border: 1px solid black;
  padding: 15px;
  border-radius: 5px;
}
.user-info:nth-child(n) {
  padding-right: 5px;
}
.message-block .name {
  color: black;
}
.message-block .name,
.message-block .email {
  font-size: small;
}
.message-block .content .text {
  margin: 0px;
  padding: 0px;
  color: black;
  overflow-wrap: break-word;
}
.controls .delete-button {
  padding: 5px 20px;
  cursor: pointer;
  border: none;
  background: rgb(255, 52, 52);
  color: #fff;
  border-radius: 5px;
}
</style>