<footer>
    <ul class="icons">
        <?php
        $social = get_socialmedia_urls();
        foreach ($social as $s) {
            ?>
            <li>
                <a href="<?php echo $s['url']; ?>" class="<?php echo $s['icon']; ?>">
                    <span class="label"><?php echo $s['name']; ?></span>
                </a>
            </li>
            <?php
        }
        ?>
    </ul>
</footer>
</section>

<!-- Footer -->
<footer id="footer">
    <ul class="copyright">
        <?php output_conditional("<li>&copy; " . date('Y') . " [[VAR]]</li>", get_setting("businessname")); ?>
        <li>Design: <a href="http://html5up.net">HTML5 UP</a> & <a href="https://netsyms.com">Netsyms</a></li>
    </ul>
</footer>

</div>

<!-- Scripts -->
        <!--[if lte IE 8]><script src="<?php get_theme_url(); ?>/assets/js/respond.min.js"></script><![endif]-->
<script>
    if ('addEventListener' in window) {
        window.addEventListener('load', function () {
            document.body.className = document.body.className.replace(/\bis-loading\b/, '');
        });
        document.body.className += (navigator.userAgent.match(/(MSIE|rv:11\.0)/) ? ' is-ie' : '');
    }
</script>

</div>
<?php get_footer(); ?>