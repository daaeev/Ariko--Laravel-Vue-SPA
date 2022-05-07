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

        <projects-pagination :work_id="single.id"></projects-pagination>

    </section>
</template>

<script>
import {mapGetters} from "vuex";
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
        this.$store.dispatch('photos/facadeFetchWorkById', this.$route.params.id);
    },

    computed: {
        ...mapGetters('photos', ['single']),
    }
}
</script>

<style scoped>

</style>
