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
?>

<?php
if ($enableunsplash) {
    ?>
    <ul class="nav nav-tabs" id="fileBrowserTabs" role="tablist">
        <li class="nav-item">
            <a class="nav-link active" id="uploadedFilesTabBtn" data-toggle="tab" href="#uploadedFilesTab">
                <i class="fas fa-folder-open"></i> <?php lang('uploaded files'); ?>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" id="unsplashTabBtn" data-toggle="tab" href="#unsplashTab">
                <i class="fas fa-image"></i> <?php lang('stock photos'); ?>
            </a>
        </li>
    </ul>
    <?php
}
?>
<div class="tab-content" id="fileBrowserTabContent">
    <div class="tab-pane fade show active" id="uploadedFilesTab" role="tabpanel" aria-labelledby="uploadedFilesTabBtn">
        <div class="card" id="uploadedFilesBin">
        </div>
    </div>

    <?php
    if ($enableunsplash) {
        ?>
        <div class="tab-pane fade" id="unsplashTab" role="tabpanel" aria-labelledby="unsplashTabBtn">
            <div class="card">
                <div class="card-body">
                    <div class="input-group">
                        <input type="text" class="form-control" id="unsplashSearch" placeholder="<?php lang("search images"); ?>" />
                        <div class="input-group-append">
                            <div class="btn btn-primary" id="unsplashSearchBtn">
                                <i class="fas fa-search"></i> <?php lang("search"); ?>
                            </div>
                        </div>
                    </div>
                    <span id="unsplashResults"></span> <span>via <a href="https://unsplash.com/?utm_source=<?php echo urlencode(UNSPLASH_UTMSOURCE); ?>&utm_medium=referral">Unsplash</a></span>
                </div>
                <div id="unsplashPhotoBin" class="px-2 pr-3">
                </div>
                <div class="card-body">
                    <button type="button" class="btn btn-primary btn-block" id="unsplashLoadMoreBtn">
                        <?php lang("load more"); ?>
                    </button>
                </div>
            </div>
        </div>
        <?php
    }
    ?>
</div>