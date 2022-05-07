<template>
    <div :class="'col-md-' + colNum">
        <div class="item">
            <div class="parent text-center">
                <span>{{ title }}</span>
                <h4><slot></slot></h4>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    props: {
        colNum: {
            type: Number,
            required: true
        },
        title: {
            required: true,
        },
    }
}
</script>

<style scoped>

</style>
