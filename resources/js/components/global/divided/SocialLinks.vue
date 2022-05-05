<template>
    <!-- social icons -->
    <ul class="social-icons list-inline" v-if="social_links.length != 0">
        <li class="list-inline-item" v-for="link in social_links" :key="link.url">
            <a :href="link.url"><i :class="link.icon_class"></i></a>
        </li>
    </ul>
</template>

<script>
import {mapGetters} from 'vuex'

export default {
    computed: {
        ...mapGetters('app', ['social_links']),
    },
}
</script>

<style scoped>

</style>
