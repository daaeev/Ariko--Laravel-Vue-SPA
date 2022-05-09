<template>
    <!-- pagination -->
    <div class="text-center load-more">
        <!-- load more button -->
        <div class="load-more-block" v-if="(fetch.pagPage !== fetch.totalPagesCount)">
            <button @click="fetch.fetchWorks()" v-if="!isWorksLoading">
                <i class="icon ion-md-arrow-down"></i>
            </button>

            <img v-else src="/images/ajax-loader.gif">
        </div>

        <span v-else>No more works...</span>
    </div>

</template>

<script>
import {mapGetters} from "vuex";

export default {
    computed: {
        ...mapGetters('photos', ['isWorksLoading', 'fetch']),
    },
}
</script>

<style scoped>

</style>
