<template>
    <!-- navigation menu -->
    <ul class="vertical-menu mt-auto">
        <li v-for="item in overlay_menu">
            <router-link :to="item.url">{{ item.title }}</router-link>

            <ul class="submenu" v-if="item.submenu.length != 0">
                <li v-for="subitem in item.submenu">
                    <router-link :to="subitem.url">{{ subitem.title }}</router-link>
                </li>
            </ul>
        </li>
    </ul>
</template>

<script>
import {mapGetters} from "vuex";

export default {
    computed: {
        ...mapGetters('app', ['overlay_menu']),
    },
}
</script>

<style scoped>

</style>
