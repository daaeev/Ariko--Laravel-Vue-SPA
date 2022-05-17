<template>
  <!-- content section -->
  <section class="blog-content">
    <div class="container container-padding">
      <h1 class="d-none">Journal</h1>

      <single-info :single="single"></single-info>

      <div class="comments mt-10" v-if="single.comments ? single.comments.length != 0 : false">
        <h4 class="title mt-0 mb-5">Comments</h4>

        <comments-list :comments="single.comments"></comments-list>
      </div>

      <div class="add-comment mt-10">
        <h4 class="title mt-0 mb-5">Add comment</h4>

        <alert :type="'success'" v-if="CreateCommentSuccess">{{
          CreateCommentSuccess
        }}</alert>
        <alert :type="'danger'" v-if="CreateCommentFailed">{{
          CreateCommentFailed
        }}</alert>

        <comment-form @submit="submitCommentForm"></comment-form>
      </div>
    </div>
  </section>

  <email-section :email="email"></email-section>
</template>

<script>
import { mapActions, mapGetters } from "vuex";
import EmailSection from "../components/global/divided/EmailSection.vue";
import SiteSettings from "../SiteSettings";
import SingleInfo from "../components/blogP/singleInfo.vue";
import CommentForm from "../components/blogP/commentForm.vue";
import Alert from "../components/UI/Alert.vue";
import CommentsList from '../components/blogP/commentsList.vue';

export default {
  components: { EmailSection, SingleInfo, CommentForm, Alert, CommentsList },

  data() {
    return {
      CreateCommentSuccess: "",
      CreateCommentFailed: "",
    };
  },

  computed: {
    ...mapGetters("posts", ["single"]),

    email() {
      return SiteSettings.email;
    },

    social_links() {
      return SiteSettings.social_links;
    },
  },

  methods: {
    ...mapActions("posts", ["fetchSinglePost", "createComment"]),

    async submitCommentForm(commentObject) {
      this.CreateCommentSuccess = "";
      this.CreateCommentFailed = "";

      await this.createComment({...commentObject, post_id: this.$route.params.id})
        .then(() => this.CreateCommentSuccess = "Your comment has been create successfully")
        .catch(error => {
          if (error.response.status == 429) {
            this.CreateCommentFailed = "Comment create limit exceeded. Wait " + error.response.headers['retry-after'] + ' seconds';
          } else {
            this.CreateCommentFailed = "Oops, something wrong";
          }
        });
    },
  },

  async created() {
    await this.fetchSinglePost(this.$route.params.id);
  },
};
</script>

<style>
</style>