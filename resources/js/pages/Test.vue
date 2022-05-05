<template>
    <h1>{{ test }}</h1>
</template>

<script>
import {mapGetters} from "vuex";

export default {
    computed: {
        ...mapGetters('app', ['test']),
    }
}
</script>

<style scoped>

</style>
