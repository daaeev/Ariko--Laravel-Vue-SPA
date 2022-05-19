<template>
    <div class="container clearfix">
        <div class="navigation">
            <router-link :to="{name: 'works.photos.single', params: {id: prev_id}}" v-if="prev_id" @click.native="this.$emit('loadsingle', prev_id)" class="prev float-left"><i class="ion-md-arrow-back"></i>Previous Project</router-link>
            <router-link :to="{name: 'works.photos.single', params: {id: next_id}}" v-if="next_id" @click.native="this.$emit('loadsingle', next_id)" class="next float-right">Next Project<i class="ion-md-arrow-forward"></i></router-link>
        </div>
    </div>
</template>

<script>
export default {
    props: {
        next_id: {
            required: true,
        },
        prev_id: {
            required: true,
        }
    },

    emits: ['loadsingle'],
}
</script>

<style scoped>

</style>
