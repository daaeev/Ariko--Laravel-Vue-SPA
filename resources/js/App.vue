<template>
  <!-- Preloader -->
  <div id="preloader">
    <div class="loading-area">
      <h3>ariko.</h3>
      <span>loading...</span>
    </div>
    <div class="left-side"></div>
    <div class="right-side"></div>
  </div>

  <header-overlay-component></header-overlay-component>

  <!-- begin site wrapper -->
  <div class="site-wrapper">
    <header-component></header-component>

    <router-view></router-view>

    <footer-component></footer-component>
  </div>
  <!-- end site wrapper -->

  <!-- Go to top button -->
  <a href="javascript:" id="return-to-top"><i class="ion-md-arrow-up"></i></a>
</template>

<script>
export default {
  mounted() {
    // Scroll to top of body
    $("body,html").animate(
      {
        scrollTop: 0,
      },
      500
    );
  },
};
</script>

<style>
</style>
