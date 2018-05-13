<?php
/*
 * This Source Code Form is subject to the terms of the Mozilla Public
 * License, v. 2.0. If a copy of the MPL was not distributed with this
 * file, You can obtain one at http://mozilla.org/MPL/2.0/.
 */
?>

<footer class="footer jumbotron mt-5 mb-0">
    <div class="row">
        <div class="col-12 col-md-6">
            <ul class="list-unstyled ml-4">
                <?php
                $links = get_footer_urls();
                foreach ($links as $l) {
                    ?>
                    <li>
                        <a href="<?php echo $l['link']; ?>">
                            <i class="fas fa-arrow-right"></i> <?php echo $l['title']; ?>
                        </a>
                    </li>
                    <?php
                }
                ?>
            </ul>
        </div>
        <div class="col-12 col-md-6">
            <?php output_conditional("<h4>[[VAR]]</h4>", get_setting("businessname")); ?>
            <?php
            output_conditional('<div class="d-flex"><div class="mr-2"><i class="fas fa-phone fa-fw"></i></div> <div><a style="font-size: 130%; color: inherit;" href="tel:[[VAR]]">' . get_setting("phone") . '</a></div></div>', preg_replace("/[^0-9+]/", "", get_setting("phone")));
            output_conditional('<div class="d-flex"><div class="mr-2"><i class="fas fa-map fa-fw"></i></div> <div>[[VAR]]</div></div>', str_replace("\n", "<br />\n", get_setting("address")));
            output_conditional('<div class="d-flex"><div class="mr-2"><i class="fas fa-envelope fa-fw"></i></div> <div>[[VAR]]</div></div>', get_setting("email"));
            ?>
        </div>
    </div>

    <div class="text-center my-4">
        <?php
        $social = get_socialmedia_urls();
        foreach ($social as $s) {
            ?>
            <a class="btn btn-outline-primary m-1" href="<?php echo $s['url']; ?>">
                <i class="<?php echo $s['icon']; ?> fa-fw"></i>
                <span class="sr-only"><?php echo $s['name']; ?></span>
            </a>
            <?php
        }
        ?>
    </div>
    <div class="text-center text-muted font-weight-light mt-4">
        <?php output_conditional("&copy; " . date('Y') . " [[VAR]].  All rights reserved.", get_setting("businessname")); ?>
        <br />
        <span>Design by <a href="https://netsyms.com">Netsyms Technologies</a>.</span>
    </div>
</footer>

<script src="<?php get_theme_url(); ?>/assets/jquery-3.3.1.slim.min.js"></script>
<script src="<?php get_theme_url(); ?>/assets/popper.min.js"></script>
<script src="<?php get_theme_url(); ?>/assets/bootstrap.min.js"></script>
<?php get_footer(); ?>