<template>
  <nav class="pagination-outer">
    <ul class="pagination justify-content-center">
      <li :class="['page-item', ((pagPage == index) ? 'disabled' : null)]" v-for="index in totalPagesCount" :key="index">
        <button class="page-link" @click="this.$emit('changePage', index)">{{index}}</button>
      </li>
    </ul>
  </nav>
</template>

<script>
export default {
    props: {
        totalPagesCount: {
            required: true,
        },

        pagPage: {
            required: true
        },
    },

    emits: ['changePage'],
};
</script>

<style scoped>
</style>