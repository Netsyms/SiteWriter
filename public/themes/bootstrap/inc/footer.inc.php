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
        </div>
        <div class="col-12 col-md-6">
            <?php output_conditional("<h4>[[VAR]]</h4>", get_setting("businessname")); ?>
            <?php
            output_conditional('<div class="d-flex"><div class="mr-2"><i class="fas fa-phone fa-fw"></i></div> <div style="font-size: 130%;">[[VAR]]</div></div>', get_setting("phone"));
            output_conditional('<div class="d-flex"><div class="mr-2"><i class="fas fa-map fa-fw"></i></div> <div>[[VAR]]</div></div>', str_replace("\n", "<br />\n", get_setting("address")));
            output_conditional('<div class="d-flex"><div class="mr-2"><i class="fas fa-envelope fa-fw"></i></div> <div>[[VAR]]</div></div>', get_setting("email"));
            ?>
        </div>
    </div>

    <div class="text-center my-4">
        <?php
        output_conditional('<a class="btn btn-outline-primary m-1" href="[[VAR]]"><i class="fab fa-facebook-f fa-fw"></i><span class="sr-only">Facebook</span></a>', get_setting("facebook"));
        output_conditional('<a class="btn btn-outline-primary m-1" href="[[VAR]]"><i class="fab fa-twitter fa-fw"></i><span class="sr-only">Twitter</span></a>', get_setting("twitter"));
        output_conditional('<a class="btn btn-outline-primary m-1" href="[[VAR]]"><i class="fab fa-youtube fa-fw"></i><span class="sr-only">YouTube</span></a>', get_setting("youtube"));
        output_conditional('<a class="btn btn-outline-primary m-1" href="[[VAR]]"><i class="fab fa-instagram fa-fw"></i><span class="sr-only">Instagram</span></a>', get_setting("instagram"));
        output_conditional('<a class="btn btn-outline-primary m-1" href="[[VAR]]"><i class="fab fa-snapchat fa-fw"></i><span class="sr-only">Snapchat</span></a>', get_setting("snapchat"));
        output_conditional('<a class="btn btn-outline-primary m-1" href="[[VAR]]"><i class="fab fa-google-plus-g fa-fw"></i><span class="sr-only">Google+</span></a>', get_setting("google-plus"));
        output_conditional('<a class="btn btn-outline-primary m-1" href="[[VAR]]"><i class="fab fa-skype fa-fw"></i><span class="sr-only">Skype</span></a>', get_setting("skype"));
        output_conditional('<a class="btn btn-outline-primary m-1" href="[[VAR]]"><i class="fab fa-telegram fa-fw"></i><span class="sr-only">Twitter</span></a>', get_setting("telegram"));
        output_conditional('<a class="btn btn-outline-primary m-1" href="[[VAR]]"><i class="fab fa-vimeo fa-fw"></i><span class="sr-only">Vimeo</span></a>', get_setting("vimeo"));
        output_conditional('<a class="btn btn-outline-primary m-1" href="[[VAR]]"><i class="fab fa-whatsapp fa-fw"></i><span class="sr-only">Whatsapp</span></a>', get_setting("whatsapp"));
        output_conditional('<a class="btn btn-outline-primary m-1" href="[[VAR]]"><i class="fab fa-linkedin fa-fw"></i><span class="sr-only">LinkedIn</span></a>', get_setting("linkedin"));
        output_conditional('<a class="btn btn-outline-primary m-1" href="[[VAR]]"><i class="fas fa-asterisk fa-fw"></i><span class="sr-only">diaspora*</span></a>', get_setting("diaspora"));
        output_conditional('<a class="btn btn-outline-primary m-1" href="[[VAR]]"><i class="fab fa-mastodon fa-fw"></i><span class="sr-only">Mastodon</span></a>', get_setting("mastodon"));
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