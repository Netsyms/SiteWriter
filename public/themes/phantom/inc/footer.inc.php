<!-- Footer -->
<footer id="footer">
    <div class="inner">
        <section>
            <h2>Get in touch</h2>
            <form method="post" action="contact.php">
                <div class="field half first">
                    <input type="text" name="name" id="name" placeholder="Name" required />
                </div>
                <div class="field half">
                    <input type="email" name="email" id="email" placeholder="Email" required />
                </div>
                <div class="field">
                    <textarea name="message" id="message" placeholder="Message" required ></textarea>
                </div>
                <ul class="actions">
                    <li><input type="submit" value="Send" class="special" required /></li>
                </ul>
            </form>
        </section>
        <section>
            <?php output_conditional("<h2>[[VAR]]</h2>", get_setting("businessname")); ?>
            <ul class="alt contact">
                <?php
                format_special(get_setting("phone"), SPECIAL_TYPE_PHONE, '<li style="font-size: 130%;"><div class="contact-icon"><i class="fas fa-phone fa-fw"></i></div> <div><a href="[[CONTENT]]">[[TITLE]]</a></div></li>');
                format_special(get_setting("address"), SPECIAL_TYPE_ADDRESS, '<li><div class="contact-icon"><i class="fas fa-map fa-fw"></i></div> <div><a href="[[CONTENT]]">[[TITLE]]</a></div></li>');
                format_special(get_setting("email"), SPECIAL_TYPE_EMAIL, '<li><div class="contact-icon"><i class="fas fa-envelope fa-fw"></i></div> <div><a href="[[CONTENT]]">[[TITLE]]</a></div></li>');
                ?>
            </ul>
            <?php
            $social = get_socialmedia_urls();
            if (count($social) > 0) {
                ?>
                <h2>Follow</h2>
                <ul class="icons">
                    <?php
                    foreach ($social as $s) {
                        ?>
                        <li>
                            <a href="<?php echo $s['url']; ?>" class="icon style2 <?php echo $s['icon']; ?>">
                                <span class="label"><?php echo $s['name']; ?></span>
                            </a>
                        </li>
                        <?php
                    }
                    ?>
                </ul>
                <?php
            }
            ?>
        </section>
        <ul class="copyright">
            <?php output_conditional("<li>&copy; " . date('Y') . " [[VAR]].  All rights reserved.</li>", get_setting("businessname")); ?>
            <li>Design: <a href="http://html5up.net">HTML5 UP</a> and <a href="https://netsyms.com">Netsyms</a></li>
        </ul>
    </div>
</footer>

</div>

<!-- Scripts -->
<script src="<?php get_theme_url(); ?>/assets/js/jquery.min.js"></script>
<script src="<?php get_theme_url(); ?>/assets/js/skel.min.js"></script>
<script src="<?php get_theme_url(); ?>/assets/js/util.js"></script>
<!--[if lte IE 8]><script src="<?php get_theme_url(); ?>/assets/js/ie/respond.min.js"></script><![endif]-->
<script src="<?php get_theme_url(); ?>/assets/js/main.js"></script>