<?php
/*
 * This Source Code Form is subject to the terms of the Mozilla Public
 * License, v. 2.0. If a copy of the MPL was not distributed with this
 * file, You can obtain one at http://mozilla.org/MPL/2.0/.
 */

require_once __DIR__ . "/../lib/requiredpublic.php";
require_once __DIR__ . "/../lib/themefunctions.php";

include __DIR__ . "/../lib/gatheranalytics.php";

if (!getsiteid()) {
    ?>
    <!DOCTYPE html>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Welcome!</title>
    <style><?php echo file_get_contents(__DIR__ . "/../static/css/bootstrap.min.css"); ?></style>
    <div class="container d-flex justify-content-center pt-4">
        <div class="card mt-4">
            <div class="card-body text-center">
                <h2 class="card-title">Welcome!</h2>
                <p>You're seeing this message because no website has been created yet.
                    <br />
                    Open <?php echo SITE_TITLE; ?> to make one.</p>
                <p><a href="<?php echo PORTAL_URL; ?>" class="btn btn-primary">Log In</a></p>
            </div>
        </div>
    </div>
    <?php
    die();
}

if (isset($_GET['theme']) && file_exists(__DIR__ . "/themes/" . preg_replace("/[^A-Za-z0-9]/", '', $_GET['theme']) . "/theme.json")) {
    $theme = preg_replace("/[^A-Za-z0-9]/", '', $_GET['theme']);
} else {
    $theme = $database->get("sites", "theme", ["siteid" => getsiteid()]);
    if (!file_exists(__DIR__ . "/themes/$theme/theme.json")) {
        $theme = "bootstrap";
    }
}

define("SITE_THEME", $theme);

$template = getpagetemplate();
if (file_exists(__DIR__ . "/themes/$theme/$template.php")) {
    include __DIR__ . "/themes/$theme/$template.php";
} else {
    include __DIR__ . "/themes/$theme/default.php";
}

if (isset($_GET['edit'])) {
    $allpages = $database->select("pages", ["slug (value)", "title"], ["siteid" => getsiteid()]);
    for ($i = 0; $i < count($allpages); $i++) {
        $allpages[$i]["value"] = get_page_clean_url(false, $allpages[$i]["value"]);
    }
    ?>
    <style><?php echo file_get_contents(__DIR__ . "/../static/css/editor.css"); ?></style>
    <script src="<?php echo URL; ?>/static/js/jquery-3.3.1.min.js"></script>
    <script src="<?php echo URL; ?>/static/js/tinymce/tinymce.min.js"></script>
    <script>
        static_dir = "<?php echo URL; ?>/static";
        page_slug = "<?php echo getpageslug(); ?>";
        site_id = "<?php echo getsiteid(); ?>";
        pages_list = <?php echo json_encode($allpages); ?>;
    </script>
    <script src="<?php echo URL; ?>/static/js/editor.js"></script>
    <?php
}
?>