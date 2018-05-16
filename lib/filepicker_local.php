<?php
/* This Source Code Form is subject to the terms of the Mozilla Public
 * License, v. 2.0. If a copy of the MPL was not distributed with this
 * file, You can obtain one at http://mozilla.org/MPL/2.0/. */

require_once __DIR__ . '/../required.php';

dieifnotloggedin();

include_once __DIR__ . "/../lib/mimetypes.php";

$base = FILE_UPLOAD_PATH;

$folder = "";
if (isset($VARS['path']) && file_exists($base . $VARS['path']) && strpos(realpath($base . $VARS['path']), FILE_UPLOAD_PATH) === 0) {
    $folder = $VARS['path'];
}

// Compared to the start of the file mimetype, if it doesn't match the file is
// skipped.  A type of "image" will match "image/png", "image/jpeg", etc.
$type = [];
if (isset($VARS['type']) && $VARS['type'] != "") {
    $type = explode("|", $VARS['type']);
}

$enableunsplash = ENABLE_UNSPLASH;
if (count($type) > 0 && !in_array("image", $type)) {
    $enableunsplash = false;
}

if ($folder == "/") {
    $folder = "";
}

$fullpath = $base . $folder;
?>
<div class="mb-2">
    <nav aria-label="breadcrumb" class="my-auto">
        <ol class="breadcrumb m-0">
            <?php
            $pathparts = explode("/", "$folder");
            $pstr = "";
            for ($i = 0; $i < count($pathparts); $i++) {
                $p = $pathparts[$i];
                $pstr .= "/$p";
                $pstr = "/" . ltrim($pstr, "/");
                if ($i == 0) {
                    $p = "<span class=\"fas fa-home\"></span>";
                }
                if ($i == count($pathparts) - 1) {
                    echo "\t<li aria-current=\"page\" class=\"breadcrumb-item active\">$p</li>";
                } else {
                    echo "\t<li class=\"breadcrumb-item\"><span class=\"filepicker-item\" data-type=\"dir\" data-path=\"$pstr\">$p</a></li>";
                }
                echo "\n";
            }
            ?>
        </ol>
    </nav>
</div>
<div class="list-group list-group-flush">
    <?php
    $files = scandir($fullpath);
    $count = 0;
    foreach ($files as $f) {
        if (strpos($f, '.') !== 0) {
            $count++;
            $link = "$folder/$f";
            $target = "_BLANK";
            $isdir = false;
            $icon = "fas fa-file";
            if (is_dir($fullpath . "/" . $f)) {
                $isdir = true;
                $link = "app.php?page=files&path=$folder/$f";
                $icon = "fas fa-folder";
                $target = "";
            } else {
                $link = "public/file.php?file=$folder/$f";
                $extension = pathinfo($fullpath . "/" . $f)['extension'];
                // If we don't have an extension, try using the whole filename
                if ($extension == "") {
                    $extension = $f;
                }
                $mimetype = "application/octet-stream";
                // Lookup mimetype from extension
                if (array_key_exists($extension, $EXT2MIME)) {
                    $mimetype = $EXT2MIME[$extension];
                }

                $found = true;
                if (count($type) > 0) {
                    $found = false;
                    foreach ($type as $t) {
                        if (strpos($mimetype, $t) === 0) {
                            $found = true;
                            break;
                        }
                    }
                }
                if (!$found) {
                    continue;
                }

                // Lookup icon from mimetype
                if (array_key_exists($mimetype, $MIMEICONS)) {
                    $icon = $MIMEICONS[$mimetype];
                } else { // Allow broad generic <format>/other icons
                    $mimefirst = explode("/", $mimetype, 2)[0];
                    if (array_key_exists($mimefirst . "/other", $MIMEICONS)) {
                        $icon = $MIMEICONS[$mimetype];
                    }
                }
            }
            ?>
            <div
                class="list-group-item filepicker-item"
                data-type="<?php echo $isdir ? "dir" : "file" ?>"
                data-path="<?php echo "$folder/$f"; ?>"
                data-file="<?php echo $f; ?>">
                <span class="<?php echo $icon; ?> fa-fw"></span> <?php echo $f; ?>
            </div>
            <?php
        }
    }
    if ($count == 0) {
        ?>
        <div class="list-group-item text-center">
            <p class="text-muted">
                <i class="far fa-folder-open fa-5x fa-fw"></i>
            </p>
            <p class="h5 text-muted">
                <?php lang("nothing here"); ?>
            </p>
        </div>
        <?php
    }
    ?>
</div>