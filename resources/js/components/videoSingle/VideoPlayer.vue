<template>
    <video controls :src="src">
        Your browser not support video tag.
    </video>
</template>

<script>
export default {
    props: {
        src: {
            type: String,
            required: true,
        }
    },
}
</script>

<style scoped>

</style>
