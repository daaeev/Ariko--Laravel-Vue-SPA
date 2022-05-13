<template>
    <!-- social icons -->
    <ul class="social-icons list-inline" v-if="social_links.length != 0">
        <li class="list-inline-item" v-for="link in social_links" :key="link.icon_class">
            <a :href="link.url"><i :class="link.icon_class"></i></a>
        </li>
    </ul>
</template>

<script>

export default {
    props: {
        social_links: {
            type: Array,
            required: true,
        }
    }
}
</script>

<style scoped>

</style>
