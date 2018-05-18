<!-- BG -->
<style>
    #bg:after {
        background-image: url(<?php get_file_url(get_complex_component("page-bg-image", "index", ['image'])['image']); ?>);
    }
</style>
<div id="bg"></div>

<!-- Scripts -->
<script src="<?php get_theme_url(); ?>/assets/js/jquery.min.js"></script>
<script src="<?php get_theme_url(); ?>/assets/js/skel.min.js"></script>
<script src="<?php get_theme_url(); ?>/assets/js/util.js"></script>
<script src="<?php get_theme_url(); ?>/assets/js/main.js"></script>
<?php get_footer(); ?>