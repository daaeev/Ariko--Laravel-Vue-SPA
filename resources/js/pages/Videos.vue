<template>

<!-- home big text -->
<section class="home-text text-center">
	<div class="container">
		<h2>Design & Build Effective Digital Experiences.</h2>
		<span>A wonderful serenity has taken possession of my entire soul.</span>
	</div>
</section>

    <!-- portfolio section -->
    <section class="home-portfolio">
        <div class="container">
            <videos-list :works="works"></videos-list>

            <pagination :pag-page="pagPage" :total-pages-count="totalPagesCount" :is-works-loading="isWorksLoading" @loadmore="fetchWorks"></pagination>
        </div>
    </section>

    <hr>

    <email-section :email="email"></email-section>

</template>

<script>
import VideosList from "../components/videosP/VideosList";
import Pagination from "../components/pagination/DynamicPagination";
import EmailSection from "../components/global/divided/EmailSection";
import {mapGetters, mapActions} from "vuex";
import siteSettings from "../SiteSettings";

export default {
    components: {EmailSection, Pagination, VideosList},

    computed: {
        ...mapGetters('videos', ['works', 'pagPage', 'totalPagesCount', 'isWorksLoading']),

        email() {
            return siteSettings.email;
        }
    },

    methods: {
        ...mapActions('videos', ['fetchWorks']),
    }
}
</script>

<style>

</style>
