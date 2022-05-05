<template>

        <div class="row clearfix">
            <!-- col -->
            <div class="col-md-4 col-sm-6 grid-item" v-for="work in works" :key="work.id">
                <!-- portfolio item -->
                <div class="portfolio-item">
                    <!-- hover -->
                    <div class="hover">

                        <div class="info">
                            <!-- title -->
                            <h3><router-link :to="'/works/photos/' + work.id">{{work.name}}</router-link></h3>
                            <span>{{work.subject}}</span>
                            <!-- icons -->
                            <div class="icons">
                                <router-link :to="'/works/photos/' + work.id" class="more-link">
                                    <i class="ion-md-arrow-forward"></i>
                                </router-link>

                                <i class="ion-md-heart-empty favorite"></i>
                            </div>
                        </div>

                        <!-- background overlay -->
                        <div class="overlay">
                            <span></span>
                            <span></span>
                            <span></span>
                            <span></span>
                        </div>
                    </div>
                    <!-- image -->
                    <img :src="'/storage/photos/' + work.preview.image" alt="portfolio-item" />
                </div>
            </div>
        </div>

</template>

<script>
export default {
    props: {
        works: {
            type: Array,
            required: true
        },
    },
}
</script>

<style scoped>

</style>
