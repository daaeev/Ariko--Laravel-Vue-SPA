<template>
    <div class="container clearfix">
        <div class="navigation">
            <router-link :to="'/works/photos/' + prev" v-if="prev" @click.native="dispatchFetchSingle(prev)" class="prev float-left"><i class="ion-md-arrow-back"></i>Previous Project</router-link>
            <router-link :to="'/works/photos/' + next" v-if="next" @click.native="dispatchFetchSingle(next)" class="next float-right">Next Project<i class="ion-md-arrow-forward"></i></router-link>
        </div>
    </div>
</template>

<script>
import {mapGetters} from "vuex";

export default {
    computed: {
        ...mapGetters('photos', ['fetch']),

        next() {
            return this.fetch.pagination.next_id;
        },

        prev() {
            return this.fetch.pagination.prev_id;
        },
    },

    methods: {
        dispatchFetchSingle(work_id) {
            this.fetch.facadeFetchWorkById(work_id);

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
