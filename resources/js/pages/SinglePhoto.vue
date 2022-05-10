<template>
    <section class="content portfolio-single">
        <div class="container container-padding">

            <div class="title text-center">
                <h1>{{single.name}}</h1>
                <div>{{single.subject}}</div>
            </div>

        </div>

        <portfolio-info :year="single.year" :client="single.client" :website="single.website"></portfolio-info>

        <image-slider :images="single.images"></image-slider>

        <div class="container container-padding">
            <h2 class="mt-15 mb-4">{{single.title}}</h2>
            <p>{{single.description}}</p>
        </div>

        <projects-pagination :next_id="singlePagination.next_id" :prev_id="singlePagination.prev_id" @loadsingle="fetchSingleByPagination"></projects-pagination>

    </section>
</template>

<script>
import {mapActions, mapGetters} from "vuex";
import ImageSlider from "../components/singleP/ImageSlider";
import PortfolioInfo from "../components/singleP/PortfolioInfo";
import ProjectsPagination from "../components/singleP/ProjectsPagination";

export default {
    components: {
        ProjectsPagination,
        PortfolioInfo,
        ImageSlider,
    },

    created() {
        this.fetchWorkById(this.$route.params.id);
    },

    computed: {
        ...mapGetters('photos', ['single', 'singlePagination']),
    },

    methods: {
        ...mapActions('photos', ['fetchWorkById']),

        fetchSingleByPagination(work_id) {
            this.fetchWorkById(work_id);

            // Scroll to top of body
            $('body,html').animate({
                scrollTop : 0
            }, 500);
        }
    }
}
</script>

<style scoped>

</style>
