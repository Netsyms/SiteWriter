<footer>
    <ul class="icons">
        <?php
        output_conditional('<li><a href="[[VAR]]" class="fab fa-facebook-f"><span class="label">Facebook</span></a></li>', get_setting("facebook"));
        output_conditional('<li><a href="[[VAR]]" class="fab fa-twitter"><span class="label">Twitter</span></a></li>', get_setting("twitter"));
        output_conditional('<li><a href="[[VAR]]" class="fab fa-youtube"><span class="label">YouTube</span></a></li>', get_setting("youtube"));
        output_conditional('<li><a href="[[VAR]]" class="fab fa-instagram"><span class="label">Instagram</span></a></li>', get_setting("instagram"));
        output_conditional('<li><a href="[[VAR]]" class="fab fa-snapchat"><span class="label">Snapchat</span></a></li>', get_setting("snapchat"));
        output_conditional('<li><a href="[[VAR]]" class="fab fa-google-plus-g"><span class="label">Google+</span></a></li>', get_setting("google-plus"));
        output_conditional('<li><a href="[[VAR]]" class="fab fa-skype"><span class="label">Skype</span></a></li>', get_setting("skype"));
        output_conditional('<li><a href="[[VAR]]" class="fab fa-telegram"><span class="label">Twitter</span></a></li>', get_setting("telegram"));
        output_conditional('<li><a href="[[VAR]]" class="fab fa-vimeo"><span class="label">Vimeo</span></a></li>', get_setting("vimeo"));
        output_conditional('<li><a href="[[VAR]]" class="fab fa-whatsapp"><span class="label">Whatsapp</span></a></li>', get_setting("whatsapp"));
        output_conditional('<li><a href="[[VAR]]" class="fab fa-linkedin"><span class="label">LinkedIn</span></a></li>', get_setting("linkedin"));
        output_conditional('<li><a href="[[VAR]]" class="fas fa-asterisk"><span class="label">diaspora*</span></a></li>', get_setting("diaspora"));
        output_conditional('<li><a href="[[VAR]]" class="fab fa-mastodon"><span class="label">Mastodon</span></a></li>', get_setting("mastodon"));
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