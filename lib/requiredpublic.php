<?php

/* This Source Code Form is subject to the terms of the Mozilla Public
 * License, v. 2.0. If a copy of the MPL was not distributed with this
 * file, You can obtain one at http://mozilla.org/MPL/2.0/. */

/**
 * This file contains global settings and utility functions.
 */
ob_start(); // allow sending headers after content
// Settings file
require __DIR__ . '/../settings.php';

if (!$SETTINGS["debug"]) {
    error_reporting(0);
} else {
    error_reporting(E_ALL);
    ini_set('display_errors', 'On');
}

// Unicode, solves almost all stupid encoding problems
header('Content-Type: text/html; charset=utf-8');

// Strip PHP version
header('X-Powered-By: PHP');

// Security
header('X-Content-Type-Options: nosniff');
header('X-XSS-Protection: 1; mode=block');
header('X-Frame-Options: "DENY"');
header('Referrer-Policy: "no-referrer, strict-origin-when-cross-origin"');
$SECURE_NONCE = base64_encode(random_bytes(8));

//
// Composer
require __DIR__ . '/../vendor/autoload.php';

/**
 * Kill off the running process and spit out an error message
 * @param string $error error message
 */
function sendError($error) {
    global $SECURE_NONCE;
    die("<!DOCTYPE html>"
            . "<meta charset=\"UTF-8\">"
            . "<meta name=\"viewport\" content=\"width=device-width, initial-scale=1\">"
            . "<title>Error</title>"
            . "<style nonce=\"" . $SECURE_NONCE . "\">"
            . "h1 {color: red; font-family: sans-serif; font-size: 20px; margin-bottom: 0px;} "
            . "h2 {font-family: sans-serif; font-size: 16px;} "
            . "p {font-family: monospace; font-size: 14px; width: 100%; wrap-style: break-word;} "
            . "i {font-size: 12px;}"
            . "</style>"
            . "<h1>A fatal application error has occurred.</h1>"
            . "<i>(This isn't your fault.)</i>"
            . "<h2>Details:</h2>"
            . "<p>" . htmlspecialchars($error) . "</p>");
}

date_default_timezone_set($SETTINGS['timezone']);

// Database settings
// Also inits database and stuff
use Medoo\Medoo;

$database;
try {
    $database = new Medoo([
        'database_type' => $SETTINGS['database']['type'],
        'database_name' => $SETTINGS['database']['name'],
        'server' => $SETTINGS['database']['server'],
        'username' => $SETTINGS['database']['user'],
        'password' => $SETTINGS['database']['password'],
        'charset' => $SETTINGS['database']['charset']
    ]);
} catch (Exception $ex) {
    //header('HTTP/1.1 500 Internal Server Error');
    sendError("Database error.  Try again later.  $ex");
}

function getdatabase() {
    global $database;
    return $database;
}

function getsiteid() {
    global $database;
    if (isset($_GET['siteid'])) {
        $id = preg_replace("/[^0-9]/", '', $_GET['siteid']);
        if ($database->has('sites', ["siteid" => $id])) {
            return $id;
        }
    }
    $host = $_SERVER['HTTP_HOST'];
    $args = $_SERVER['QUERY_STRING'];
    $path = str_replace("?$args", "", $_SERVER['REQUEST_URI']);
    $dir = str_replace("index.php", "", $path);
    $sites = $database->select("sites", ["siteid", "url"], ["OR" => ["url[~]" => $host, "url" => $dir]]);
    //var_dump($sites);
    if (count($sites) == 1) {
        return $sites[0]["siteid"];
    }
    if (count($sites) > 1) {
        //var_dump($sites);
        //die();
        return $sites[0]['siteid'];
    }
    return $database->get("sites", "siteid");
}

function getpageslug() {
    global $database;
    if (isset($_GET['id'])) {
        $id = $_GET['id'];
    } else {
        $id = "index";
    }
    if ($database->has("pages", ["AND" => ["slug" => $id, "siteid" => getsiteid()]])) {
        return $id;
    }
    return null;
}

function getpageid() {
    global $database;
    if (isset($_GET['id'])) {
        $id = $_GET['id'];
    } else {
        $id = "index";
    }
    $siteid = getsiteid();
    if ($database->has("pages", ["AND" => ["slug" => $id, "siteid" => $siteid]])) {
        return $database->get("pages", "pageid", ["AND" => ["slug" => $id, "siteid" => $siteid]]);
    }
    return null;
}

function getpagetemplate() {
    global $database;
    $slug = getpageslug();
    if (isset($_GET['template'])) {
        return preg_replace("/[^A-Za-z0-9]/", '', $_GET['template']);
    }
    if (!is_null($slug)) {
        return $database->get("pages", "template", ["AND" => ["slug" => $slug, "siteid" => getsiteid()]]);
    }
    return "404";
}

function formatsiteurl($url) {
    if (substr($url, 0) != "/") {
        if (strpos($url, "http://") !== 0 && strpos($url, "https://") !== 0) {
            if (empty($_SERVER['HTTPS']) || $_SERVER['HTTPS'] == "off") {
                $url = "http://$url";
            } else {
                $url = "https://$url";
            }
        }
    }
    if (substr($url, -1) != "/") {
        $url = $url . "/";
    }
    return $url;
}
