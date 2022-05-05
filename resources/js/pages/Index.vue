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

            <pagination></pagination>
        </div>
    </section>

<!-- email section -->
<section class="cta text-center clearfix">
	<div class="container">
		<span>Start a project</span>
		<h2><a href="#">hello@ariko.com</a></h2>
	</div>
</section>

</template>

<script>
import PhotosList from "../components/index/PhotosList";
import Pagination from "../components/UI/Pagination";
import {mapGetters} from "vuex";

export default {
    components: {Pagination, PhotosList},

    created() {
        this.$store.dispatch('photos/fetchWorks');
    },

    computed: {
        ...mapGetters('photos', ['works']),
    }
}
</script>

<style>

</style>
