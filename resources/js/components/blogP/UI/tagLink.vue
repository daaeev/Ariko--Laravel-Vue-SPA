<template>
  <span
    class="tag-name_link"
    @click="fetchPostsByTagWithSimplePagination({ tag })"
    >{{ tag }}</span
  >
</template>

<script>
import { mapActions } from "vuex";

export default {
  methods: {
    ...mapActions("posts", ["fetchPostsByTagWithSimplePagination"]),
  },

  props: {
    tag: {
      required: true,
    },
  },
};
</script>

<style scoped>
.tag-name_link {
  cursor: pointer;
}
</style>