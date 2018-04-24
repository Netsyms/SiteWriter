<?php
/*
 * This Source Code Form is subject to the terms of the Mozilla Public
 * License, v. 2.0. If a copy of the MPL was not distributed with this
 * file, You can obtain one at http://mozilla.org/MPL/2.0/.
 */

require_once __DIR__ . "/../lib/requiredpublic.php";
require_once __DIR__ . "/../lib/themefunctions.php";

if (!getsiteid()) {
    sendError("No website has been created yet.  Please open " . SITE_TITLE . " and make one.");
}

if (isset($_GET['theme']) && file_exists(__DIR__ . "/themes/" . preg_replace("/[^A-Za-z0-9]/", '', $_GET['theme']) . "/theme.json")) {
    $theme = preg_replace("/[^A-Za-z0-9]/", '', $_GET['theme']);
} else {
    $theme = $database->get("sites", "theme", ["siteid" => getsiteid()]);
}

define("SITE_THEME", $theme);

$template = getpagetemplate();
if (file_exists(__DIR__ . "/themes/$theme/$template.php")) {
    include __DIR__ . "/themes/$theme/$template.php";
} else {
    include __DIR__ . "/themes/$theme/default.php";
}

if (isset($_GET['edit'])) {
    ?>
    <style><?php echo str_replace("./font/summernote", "../static/fonts/summernote", file_get_contents(__DIR__ . "/../static/css/summernote-lite.css")); ?></style>
    <script src="<?php echo URL; ?>/static/js/jquery-3.3.1.min.js"></script>
    <script src="<?php echo URL; ?>/static/js/summernote-lite.js"></script>
    <script>
        static_dir = "<?php echo URL; ?>/static";
        page_slug = "<?php echo getpageslug(); ?>";
        site_id = "<?php echo getsiteid(); ?>";
    </script>
    <script src="<?php echo URL; ?>/static/js/editor.js"></script>
    <?php
}
?>