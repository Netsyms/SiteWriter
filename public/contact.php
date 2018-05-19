<?php
/*
 * This Source Code Form is subject to the terms of the Mozilla Public
 * License, v. 2.0. If a copy of the MPL was not distributed with this
 * file, You can obtain one at http://mozilla.org/MPL/2.0/.
 */

require __DIR__ . "/../lib/requiredpublic.php";

function output_card($content) {
    ?>
    <!DOCTYPE html>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Contact Form</title>
    <style><?php echo file_get_contents(__DIR__ . "/../static/css/bootstrap.min.css"); ?></style>
    <div class="container d-flex justify-content-center pt-4">
        <div class="card mt-4">
            <div class="card-body text-center">
                <?php echo $content; ?>
            </div>
        </div>
    </div>
    <?php
}

if (empty($_POST['name']) || empty($_POST['message']) || !filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
    $content = <<<END
<p>Whoops!  You didn't fill out the contact form properly.</p>
<p><a href="javascript:history.back()" class="btn btn-primary btn-sm">Go back</a> and try again.</p>
END;
    output_card($content);
    die();
}

$database->insert("messages", [
    "siteid" => getsiteid(),
    "name" => htmlspecialchars($_POST['name']),
    "email" => htmlspecialchars($_POST['email']),
    "message" => htmlspecialchars($_POST['message']),
    "date" => date("Y-m-d H:i:s")
]);

$content = <<<END
<h2 class="card-title">Thanks!</h2>
<p>Your message has been sent!</p>
<a href="./" class="btn btn-success">Continue</a>
END;

output_card($content);