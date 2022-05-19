<template>
  <!-- blog item -->
  <article class="blog-item" v-for="item in posts" :key="item.id">
    <!-- header -->
    <header>
      <!-- title -->
      <h2 class="title">
        <router-link :to="{name: 'blog', params: {id: item.id}}">{{ item.title }}</router-link>
      </h2>
      <!-- meta -->
      <ul class="meta list-inline">
        <li class="list-inline-item">{{ dateFormat(item.created_at) }}</li>
        <li class="list-inline-item" v-for="tag in item.tags" :key="tag.id">
          <tag-link :tag="tag.name"></tag-link>
        </li>
      </ul>
    </header>
    <!-- thumb -->
    <div class="text-center">
      <img
        :src="
          '/storage/posts_previews/' + (item.prevew_image ?? item.main_image)
        "
        alt="blog-thumb"
      />
    </div>
    <!-- footer -->
    <footer>
      <!-- except -->
      <p class="except">
        {{ item.content.substr(0, 170) + "..." }}
      </p>
      <!-- button -->
      <router-link :to="{name: 'blog', params: {id: item.id}}" class="btn btn-default"
        >Read more</router-link
      >
    </footer>
  </article>
</template>

<script>
import DateFormat from "../../mixins/DateFormat";
import TagLink from './UI/tagLink.vue'

export default {
  components: {TagLink},
  mixins: [DateFormat],

  props: {
    posts: {
      required: true,
    },
  },
};
</script>

<style scoped>
</style>