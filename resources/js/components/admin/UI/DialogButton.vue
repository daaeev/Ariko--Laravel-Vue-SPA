<template>
  <button class="dialog-button" @click="this.$emit('update:show', true)"><slot></slot></button>
</template>

<script>
export default {
    props: {
        show: {
            required: true
        },
    },
}
</script>

<style>
.dialog-button {
    -webkit-transition-duration: 0.4s;
    transition-duration: 0.4s;
    background-color: white;
    border: 2px solid #000000;
    border-radius: 7px;
    color: #000000;
    padding: 15px 32px;
    text-align: center;
    text-decoration: none;
    display: inline-block;
    font-size: 16px;
    cursor: pointer;
    white-space: nowrap;
}

.dialog-button:hover {
    background-color: #3f3f3f;
    color: white;
}
</style>