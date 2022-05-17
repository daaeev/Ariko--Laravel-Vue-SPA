<template>
  <ul class="tags list-inline float-right">
    <li class="list-inline-item" v-for="tag in tags" :key="tag.id"><router-link :to="'/blog?tag=' + tag.name">#{{tag.name}}</router-link></li>
  </ul>
</template>

<script>
export default {
    props: {
        tags: {
            required: true,
        }
    }
};
</script>

<style>
</style>