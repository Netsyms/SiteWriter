<?php
/* This Source Code Form is subject to the terms of the Mozilla Public
 * License, v. 2.0. If a copy of the MPL was not distributed with this
 * file, You can obtain one at http://mozilla.org/MPL/2.0/. */

require_once __DIR__ . '/../required.php';

redirectifnotloggedin();

include_once __DIR__ . "/../lib/mimetypes.php";

$base = FILE_UPLOAD_PATH;

$folder = "";
if (isset($VARS['path']) && file_exists($base . $VARS['path']) && strpos(realpath($base . $VARS['path']), FILE_UPLOAD_PATH) === 0) {
    $folder = $VARS['path'];
}

if ($folder == "/") {
    $folder = "";
}

$fullpath = $base . $folder;
?>

<div class="card">
    <div class="card-header d-flex justify-content-between">
        <nav aria-label="breadcrumb">
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
                        echo "\t<li class=\"breadcrumb-item\"><a href=\"app.php?page=files&path=$pstr\">$p</a></li>";
                    }
                    echo "\n";
                }
                ?>
            </ol>
        </nav>

        <div class="ml-auto">
            <form action="action.php" method="POST" enctype="multipart/form-data">
                <div class="input-group">
                    <div class="input-group-prepend">
                        <span class="btn btn-primary btn-file">
                            <i class="fas fa-folder-open"></i> <?php lang("browse"); ?> <input id="fileupload" type="file" name="files[]" multiple required />
                        </span>
                    </div>
                    <input type="text" id="uploadstatus" class="form-control" readonly />
                    <div class="input-group-append">
                        <button class="btn btn-success" type="submit"><i class="fas fa-cloud-upload-alt"></i> <?php lang("upload"); ?></button>
                    </div>
                </div>
                <input type="hidden" name="action" value="fileupload" />
                <input type="hidden" name="source" value="files" />
                <input type="hidden" name="path" value="<?php echo $folder; ?>" />
            </form>
        </div>
    </div>
    <div class="card-body">
        <div class="list-group">
            <?php
            $files = scandir($fullpath);
            foreach ($files as $f) {
                if (strpos($f, '.') !== 0) {
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
                    <div class="list-group-item d-flex justify-content-between">
                        <a href="<?php echo $link; ?>" target="<?php echo $target; ?>">
                            <span class="<?php echo $icon; ?> fa-fw"></span> <?php echo $f; ?>
                        </a>
                        <form action="action.php" method="POST">
                            <input type="hidden" name="action" value="filedelete" />
                            <input type="hidden" name="source" value="files" />
                            <input type="hidden" name="file" value="<?php echo "$folder/$f"; ?>" />
                            <button type="submit" class="btn btn-outline-danger btn-sm"><i class="fas fa-trash"></i> <?php lang("delete"); ?></button>
                        </form>
                    </div>
                    <?php
                }
            }
            ?>
        </div>
    </div>
</div>