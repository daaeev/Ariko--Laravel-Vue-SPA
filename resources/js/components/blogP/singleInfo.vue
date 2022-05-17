<template>
  <!-- blog item -->
  <article class="blog-item is-single">
    <!-- header -->
    <header>
      <!-- title -->
      <h2 class="title">{{ single.title }}</h2>
      <!-- meta -->
      <ul class="meta list-inline">
        <li class="list-inline-item">{{dateFormat(single.created_at)}}</li>
        <li class="list-inline-item" v-for="tag in single.tags" :key="tag.id">
          <router-link :to="'/blog?tag=' + tag.name">{{tag.name}}</router-link>
        </li>
      </ul>
    </header>
    <!-- thumb -->
    <div class="text-center">
      <img
        :src="'/storage/posts_previews/' + single.main_image"
        alt="blog-thumb"
      />
    </div>
    <div class="content">
      {{ single.content }}
    </div>

    <footer class="clearfix">
      <tags-list :tags="single.tags"></tags-list>
    </footer>
  </article>
</template>

<script>
import TagsList from "./tagsList.vue";
import DateFormat from '../../mixins/DateFormat';

export default {
  components: { TagsList },
  mixins: [DateFormat],

  props: {
    single: {
      required: true,
    },
  },
};
</script>

<style>
</style>