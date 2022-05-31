<template>
  <!-- blog item -->
  <article class="blog-item" v-for="item in posts" :key="item.id">
    <!-- header -->
    <header>
      <!-- title -->
      <h2 class="title">
        <router-link :to="{name: 'blog.single', params: {id: item.id}}">{{ item.title }}</router-link>
      </h2>
      <!-- meta -->
      <ul class="meta list-inline">
        <li class="list-inline-item">{{ dateFormat(item.created_at) }}</li>
        <li class="list-inline-item" v-for="tag in item.tags" :key="tag.id">
          <span class="tag-link" @click="tagLinkClick(tag.name)">{{ tag.name }}</span>
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
      <router-link :to="{name: 'blog.single', params: {id: item.id}}" class="btn btn-default"
        >Read more</router-link
      >
    </footer>
  </article>
</template>

<script>
import { mapActions } from 'vuex';
import DateFormat from "../../mixins/DateFormat";

export default {
  mixins: [DateFormat],

  methods: {
    ...mapActions('posts', ['fetchPostsByTag']),

    tagLinkClick(tag) {
      this.$router.push({name: 'blog.by-tag', params: {tag}});
      this.fetchPostsByTag({tag});
    }
  },

  props: {
    posts: {
      required: true,
    },
  },
};
</script>

<style scoped>
.tag-link {
  cursor: pointer;
}
</style>
