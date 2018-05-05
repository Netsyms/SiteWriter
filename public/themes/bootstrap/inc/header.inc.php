<?php
/*
 * This Source Code Form is subject to the terms of the Mozilla Public
 * License, v. 2.0. If a copy of the MPL was not distributed with this
 * file, You can obtain one at http://mozilla.org/MPL/2.0/.
 */
?>
<!DOCTYPE html>
<meta charset="utf-8">
<title><?php get_site_name(); ?></title>
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<link rel="stylesheet" href="<?php get_theme_color_url(); ?>/bootstrap.min.css" />
<script defer src="<?php get_theme_url(); ?>/assets/fontawesome-all.min.js"></script>
<?php get_header(); ?>

<nav class="navbar navbar-expand-md navbar-dark fixed-top bg-primary">
    <a class="navbar-brand" href="<?php get_site_url(); ?>"><?php get_site_name(); ?></a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbar-collapse" aria-controls="navbar-collapse" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbar-collapse">
        <ul class="navbar-nav mr-auto">
            <?php get_navigation(null, "", "nav-item", "active", "nav-link"); ?>
        </ul>
    </div>
</nav>