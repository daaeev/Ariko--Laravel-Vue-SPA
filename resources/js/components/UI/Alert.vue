<template>
    <div :class="'alert alert-' + type" role="alert">
        <slot></slot>
    </div>
</template>

<script>
export default {
    props: {
        type: {
            type: String,
            required: true,
        }
    }
}
</script>

<style scoped>
.alert {
    border-radius: 5px;
}
</style>
