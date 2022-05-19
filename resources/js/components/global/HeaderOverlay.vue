<template>
    <!-- overlay dark menu -->
    <div class="overlay-menu d-flex align-items-start flex-column">
        <!-- logo, close icon, social icons -->
        <nav class="navbar navbar-light">
            <div class="container">

                <!-- logo light -->
                <router-link :to="{name: 'works.photos'}"><img src="/images/logo-light.png" alt="logo"/></router-link>

                <!-- close icon -->
                <div class="close-icon">
                    <i class="ion-md-close"></i>
                </div>

                <social-links class="light" :social_links="social_links"></social-links>

            </div>
        </nav>

        <overlay-menu :overlay_menu="overlay_menu"></overlay-menu>

        <copyright>{{copyright}}</copyright>
    </div>

</template>

<script>
import SocialLinks from "./divided/SocialLinks";
import OverlayMenu from "./divided/OverlayMenu";
import Copyright from "./divided/Copyright";
import SiteSettings from "../../SiteSettings";
export default {
    name: "header-overlay-component",
    components: {Copyright, OverlayMenu, SocialLinks},

    computed: {
        copyright() {
            return SiteSettings.copyright;
        },

        overlay_menu() {
            return SiteSettings.overlay_menu;
        },

        social_links() {
            return SiteSettings.social_links;
        }
    },
}
</script>

<style scoped>

</style>
