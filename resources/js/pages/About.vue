<template>
    <!-- content section -->
    <section class="content">
        <div class="container container-padding">

            <div class="page-header">
                <h1>It is a paradisematic country, in which roasted parts of sentences fly into your mouth.</h1>
            </div>

        </div>

        <div class="container container-padding">
            <h2 class="mt-10 mb-4">Because those who do not know how to pursue pleasure rationally encounter consequences that are extremely painful.</h2>
            <p>But I must explain to you how all this mistaken idea of denouncing pleasure and praising pain was born and I will give you a complete account of the system.</p>

            <capabilities :capabilities="capabilities"></capabilities>
        </div>

    </section>

    <email-section :email="email"></email-section>
</template>

<script>
import EmailSection from "../components/global/divided/EmailSection";
import siteSettings from "../SiteSettings";
import SiteSettings from "../SiteSettings";
import Capabilities from "../components/aboutP/Capabilities";

export default {
    components: {Capabilities, EmailSection},

    computed: {
        email() {
            return siteSettings.email;
        },

        capabilities() {
            return SiteSettings.capabilities;
        },
    }
}
</script>

<style scoped>

</style>
