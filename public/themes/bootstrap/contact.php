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
            <p class="ml-2 lead sw-text" data-component="lead"><?php get_component("lead"); ?></p>
        </div>
    </div>
    <div class="container">
        <form action="<?php get_site_url(); ?>/contact.php" method="POST">
            <div class="row">
                <div class="col-12 col-md-6 mb-3">
                    <label for="name">Name</label>
                    <input type="text" class="form-control" name="name" id="name" placeholder="" required />
                </div>
                <div class="col-12 col-md-6 mb-3">
                    <label for="email">Email</label>
                    <input type="email" class="form-control" name="email" id="email" placeholder="you@example.com" required />
                </div>
                <div class="col-12">
                    <label for="message">Message</label>
                    <textarea class="form-control" name="message" id="message" placeholder="" required ></textarea>
                </div>
            </div>
        </form>
    </div>
</main>

<?php
include __DIR__ . "/inc/footer.inc.php";
?>