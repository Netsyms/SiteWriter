<!-- Footer -->
<div id="footer-wrapper">
    <footer id="footer" class="container">
        <div class="row">
            <div class="6u 12u$(medium) 12u$(small)">

                <!-- Contact -->
                <section class="widget contact">
                    <?php output_conditional("<h3>[[VAR]]</h3>", get_setting("businessname")); ?>
                    <p>
                        <?php
                        format_special(get_setting("phone"), SPECIAL_TYPE_PHONE, '<div style="display: flex;"><div style="margin-right: 10px;"><i class="fas fa-phone fa-fw"></i></div> <div style="font-size: 130%;"><a href="[[CONTENT]]">[[TITLE]]</a></div></div>');
                        format_special(get_setting("address"), SPECIAL_TYPE_ADDRESS, '<div style="display: flex;"><div style="margin-right: 10px;"><i class="fas fa-map fa-fw"></i></div> <div><a href="[[CONTENT]]">[[TITLE]]</a></div></div>');
                        format_special(get_setting("email"), SPECIAL_TYPE_EMAIL, '<div style="display: flex;"><div style="margin-right: 10px;"><i class="fas fa-envelope fa-fw"></i></div> <div><a href="[[CONTENT]]">[[TITLE]]</a></div></div>');
                        ?>
                    </p>
                    <ul>
                        <?php
                        $social = get_socialmedia_urls();
                        foreach ($social as $s) {
                            ?>
                            <li>
                                <a href="<?php echo $s['url']; ?>" class="icon <?php echo $s['icon']; ?>">
                                    <span class="label"><?php echo $s['name']; ?></span>
                                </a>
                            </li>
                            <?php
                        }
                        ?>
                    </ul>
                </section>

            </div>
            <?php
            $links = get_footer_urls();
            $links1 = [];
            $links2 = [];
            foreach ($links as $l) {
                if (count($links1) < 5) {
                    $links1[] = $l;
                } else {
                    $links2[] = $l;
                }
            }
            ?>
            <div class="3u 6u(medium) 12u$(small)">

                <!-- Links -->
                <section class="widget links">
                    <h3>Links</h3>
                    <ul class="style2">
                        <?php
                        foreach ($links1 as $l) {
                            ?>
                            <li>
                                <a href="<?php echo $l['link']; ?>">
                                    <?php echo $l['title']; ?>
                                </a>
                            </li>
                            <?php
                        }
                        ?>
                    </ul>
                </section>

            </div>
            <div class="3u 6u$(medium) 12u$(small)">

                <!-- Links -->
                <section class="widget links">
                    <h3>&nbsp;</h3>
                    <ul class="style2">
                        <?php
                        foreach ($links2 as $l) {
                            ?>
                            <li>
                                <a href="<?php echo $l['link']; ?>">
                                    <?php echo $l['title']; ?>
                                </a>
                            </li>
                            <?php
                        }
                        ?>
                    </ul>
                </section>

            </div>
        </div>
        <div class="row">
            <div class="12u">
                <div id="copyright">
                    <ul class="menu">
                        <?php output_conditional("<li>&copy; " . date('Y') . " [[VAR]].  All rights reserved.</li>", get_setting("businessname")); ?>
                        <li>Design: <a href="http://html5up.net">HTML5 UP</a> and <a href="https://netsyms.com">Netsyms</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </footer>
</div>