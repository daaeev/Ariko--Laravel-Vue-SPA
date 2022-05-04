<template>
    <!-- overlay dark menu -->
    <div class="overlay-menu d-flex align-items-start flex-column">
        <!-- logo, close icon, social icons -->
        <nav class="navbar navbar-light">
            <div class="container">
                <!-- logo light -->
                <a class="navbar-brand" href="index.html"
                ><img src="images/logo-light.png" alt="logo"
                /></a>
                <!-- close icon -->
                <div class="close-icon">
                    <i class="ion-md-close"></i>
                </div>
                <!-- social icons -->
                <ul class="social-icons light list-inline">
                    <li class="list-inline-item">
                        <a href="#"><i class="fab fa-dribbble"></i></a>
                    </li>
                    <li class="list-inline-item">
                        <a href="#"><i class="fab fa-twitter"></i></a>
                    </li>
                    <li class="list-inline-item">
                        <a href="#"><i class="fab fa-instagram"></i></a>
                    </li>
                    <li class="list-inline-item">
                        <a href="#"><i class="fab fa-linkedin-in"></i></a>
                    </li>
                </ul>
            </div>
        </nav>

        <!-- navigation menu -->
        <ul class="vertical-menu mt-auto">
            <li>
                <a href="index.html">Works</a>
                <ul class="submenu">
                    <li><a href="portfolio-single-1.html">Single Parallax</a></li>
                    <li><a href="portfolio-single-2.html">Single Slideshow</a></li>
                    <li><a href="portfolio-single-3.html">Single Video</a></li>
                </ul>
            </li>
            <li><a href="about.html">About</a></li>
            <li>
                <a href="blog.html">Journal</a>
                <ul class="submenu">
                    <li><a href="blog.html">List</a></li>
                    <li><a href="blog-single.html">Single Post</a></li>
                </ul>
            </li>
            <li><a href="contact.html">Contact</a></li>
        </ul>

        <!-- copyright -->
        <footer class="text-center mt-auto">
            <div class="container">
                <span class="copyright">© 2018 PxlSolutions Media, Inc</span>
            </div>
        </footer>
    </div>

</template>

<script>
export default {
    name: "header-overlay-menu-component"
}
</script>

<style scoped>

</style>
