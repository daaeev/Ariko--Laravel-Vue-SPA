<template>
  <section class="content portfolio-single">
    <div class="container container-padding">
      <div class="title text-center">
        <h1>{{ single.name }}</h1>
        <div>{{ single.subject }}</div>
      </div>
    </div>

    <portfolio-info
      :year="single.year"
      :client="single.client"
      :website="single.website"
    ></portfolio-info>

    <div class="container container-padding text-center">
      <video-player :src="'/storage/videos/' + single.video"></video-player>
    </div>

    <div class="container container-padding">
      <h2 class="mt-15 mb-4">{{ single.title }}</h2>
      <p>{{ single.description }}</p>
    </div>

    <projects-pagination
      :next_id="singlePagination.next_id"
      :prev_id="singlePagination.prev_id"
      :route_name="'works.videos.single'"
      @loadsingle="fetchSingleByPagination"
    ></projects-pagination>
  </section>
</template>

<script>
import { mapActions, mapGetters } from "vuex";
import PortfolioInfo from "../components/singleP/PortfolioInfo";
import ProjectsPagination from "../components/singleP/ProjectsPagination";
import VideoPlayer from "../components/videoSingle/VideoPlayer";

export default {
  components: {
    VideoPlayer,
    ProjectsPagination,
    PortfolioInfo,
  },

  async created() {
    // Scroll to top of body
    $("body,html").animate(
      {
        scrollTop: 0,
      },
      500
    );

    await this.fetchWorkById(this.$route.params.id);
  },

  computed: {
    ...mapGetters("videos", ["single", "singlePagination"]),
  },

  methods: {
    ...mapActions("videos", ["fetchWorkById"]),

    async fetchSingleByPagination(work_id) {
      // Scroll to top of body
      $("body,html").animate(
        {
          scrollTop: 0,
        },
        500
      );

      await this.fetchWorkById(work_id);
    },
  },
};
</script>

<style scoped>
</style>
