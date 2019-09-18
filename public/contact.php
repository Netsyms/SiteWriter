<?php
/*
 * This Source Code Form is subject to the terms of the Mozilla Public
 * License, v. 2.0. If a copy of the MPL was not distributed with this
 * file, You can obtain one at http://mozilla.org/MPL/2.0/.
 */
ignore_user_abort(true);

require __DIR__ . "/../lib/requiredpublic.php";

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

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

if (empty($_POST['name']) || empty($_POST['message']) || !filter_var($_POST['real_email'], FILTER_VALIDATE_EMAIL) || !empty($_POST['email'])) {
    $content = <<<END
<p>Whoops!  You didn't fill out the contact form properly.</p>
<p><a href="javascript:history.back()" class="btn btn-primary btn-sm">Go back</a> and try again.</p>
END;
    output_card($content);
    die();
}

$siteid = getsiteid();

$database->insert("messages", [
    "siteid" => $siteid,
    "name" => htmlspecialchars($_POST['name']),
    "email" => htmlspecialchars($_POST['real_email']),
    "message" => htmlspecialchars($_POST['message']),
    "date" => date("Y-m-d H:i:s")
]);

$content = <<<END
<h2 class="card-title">Thanks!</h2>
<p>Your message has been sent!</p>
<a href="./" class="btn btn-success">Continue</a>
END;

output_card($content);
ob_flush();
flush();


if ($database->has('settings', ["AND" => ['siteid' => $siteid, 'key' => 'contactemail']])) {
    $emailto = $database->get('settings', "value", ["AND" => ['siteid' => $siteid, 'key' => 'contactemail']]);
    // Setup mailer
    $mail = new PHPMailer(true);
    $mail->isSMTP();
    $mail->Host = $SETTINGS["email"]["host"];
    $mail->SMTPAuth = $SETTINGS["email"]["auth"];
    if ($SETTINGS["email"]["auth"]) {
        $mail->Username = $SETTINGS["email"]["user"];
        $mail->Password = $SETTINGS["email"]["password"];
    }
    if ($SETTINGS["email"]["secure"] != "none") {
        $mail->SMTPSecure = $SETTINGS["email"]["secure"];
    }
    $mail->Port = $SETTINGS["email"]["port"];
    $mail->isHTML(true);
    $mail->setFrom($SETTINGS["email"]["fromaddress"], $SETTINGS["email"]["fromname"]);

    $mail->addAddress($emailto);
    $mail->addReplyTo($_POST['email'], $_POST['name']);

    $mail->Subject = 'Website Contact Form Message';
    $mail->Body = '<p><b>From:</b> ' . htmlspecialchars($_POST['name']) . ' &lt;<a href="mailto:' . htmlspecialchars($_POST['email']) . '">' . $_POST['email'] . '</a>&gt;</p>'
            . '<p><b>Message:</b> <br>' . htmlspecialchars($_POST['message']) . '</p>';
    $mail->AltBody = "From: $_POST[name] <$_POST[email]>\r\n\r\nMessage: \r\n$_POST[message]";

    $mail->send();
}