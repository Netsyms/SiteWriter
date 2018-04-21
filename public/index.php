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

$theme = $database->get("sites", "theme", ["siteid" => getsiteid()]);

$template = getpagetemplate();
include __DIR__ . "/themes/$theme/$template.php";

if (isset($_GET['edit'])) {
    ?>
    <link href="<?php echo URL; ?>/static/css/summernote-lite.css" rel="stylesheet" />
    <script src="<?php echo URL; ?>/static/js/summernote-lite.js"></script>
    <script src="<?php echo URL; ?>/static/js/editor.js"></script>
    <script>
        var save_url = "<?php echo URL; ?>/action.php";
        var page_slug = "<?php getpageslug(); ?>";
    </script>
    <?php
}
?>