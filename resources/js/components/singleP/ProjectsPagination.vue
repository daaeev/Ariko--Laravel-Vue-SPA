<template>
    <div class="container clearfix">
        <div class="navigation">
            <router-link :to="'/works/photos/' + prevSingle" v-if="prevSingle" @click.native="dispatchFetchSingle(prevSingle)" class="prev float-left"><i class="ion-md-arrow-back"></i>Previous Project</router-link>
            <router-link :to="'/works/photos/' + nextSingle" v-if="nextSingle" @click.native="dispatchFetchSingle(nextSingle)" class="next float-right">Next Project<i class="ion-md-arrow-forward"></i></router-link>
        </div>
    </div>
</template>

<script>
import {mapGetters} from "vuex";

export default {
    props: {
        work_id: {
            type: Number,
            required: true
        }
    },

    computed: {
        ...mapGetters('photos', ['nextSingle', "prevSingle"]),
    },

    methods: {
        dispatchFetchSingle(work_id) {
            this.$store.dispatch('photos/facadeFetchWorkById', work_id);

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
