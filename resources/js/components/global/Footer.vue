<template>
  <!-- site footer -->
<footer class="text-center">
	<div class="container">
        <social-links></social-links>

		<copyright></copyright>
	</div>
</footer>
</template>

<script>
import SocialLinks from "./divided/SocialLinks";
import Copyright from "./divided/Copyright";
export default {
  name: "footer-component",
    components: {Copyright, SocialLinks},
};
</script>

<style>
</style>
