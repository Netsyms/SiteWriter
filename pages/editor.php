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

<div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editLabel"><?php lang("edit component"); ?></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="editModalBody">
                <div class="form-group d-none" id="iconEdit">
                    <label><i class="fas fa-paint-brush"></i> <?php lang("icon"); ?></label>

                </div>
                <div class="form-group" id="linkEdit">
                    <label><i class="fas fa-link"></i> <?php lang("link"); ?></label>
                    <select id="linkPage" class="form-control">
                        <option value=""><?php lang("select page or enter url"); ?></option>
                        <?php
                        foreach ($pagedata as $p) {
                            echo "<option value=\"" . $p['slug'] . "\">" . $p['title'] . ' (' . $p['slug'] . ')' . "</option>\n";
                        }
                        ?>
                    </select>
                    <input type="text" id="linkBox" class="form-control" placeholder="http://example.com" />
                </div>
                <div class="form-group" id="textEdit">
                    <label><i class="fas fa-font"></i> <?php lang("text"); ?></label>
                    <input type="text" id="textBox" class="form-control" placeholder="Edit me" />
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal"><?php lang("cancel"); ?></button>
                <button type="button" class="btn btn-success" id="editModalSave"><i class="fas fa-save"></i> <?php lang("save"); ?></button>
            </div>
        </div>
    </div>
</div>

<div class="row mb-2 justify-content-between">
    <div class="col-12 col-sm-6 col-md-4">
        <div class="btn-group">
            <div class="btn btn-success" id="savebtn">
                <i class="fas fa-save"></i> <?php lang("save"); ?>
            </div>
            <a class="btn btn-info" id="viewbtn" target="_BLANK" href="public/index.php?id=<?php echo $slug; ?>&siteid=<?php echo $VARS['siteid']; ?>">
                <i class="fas fa-eye"></i> <?php lang("view"); ?>
            </a>
        </div>
        <span class="badge badge-success d-none" id="savedBadge"><i class="fas fa-check"></i> <?php lang("saved"); ?></span>
        <div id="reloadprompt" class="badge badge-info d-none">
            <i class="fas fa-sync-alt"></i>
            <?php lang("save needed"); ?>
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

<iframe id="editorframe" src="public/index.php?id=<?php echo $slug; ?>&edit&siteid=<?php echo $VARS['siteid']; ?>"></iframe>