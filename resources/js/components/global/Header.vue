<template>
<!-- site header -->
<header>
	<nav class="navbar navbar-light">
		<div class="container d-flex">
			<!-- logo -->
			<router-link :to="{name: 'works.photos'}"><img src="/images/logo.png" alt="logo"/></router-link>

			<!-- menu icon -->
			<div class="menu-icon justify-content-end">
				<span></span>
				<span></span>
				<span></span>
			</div>

			<social-links :social_links="social_links"></social-links>

		</div>
	</nav>
</header>

</template>

<script>
import SocialLinks from "./divided/SocialLinks";
import SiteSettings from "../../SiteSettings";

export default {
  name: "header-component",
    components: {SocialLinks},

    computed: {
      social_links() {
          return SiteSettings.social_links;
      }
    },
};
</script>

<style>
</style>
