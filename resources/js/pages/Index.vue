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
            <photos-list :works="works"></photos-list>

            <pagination :pag-page="pagPage" :total-pages-count="totalPagesCount" :is-works-loading="isWorksLoading" @loadmore="fetchWorks"></pagination>
        </div>
    </section>

    <hr>

    <email-section :email="email"></email-section>

</template>

<script>
import PhotosList from "../components/index/PhotosList";
import Pagination from "../components/pagination/DynamicPagination";
import EmailSection from "../components/global/divided/EmailSection";
import {mapGetters, mapActions} from "vuex";
import siteSettings from "../SiteSettings";

export default {
    components: {EmailSection, Pagination, PhotosList},

    computed: {
        ...mapGetters('photos', ['works', 'pagPage', 'totalPagesCount', 'isWorksLoading']),

        email() {
            return siteSettings.email;
        }
    },

    methods: {
        ...mapActions('photos', ['fetchWorks']),
    }
}
</script>

<style>

</style>
