<template>

    <h3 class="mt-10 mb-4">Our capabilities</h3>
    <ul>
        <li v-for="item in capabilities" :key="item">{{item}}</li>
    </ul>

</template>

<script>
export default {
    props: {
        capabilities: {
            type: Array,
            required: true
        }
    },
}
</script>

<style scoped>

</style>
