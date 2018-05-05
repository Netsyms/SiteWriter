<?php
/*
 * This Source Code Form is subject to the terms of the Mozilla Public
 * License, v. 2.0. If a copy of the MPL was not distributed with this
 * file, You can obtain one at http://mozilla.org/MPL/2.0/.
 */

require __DIR__ . "/../lib/requiredpublic.php";

if (empty($_POST['name']) || empty($_POST['message']) || !filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
    ?>
    <!DOCTYPE html>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Contact Form</title>
    <p>Whoops!  You didn't fill out the contact form properly.  <a href="javascript:history.back()">Go back</a> and try again.</p>
    <?php
    die("");
}

$database->insert("messages", [
    "siteid" => getsiteid(),
    "name" => htmlspecialchars($_POST['name']),
    "email" => htmlspecialchars($_POST['email']),
    "message" => htmlspecialchars($_POST['message']),
    "date" => date("Y-m-d H:i:s")
]);

header('Location: ./');
