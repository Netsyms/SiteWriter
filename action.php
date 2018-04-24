<?php

/* This Source Code Form is subject to the terms of the Mozilla Public
 * License, v. 2.0. If a copy of the MPL was not distributed with this
 * file, You can obtain one at http://mozilla.org/MPL/2.0/. */

/**
 * Make things happen when buttons are pressed and forms submitted.
 */
require_once __DIR__ . "/required.php";

if ($VARS['action'] !== "signout") {
    dieifnotloggedin();
}

/**
 * Redirects back to the page ID in $_POST/$_GET['source'] with the given message ID.
 * The message will be displayed by the app.
 * @param string $msg message ID (see lang/messages.php)
 * @param string $arg If set, replaces "{arg}" in the message string when displayed to the user.
 */
function returnToSender($msg, $arg = "") {
    global $VARS;
    if ($arg == "") {
        header("Location: app.php?page=" . urlencode($VARS['source']) . "&msg=" . $msg);
    } else {
        header("Location: app.php?page=" . urlencode($VARS['source']) . "&msg=$msg&arg=$arg");
    }
    die();
}

switch ($VARS['action']) {
    case "saveedits":
        header("Content-Type: application/json");
        $slug = $VARS['slug'];
        $site = $VARS['site'];
        $content = $VARS['content'];
        if ($database->has("pages", ["AND" => ["slug" => $slug, "siteid" => $site]])) {
            $pageid = $database->get("pages", "pageid", ["AND" => ["slug" => $slug, "siteid" => $site]]);
        } else {
            die(json_encode(["status" => "ERROR", "msg" => "Invalid page or site"]));
        }
        foreach ($content as $name => $value) {
            if (is_array($value)) {
                $json = json_encode($value);
                if ($database->has("complex_components", ["AND" => ["pageid" => $pageid, "name" => $name]])) {
                    $database->update("complex_components", ["content" => $json], ["AND" => ["pageid" => $pageid, "name" => $name]]);
                } else {
                    $database->insert("complex_components", ["name" => $name, "content" => $json, "pageid" => $pageid]);
                }
            } else {
                if ($database->has("components", ["AND" => ["pageid" => $pageid, "name" => $name]])) {
                    $database->update("components", ["content" => $value], ["AND" => ["pageid" => $pageid, "name" => $name]]);
                } else {
                    $database->insert("components", ["name" => $name, "content" => $value, "pageid" => $pageid]);
                }
            }
        }
        exit(json_encode(["status" => "OK"]));
        break;
    case "signout":
        session_destroy();
        header('Location: index.php');
        die("Logged out.");
}