<template>
    <div class="swiper-block container">
        <swiper>
            <swiper-slide v-for="item in images" :key="item.id">
                <img :src="'/storage/photos/' + item.image" alt="photo_work">
            </swiper-slide>
        </swiper>
    </div>
</template>

<script>
import { Swiper, SwiperSlide } from 'swiper/vue';
import 'swiper/css';

export default {
    components: {
        Swiper,
        SwiperSlide,
    },

    props: {
        images: {
            required: true,
        }
    }
}
</script>

<style scoped>
.swiper-block {
    text-align: center;
    cursor: grab;
}
</style>
