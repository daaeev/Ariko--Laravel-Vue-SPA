<template>
  <!-- site footer -->
  <footer class="text-center">
    <div class="container">
      <social-links :social_links="social_links"></social-links>

      <copyright>{{copyright}}</copyright>
    </div>
  </footer>
</template>

<script>
import SocialLinks from "./divided/SocialLinks";
import Copyright from "./divided/Copyright";
import SiteSettings from "../../SiteSettings";
export default {
  name: "footer-component",
  components: { Copyright, SocialLinks },

    computed: {
        copyright() {
            return SiteSettings.copyright;
        },

        social_links() {
            return SiteSettings.social_links;
        }
    },
};
</script>

<style>
</style>
