<template>
    <!-- navigation menu -->
    <ul class="vertical-menu mt-auto">
        <li v-for="item in overlay_menu" :key="item.title">
            <router-link :to="{name: item.url}">{{ item.title }}</router-link>

            <ul class="submenu" v-if="item.submenu.length != 0">
                <li v-for="subitem in item.submenu" :key="subitem.title">
                    <router-link :to="{name: subitem.url}">{{ subitem.title }}</router-link>
                </li>
            </ul>
        </li>
    </ul>
</template>

<script>
export default {
    props: {
        overlay_menu: {
            type: Array,
            required: true,
        },
    }
}
</script>

<style scoped>

</style>
