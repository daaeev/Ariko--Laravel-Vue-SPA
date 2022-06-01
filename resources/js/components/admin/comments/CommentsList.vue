<template>
  <div
    :class="[
      'comment-block',
      'd-flex',
      'justify-between',
      'mb-5',
      'comment-' + item.id,
    ]"
    v-for="item in comments"
    :key="item.id"
  >
    <div class="col-10 content">
      <span class="name user-info">{{ item.name }}</span>
      <span class="email user-info">({{ item.email }})</span>

      <p class="text">
        {{ item.comment }}
      </p>
    </div>
    <div class="col-2 controls">
      <button class="button delete-button mb-2" @click="this.$emit('delete', item.id)">Delete</button>

      <button class="button checked-button mb-2" @click="this.$emit('checked', item.id)" v-if="!(item.checked)">Checked</button>
        <span class="text-success" v-else>Checked</span>
    </div>
  </div>
</template>

<script>
export default {
  props: {
    comments: {
      required: true,
    },
  },

  emits: ['delete', 'checked'],
};
</script>

<style scoped>
.comment-block {
  width: 100%;
  border: 1px solid black;
  padding: 15px;
  border-radius: 5px;
}
.user-info:nth-child(n) {
  padding-right: 5px;
}
.comment-block .name {
  color: black;
}
.comment-block .name,
.comment-block .email {
  font-size: small;
}
.comment-block .content .text {
  margin: 0px;
  padding: 0px;
  color: black;
  overflow-wrap: break-word;
}
.controls .button {
    padding: 5px 20px;
    cursor: pointer;
    border: none;
    border-radius: 5px;
}
.controls .delete-button {
  background: rgb(255, 52, 52);
  color: #fff;
}
.controls .checked-button {
    background: #4EC503FF;
    color: #fff;
}
</style>
