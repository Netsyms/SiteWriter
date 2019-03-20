<?php
/* This Source Code Form is subject to the terms of the Mozilla Public
 * License, v. 2.0. If a copy of the MPL was not distributed with this
 * file, You can obtain one at http://mozilla.org/MPL/2.0/. */

require_once __DIR__ . '/../required.php';

redirectifnotloggedin();

$user = new User($_SESSION['uid']);

if (!$user->hasPermission("SITEWRITER") && !$user->hasPermission("SITEWRITER_FILES") && !$user->hasPermission("SITEWRITER_EDIT")) {
    // Note: the EDIT permission is valid here because content editors can browse files anyways
    if ($_GET['msg'] != "no_permission") {
        header("Location: app.php?page=files&msg=no_permission");
    }
    die();
}

include_once __DIR__ . "/../lib/mimetypes.php";

$base = $SETTINGS["file_upload_path"];

$folder = "";
if (isset($VARS['path']) && file_exists($base . $VARS['path']) && strpos(realpath($base . $VARS['path']), $SETTINGS["file_upload_path"]) === 0) {
    $folder = $VARS['path'];
}

if ($folder == "/") {
    $folder = "";
}

$fullpath = $base . $folder;
?>

<div class="card">
    <div class="card-header d-flex justify-content-between">
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
                        echo "\t<li class=\"breadcrumb-item\"><a href=\"app.php?page=files&path=$pstr\">$p</a></li>";
                    }
                    echo "\n";
                }
                ?>
            </ol>
        </nav>

        <div class="ml-auto my-auto">
            <form action="action.php" method="POST" enctype="multipart/form-data">
                <div class="input-group input-group-sm">
                    <input type="text" id="uploadstatus" class="form-control" readonly />
                    <div class="input-group-append">
                        <span class="btn btn-primary btn-file">
                            <i class="fas fa-folder-open"></i> <?php $Strings->get("browse"); ?> <input id="fileupload" type="file" name="files[]" multiple required />
                        </span>
                        <button class="btn btn-success" type="submit"><i class="fas fa-cloud-upload-alt"></i> <?php $Strings->get("upload"); ?></button>
                    </div>
                </div>
                <input type="hidden" name="action" value="fileupload" />
                <input type="hidden" name="source" value="files" />
                <input type="hidden" name="path" value="<?php echo $folder; ?>" />
            </form>
            <form action="action.php" method="POST" class="mt-1">
                <div class="input-group input-group-sm">
                    <input type="text" class="form-control" name="folder" required />
                    <div class="input-group-append">
                        <button class="btn btn-success" type="submit"><i class="fas fa-folder"></i> <?php $Strings->get("new folder"); ?></button>
                    </div>
                </div>
                <input type="hidden" name="action" value="newfolder" />
                <input type="hidden" name="source" value="files" />
                <input type="hidden" name="path" value="<?php echo $folder; ?>" />
            </form>
        </div>
    </div>
    <div class="card-body">
        <div class="list-group">
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
                        // Lookup icon from mimetype
                        if (array_key_exists($mimetype, $MIMEICONS)) {
                            $icon = $MIMEICONS[$mimetype];
                        } else { // Allow broad generic <format>/other icons
                            $mimefirst = explode("/", $mimetype, 2)[0];
                            if (array_key_exists($mimefirst . "/other", $MIMEICONS)) {
                                $icon = $MIMEICONS[$mimefirst . "/other"];
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
                            <button type="submit" class="btn btn-outline-danger btn-sm"><i class="fas fa-trash"></i> <?php $Strings->get("delete"); ?></button>
                        </form>
                    </div>
                    <?php
                }
            }
            if ($count == 0) {
                ?>
                <div class="list-group-item text-center">
                    <p class="text-muted">
                        <i class="far fa-folder-open fa-10x fa-fw"></i>
                    </p>
                    <p class="h4 text-muted">
                        <?php $Strings->get("nothing here"); ?>
                    </p>
                </div>
                <?php
            }
            ?>
        </div>
    </div>
</div>