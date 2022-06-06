<template>

        <div class="row clearfix">

            <transition-group name="fade">
                <!-- col -->
                <div class="col-md-4 col-sm-6 grid-item" v-for="work in works" :key="work.id">
                    <!-- portfolio item -->
                    <div class="portfolio-item">
                        <!-- hover -->
                        <div class="hover">

                            <div class="info">
                                <!-- title -->
                                <h3><router-link :to="{name: 'works.videos.single', params: {id: work.id}}">{{work.name}}</router-link></h3>
                                <span>{{work.subject}}</span>
                                <!-- icons -->
                                <div class="icons">
                                    <router-link :to="{name: 'works.videos.single', params: {id: work.id}}" class="more-link">
                                        <i class="ion-md-arrow-forward"></i>
                                    </router-link>
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
                        <!-- video -->
                        <video autoplay muted loop>
                            <source :src="'/storage/videos/' + work.video">
                            Your browser does not support the video tag.
                        </video>
                    </div>
                </div>
            </transition-group>
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
.fade-enter-active,
.fade-leave-active {
    transition: opacity 0.7s ease;
}

.fade-enter-from,
.fade-leave-to {
    opacity: 0;
}
</style>
