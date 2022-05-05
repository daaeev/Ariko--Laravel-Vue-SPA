<template>
<!-- site header -->
<header>
	<nav class="navbar navbar-light">
		<div class="container d-flex">
			<!-- logo -->
			<router-link :to="'/'"><img src="images/logo.png" alt="logo"/></router-link>

			<!-- menu icon -->
			<div class="menu-icon justify-content-end">
				<span></span>
				<span></span>
				<span></span>
			</div>

			<social-links></social-links>

		</div>
	</nav>
</header>

</template>

<script>
import SocialLinks from "./divided/SocialLinks";

export default {
  name: "header-component",
    components: {SocialLinks},
};
</script>

<style>
</style>
