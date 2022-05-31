<template>
  <section class="blog-content">
    <div class="container container-padding" v-if="posts.length">
      <div v-if="!isPostsLoading">
        <posts-list :posts="posts"></posts-list>

        <pagination
          :pagPage="pagPage"
          :totalPagesCount="totalPagesCount"
          @changePage="changePage"
        ></pagination>
      </div>

      <div class="text-center" v-else>
        <img src="/images/ajax-loader.gif" />
      </div>
    </div>

    <div class="text-center" v-else>
      <h3>Empty!</h3>
    </div>
  </section>

  <email-section :email="email"></email-section>
</template>

<script>
import SiteSettings from "../SiteSettings";
import EmailSection from "../components/global/divided/EmailSection.vue";
import PostsList from "../components/blogP/postsList.vue";
import Pagination from "../components/blogP/pagination.vue";
import { mapActions, mapGetters } from "vuex";

export default {
  components: { EmailSection, PostsList, Pagination },

  computed: {
    ...mapGetters("posts", [
      "posts",
      "isPostsLoading",
      "totalPagesCount",
      "pagPage",
    ]),

    email() {
      return SiteSettings.email;
    },
  },

  methods: {
    ...mapActions("posts", ["fetchPosts", "fetchPostsByTag"]),

    changePage(page) {
      if (this.$route.params?.tag) {
        const tag = this.$route.params.tag;

        this.fetchPostsByTag({page, tag});
      } else {
        this.fetchPosts(page);
      }

      // Scroll to top of body
      $("body,html").animate(
        {
          scrollTop: 0,
        },
        500
      );
    },
  },

  created() {
    if (this.$route.params?.tag) {
      const tag = this.$route.params.tag;

      this.fetchPostsByTag({tag});
    } else {
      this.fetchPosts();
    }
  },
};
</script>

<style>
</style>
