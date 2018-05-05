<?php

/* This Source Code Form is subject to the terms of the Mozilla Public
 * License, v. 2.0. If a copy of the MPL was not distributed with this
 * file, You can obtain one at http://mozilla.org/MPL/2.0/. */

/**
 * Make things happen when buttons are pressed and forms submitted.
 */
require_once __DIR__ . "/required.php";
require_once __DIR__ . "/lib/util.php";

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
    case "newpage":
        if (is_empty($VARS['siteid']) || !$database->has("sites", ["siteid" => $VARS['siteid']])) {
            returnToSender("invalid_parameters");
        }
        if (is_empty($VARS['title'])) {
            returnToSender("invalid_parameters", $VARS['siteid']);
        }
        if (!is_empty($VARS['slug'])) {
            $slug = strtolower($VARS['slug']);
            $slug = preg_replace("/[^[:alnum:][:space:]]/u", '', $slug);
            $slug = preg_replace("/[[:space:]]/u", '-', $slug);
            if ($database->has("pages", ["AND" => ["siteid" => $VARS['siteid'], "slug" => $VARS['slug']]])) {
                returnToSender("slug_taken", $VARS['siteid']);
            }
        } else {
            // Auto-generate a slug
            $slug = strtolower($VARS['title']);
            $slug = preg_replace("/[^[:alnum:][:space:]]/u", '', $slug);
            $slug = preg_replace("/[[:space:]]/u", '-', $slug);
            if ($database->has("pages", ["AND" => ["siteid" => $VARS['siteid'], "slug" => $slug]])) {
                $num = 2;
                while ($database->has("pages", ["AND" => ["siteid" => $VARS['siteid'], "slug" => $slug . $num]])) {
                    $num++;
                }
                $slug = $slug . $num;
            }
        }
        $template = "default";
        if (!is_empty($VARS['template'])) {
            $template = preg_replace("/[^A-Za-z0-9]/", '', $VARS['template']);
        }
        $theme = $database->get("sites", "theme", ["siteid" => $VARS['siteid']]);
        if (!file_exists(__DIR__ . "/public/themes/$theme/$template.php")) {
            returnToSender("template_missing", $VARS['siteid']);
        }
        $database->insert("pages", ["slug" => $slug, "siteid" => $VARS['siteid'], "title" => $VARS['title'], "template" => $VARS['template']]);
        returnToSender("page_added", $VARS['siteid'] . "|" . $database->id());
        break;
    case "pagesettings":
        if (is_empty($VARS['siteid']) || !$database->has("sites", ["siteid" => $VARS['siteid']])) {
            returnToSender("invalid_parameters");
        }
        if (is_empty($VARS['pageid']) || !$database->has("pages", ["AND" => ["pageid" => $VARS['pageid'], "siteid" => $VARS['siteid']]])) {
            returnToSender("invalid_parameters");
        }
        if (is_empty($VARS['title'])) {
            returnToSender("invalid_parameters", $VARS['siteid']);
        }
        if (is_empty($VARS['template'])) {
            returnToSender("invalid_parameters", $VARS['siteid']);
        }
        $template = preg_replace("/[^A-Za-z0-9]/", '', $VARS['template']);
        $theme = $database->get("sites", "theme", ["siteid" => $VARS['siteid']]);
        if (!file_exists(__DIR__ . "/public/themes/$theme/$template.php")) {
            returnToSender("template_missing", $VARS['siteid']);
        }
        $database->update(
                "pages", [
            "title" => $VARS['title'],
            "template" => $VARS['template']
                ], [
            "AND" => [
                "siteid" => $VARS['siteid'],
                "pageid" => $VARS['pageid']
            ]
        ]);
        returnToSender("settings_saved", $VARS['siteid'] . "|" . $VARS['pageid']);
        break;
    case "sitesettings":
        if (!is_empty($VARS['siteid'])) {
            if (!$database->has("sites", ["siteid" => $VARS['siteid']])) {
                returnToSender("invalid_parameters");
            }
        }
        if (is_empty($VARS['name'])) {
            returnToSender("invalid_parameters");
        }
        if (is_empty($VARS['url'])) {
            returnToSender("invalid_parameters");
        }
        if (is_empty($VARS['theme'])) {
            returnToSender("invalid_parameters");
        }
        if (is_empty($VARS['color'])) {
            returnToSender("invalid_parameters");
        }
        $url = formatsiteurl($VARS['url']);
        $theme = preg_replace("/[^A-Za-z0-9]/", '', $VARS['theme']);
        $color = preg_replace("/[^A-Za-z0-9]/", '', $VARS['color']);
        if (!file_exists(__DIR__ . "/public/themes/$theme/theme.json")) {
            returnToSender("invalid_parameters");
        }
        if ($color != "default" && !file_exists(__DIR__ . "/public/themes/$theme/colors/$color")) {
            returnToSender("invalid_parameters");
        }
        if (is_empty($VARS['siteid'])) {
            $database->insert('sites', ["sitename" => $VARS['name'], "url" => $url, "theme" => $theme, "color" => $color]);
            $siteid = $database->id();
            $template = (file_exists(__DIR__ . "/public/themes/$theme/home.php") ? "home" : "default");
            $database->insert('pages', ["slug" => "index", "siteid" => $siteid, "title" => "Home", "nav" => "Home", "navorder" => 1, "template" => $template]);
        } else {
            $database->update('sites', ["sitename" => $VARS['name'], "url" => $url, "theme" => $theme, "color" => $color], ["siteid" => $VARS['siteid']]);
            $siteid = $VARS['siteid'];
        }

        foreach ($VARS['settings'] as $key => $value) {
            if ($database->has('settings', ["AND" => ["siteid" => $siteid, "key" => $key]])) {
                if ($value == "") {
                    //echo "deleting $key => $value\n";
                    $database->delete('settings', ["AND" => ["siteid" => $siteid, "key" => $key]]);
                } else {
                    //echo "updating $key => $value\n";
                    $database->update('settings', ["value" => $value], ["AND" => ["siteid" => $siteid, "key" => $key]]);
                }
            } else if ($value != "") {
                $database->insert('settings', ["siteid" => $siteid, "key" => $key, "value" => $value]);
            }
        }

        returnToSender("settings_saved");
        break;
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
    case "deletemessage":
        if ($database->count('messages', ["mid" => $VARS['id']]) !== 1) {
            returnToSender("invalid_parameters");
        }
        $database->delete('messages', ["mid" => $VARS['id']]);
        returnToSender("message_deleted");
        break;
    case "signout":
        session_destroy();
        header('Location: index.php');
        die("Logged out.");
}