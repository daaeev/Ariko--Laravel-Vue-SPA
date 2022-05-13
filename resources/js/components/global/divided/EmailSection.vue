<template>
    <!-- email section -->
    <section class="cta text-center">
        <div class="container">
            <span>Start a project</span>
            <h2><a :href="'mailto:' + email">{{ email }}</a></h2>
        </div>
    </section>
</template>

<script>

export default {
    props: {
        email: {
            required: true
        },
    },
}
</script>

<style scoped>

</style>
