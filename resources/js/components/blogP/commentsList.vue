<template>
  <div class="single-comment" v-for="item in comments" :key="item.id">
    <h5 class="name mt-0">{{item.name}}</h5>
    <span class="date">{{ dateFormat(item.created_at) }}</span>
    <p>
      {{item.comment}}
    </p>
  </div>
</template>

<script>
import DateFormat from '../../mixins/DateFormat';

export default {
    mixins: [DateFormat],

    props: {
        comments: {
            required: true
        },
    }
};
</script>

<style>
</style>