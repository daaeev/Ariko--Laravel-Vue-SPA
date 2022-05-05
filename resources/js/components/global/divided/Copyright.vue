<template>
    <!-- copyright -->
    <footer class="text-center mt-auto">
        <div class="container">
            <span class="copyright">{{copyright}}</span>
        </div>
    </footer>

</template>

<script>
import {mapGetters} from "vuex";

export default {
    computed: {
        ...mapGetters('app', ['copyright']),
    }
}
</script>

<style scoped>

</style>
