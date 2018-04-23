<?php
/* This Source Code Form is subject to the terms of the Mozilla Public
 * License, v. 2.0. If a copy of the MPL was not distributed with this
 * file, You can obtain one at http://mozilla.org/MPL/2.0/. */

require_once __DIR__ . '/../required.php';

redirectifnotloggedin();

if (!is_empty($VARS['siteid'])) {
    if ($database->has('sites', ['siteid' => $VARS['siteid']])) {
        $sitedata = $database->get(
                'sites', [
            'siteid',
            'sitename',
            'url',
            'theme',
            'color'
                ], [
            'siteid' => $VARS['siteid']
        ]);
        $pagedata = $database->select(
                'pages', [
            "pageid",
            "slug",
            "title",
            "template"
                ], ["siteid" => $VARS['siteid']]
        );
        $slug = "index";
        if (isset($VARS['slug']) && $database->has('pages', ["AND" => ['slug' => $VARS['slug'], 'siteid' => $VARS['siteid']]])) {
            $slug = $VARS['slug'];
        }
    } else {
        header('Location: app.php?page=sites');
        die();
    }
} else {
    header('Location: app.php?page=sites');
    die();
}
?>
<div class="row mb-2">
    <div class="col-12 col-sm-6 col-md-4">
        <div class="btn btn-success" id="savebtn">
            <i class="fas fa-save"></i> <?php lang("save"); ?>
        </div>
    </div>
    <form method="GET" action="app.php" class="col-12 col-sm-6 col-md-4">
        <input type="hidden" name="page" value="editor" />
        <input type="hidden" name="siteid" value="<?php echo $VARS['siteid']; ?>" />
        <div class="input-group">
            <select name="slug" class="form-control">
                <?php
                foreach ($pagedata as $p) {
                    $selected = "";
                    if ($slug == $p['slug']) {
                        $selected = " selected";
                    }
                    echo "<option value=\"" . $p['slug'] . "\"$selected>" . $p['title'] . ' (' . $p['slug'] . ')' . "</option>\n";
                }
                ?>
            </select>
            <div class="input-group-append">
                <button type="submit" class="btn btn-primary"><?php lang("edit"); ?></button>
            </div>
        </div>
    </form>
</div>

<iframe id="editorframe" src="public/index.php?id=<?php echo $slug; ?>&edit"></iframe>