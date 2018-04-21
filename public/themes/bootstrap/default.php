<?php
/*
 * This Source Code Form is subject to the terms of the Mozilla Public
 * License, v. 2.0. If a copy of the MPL was not distributed with this
 * file, You can obtain one at http://mozilla.org/MPL/2.0/.
 */

include __DIR__ . "/inc/header.inc.php";
?>

<main role="main" class="mt-5">
    <div class="jumbotron">
        <div class="container">
            <h1 class="display-4"><?php get_page_title(); ?></h1>
            <div class="ml-2 lead">
                <div class="sw-editable" data-component="lead">
                    <?php get_component("lead"); ?>
                </div>
            </div>
        </div>
    </div>
    <div class="container">
        <div class="sw-editable" data-component="content">
            <?php get_page_content(); ?>
        </div>
    </div>
</main>

<?php
include __DIR__ . "/inc/footer.inc.php";
?>