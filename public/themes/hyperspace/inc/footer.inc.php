<!-- Footer -->
<footer id="footer" class="wrapper alt">
    <div class="inner">
        <ul class="menu">
            <?php output_conditional("<li>&copy; " . date('Y') . " [[VAR]].  All rights reserved.</li>", get_setting("businessname")); ?>
            <li>Design: <a href="http://html5up.net">HTML5 UP</a> and <a href="https://netsyms.com">Netsyms</a></li>
        </ul>
    </div>
</footer>

<!-- Scripts -->
<script src="<?php get_theme_url(); ?>/assets/js/jquery.min.js"></script>
<script src="<?php get_theme_url(); ?>/assets/js/jquery.scrollex.min.js"></script>
<script src="<?php get_theme_url(); ?>/assets/js/jquery.scrolly.min.js"></script>
<script src="<?php get_theme_url(); ?>/assets/js/skel.min.js"></script>
<script src="<?php get_theme_url(); ?>/assets/js/util.js"></script>
<!--[if lte IE 8]><script src="<?php get_theme_url(); ?>/assets/js/ie/respond.min.js"></script><![endif]-->
<script src="<?php get_theme_url(); ?>/assets/js/main.js"></script>