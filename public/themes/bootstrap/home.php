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
            <h1 class="display-4 sw-text" data-component="banner-title"><?php get_component("banner-title"); ?></h1>
            <div class="ml-2 lead">
                <div class="sw-text" data-component="lead">
                    <?php get_component("lead"); ?>
                </div>
            </div>
        </div>
    </div>
    <div class="container">
        <div class="row justify-content-center">
            <?php if (!is_component_empty("cardrow-1")) { ?>
            <div class="col-md">
                <div class="sw-editable" data-component="cardrow-1">
                    <?php get_component("cardrow-1"); ?>
                </div>
            </div>
            <?php } ?>
            <?php if (!is_component_empty("cardrow-2")) { ?>
            <div class="col-md">
                <div class="sw-editable" data-component="cardrow-2">
                    <?php get_component("cardrow-2"); ?>
                </div>
            </div>
            <?php } ?>
            <?php if (!is_component_empty("cardrow-3")) { ?>
            <div class="col-md">
                <div class="sw-editable" data-component="cardrow-3">
                    <?php get_component("cardrow-3"); ?>
                </div>
            </div>
            <?php } ?>
        </div>
    </div>
</main>

<?php
include __DIR__ . "/inc/footer.inc.php";
?>