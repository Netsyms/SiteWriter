<!-- Footer -->
<div id="footer-wrapper">
    <footer id="footer" class="container">
        <div class="row">
            <div class="6u 6u$(medium) 12u$(small)">

                <!-- Contact -->
                <section class="widget contact">
                    <?php output_conditional("<h3>[[VAR]]</h3>", get_setting("businessname")); ?>
                    <p>
                        <?php output_conditional('<div style="display: flex;"><div style="margin-right: 10px;"><i class="fas fa-phone fa-fw"></i></div> <div style="font-size: 130%;"><a href="tel:[[VAR]]">' .get_setting("phone"). '</a></div></div>', preg_replace("/[^0-9+]/", "", get_setting("phone"))); ?>
                        <?php output_conditional('<div style="display: flex;"><div style="margin-right: 10px;"><i class="fas fa-map fa-fw"></i></div> <div>[[VAR]]</div></div>', str_replace("\n", "<br />\n", get_setting("address"))); ?>
                        <?php output_conditional('<div style="display: flex;"><div style="margin-right: 10px;"><i class="fas fa-envelope fa-fw"></i></div> <div>[[VAR]]</div></div>', get_setting("email")); ?>
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
            <div class="3u 6u(medium) 12u$(small)">

                <!-- Links -->
                <!--<section class="widget links">
                    <h3>Random Stuff</h3>
                    <ul class="style2">
                        <li><a href="#">Etiam feugiat condimentum</a></li>
                        <li><a href="#">Aliquam imperdiet suscipit odio</a></li>
                        <li><a href="#">Sed porttitor cras in erat nec</a></li>
                        <li><a href="#">Felis varius pellentesque potenti</a></li>
                        <li><a href="#">Nullam scelerisque blandit leo</a></li>
                    </ul>
                </section>-->

            </div>
            <div class="3u 6u$(medium) 12u$(small)">

                <!-- Links -->
                <!--<section class="widget links">
                    <h3>Random Stuff</h3>
                    <ul class="style2">
                        <li><a href="#">Etiam feugiat condimentum</a></li>
                        <li><a href="#">Aliquam imperdiet suscipit odio</a></li>
                        <li><a href="#">Sed porttitor cras in erat nec</a></li>
                        <li><a href="#">Felis varius pellentesque potenti</a></li>
                        <li><a href="#">Nullam scelerisque blandit leo</a></li>
                    </ul>
                </section>-->

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