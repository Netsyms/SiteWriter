<?php

/*
 * This Source Code Form is subject to the terms of the Mozilla Public
 * License, v. 2.0. If a copy of the MPL was not distributed with this
 * file, You can obtain one at http://mozilla.org/MPL/2.0/.
 */

require_once __DIR__ . "/../lib/requiredpublic.php";

$base = FILE_UPLOAD_PATH;

$filepath = "";

if (isset($_GET['file'])) {
    $file = $_GET['file'];
    $filepath = $base . $file;
    if (!file_exists($filepath) || is_dir($filepath)) {
        http_response_code(404);
        die("404 File Not Found");
    }
    if (strpos(realpath($filepath), FILE_UPLOAD_PATH) !== 0) {
        http_response_code(404);
        die("404 File Not Found");
    }
} else {
    http_response_code(404);
    die("404 File Not Found");
}

include_once __DIR__ . "/../lib/mimetypes.php";

$extension = pathinfo($filepath)['extension'];
// If we don't have an extension, try using the whole filename
if ($extension == "") {
    $extension = $f;
}
$mimetype = "application/octet-stream";
// Lookup mimetype from extension
if (array_key_exists($extension, $EXT2MIME)) {
    $mimetype = $EXT2MIME[$extension];
}

header("Content-Type: $mimetype");
header('Content-Length: ' . filesize($filepath));
header("X-Content-Type-Options: nosniff");

ob_end_flush();

readfile($filepath);