<template>
    <!-- pagination -->
    <div class="text-center load-more">
        <!-- load more button -->
        <div class="load-more-block" v-if="(pagPage != totalPagesCount)">
            <div v-if="!isWorksLoading">
                <small>Load more</small>

                <div>
                    <button @click="this.$emit('loadmore')">
                        <i class="icon ion-md-arrow-down"></i>
                    </button>
                </div>
            </div>

            <img v-else src="/images/ajax-loader.gif">
        </div>

        <span v-else>No more works...</span>
    </div>

</template>

<script>
export default {
    props: {
        pagPage: {
            required: true,
        },
        totalPagesCount: {
            required: true,
        },
        isWorksLoading: {
            required: true,
        }
    },

    emits: ['loadmore'],
}
</script>

<style scoped>

</style>
