<?php
/* This Source Code Form is subject to the terms of the Mozilla Public
 * License, v. 2.0. If a copy of the MPL was not distributed with this
 * file, You can obtain one at http://mozilla.org/MPL/2.0/. */

require_once __DIR__ . '/../required.php';

redirectifnotloggedin();

$user = new User($_SESSION['uid']);

if (!$user->hasPermission("SITEWRITER") && !$user->hasPermission("SITEWRITER_EDIT")) {
    if ($_GET['msg'] != "no_permission") {
        header("Location: app.php?page=editor&msg=no_permission");
    }
    die();
}

if (!empty($VARS['arg'])) {
    // Allow action.php to do a better redirect
    $VARS['siteid'] = $VARS['arg'];
    if (strpos($VARS['arg'], "|") !== FALSE) {
        $arg = explode("|", $VARS['arg'], 2);
        $VARS['siteid'] = $arg[0];
        if ($database->has("pages", ["AND" => ["siteid" => $VARS['siteid'], "pageid" => $arg[1]]])) {
            $VARS['slug'] = $database->get("pages", "slug", ["AND" => ["siteid" => $VARS['siteid'], "pageid" => $arg[1]]]);
        }
    }
}

if (!empty($VARS['siteid'])) {
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
            "parentid",
            "nav",
            "template",
            "navorder"
                ], ["siteid" => $VARS['siteid'], "ORDER" => ["navorder"]]
        );
        $slug = "index";
        if (isset($VARS['slug']) && $database->has('pages', ["AND" => ['slug' => $VARS['slug'], 'siteid' => $VARS['siteid']]])) {
            $slug = $VARS['slug'];
        }
        $thispage = $database->get(
                'pages', [
            "pageid",
            "slug",
            "title",
            "parentid",
            "nav",
            "template",
            "navorder"
                ], ["AND" => ["siteid" => $VARS['siteid'], "slug" => $slug]]
        );

        $singlepage = false;
        $themedata = json_decode(file_get_contents(__DIR__ . "/../public/themes/" . $sitedata["theme"] . "/theme.json"), true);

        if ($themedata["singlepage"] === true) {
            $singlepage = true;
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

<div class="modal fade" id="fileBrowseModal" tabindex="-1" role="dialog" aria-labelledby="fileBrowseLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="fileBrowseLabel"><i class="far fa-folder-open"></i> <?php $Strings->get("browse"); ?></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="fileBrowseModalBody">
                <i class="fas fa-spin fa-circle-notch"></i> <?php $Strings->get("loading"); ?>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal"><?php $Strings->get("cancel"); ?></button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="pageSettingsModal" tabindex="-1" role="dialog" aria-labelledby="pageSettingsLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <form class="modal-content" action="action.php" method="POST">
            <div class="modal-header">
                <h5 class="modal-title" id="pageSettingsLabel"><i class="fas fa-cog"></i> <?php $Strings->get("page settings"); ?></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="pageSettingsModalBody">
                <div class="form-group">
                    <label for="pageSettingsTitle"><i class="fas fa-font"></i> <?php $Strings->get("title"); ?></label>
                    <input type="text" id="pageSettingsTitle" name="title" class="form-control" required="required" minlength="1" maxlength="200" value="<?php echo $thispage['title']; ?>" />
                </div>
                <div class="form-group">
                    <label><i class="fas fa-paint-brush"></i> <?php $Strings->get("template"); ?></label>
                    <select id="pageSettingsTemplate" name="template" class="form-control" required="required">
                        <?php
                        $json = file_get_contents(__DIR__ . "/../public/themes/" . $sitedata['theme'] . "/theme.json");
                        $templates = json_decode($json, true)["templates"];
                        foreach ($templates as $name => $value) {
                            $selected = "";
                            if ($thispage['template'] == $name) {
                                $selected = " selected";
                            }
                            echo "<option value=\"" . $name . "\"$selected>" . $value['title'] . "</option>\n";
                        }
                        ?>
                    </select>
                </div>
                <?php
                if ($singlepage !== true) {
                    ?>
                    <div class="form-group">
                        <label><i class="fas fa-bars"></i> <?php $Strings->get("navbar options"); ?></label>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="innavbar" id="innavbarCheckbox" value="1" <?php echo (empty($thispage['nav']) ? "" : "checked") ?> />
                            <label class="form-check-label" for="innavbarCheckbox">
                                <?php $Strings->get("in navbar"); ?>
                            </label>
                        </div>
                        <div id="navbarSettings" class="card p-2 mt-3 d-none">
                            <script nonce="<?php echo $SECURE_NONCE; ?>">
                                var pageid = <?php echo $thispage['pageid'] ?>;
                                var pagetitle = "<?php echo $thispage['title'] ?>";
                            </script>
                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                    <label class="input-group-text" for="navbarTitle"><?php $Strings->get("navbar title"); ?></label>
                                </div>
                                <input type="text" id="navbarTitle" name="navbartitle" class="form-control" required="required" minlength="1" maxlength="200" value="<?php echo (empty($thispage['nav']) ? $thispage['title'] : $thispage['nav']); ?>" />
                            </div>

                            <label class="mb-0 pb-0"><?php $Strings->get("navbar position"); ?></label>
                            <div class="list-group py-3" id="navbar-order-list">
                                <?php
                                foreach ($pagedata as $page) {
                                    if (empty($page['nav'])) {
                                        continue;
                                    }
                                    ?>
                                    <div
                                        class="list-group-item"
                                        data-pageid="<?php echo $page['pageid']; ?>">
                                        <i class="fas fa-sort"></i> <?php echo $page['title']; ?>
                                    </div>
                                    <?php
                                }
                                ?>
                            </div>
                            <input type="hidden" name="navorder" value="" />
                        </div>
                    </div>
                    <?php
                }
                ?>
            </div>
            <div class="modal-footer">
                <input type="hidden" name="siteid" value="<?php echo $sitedata['siteid']; ?>" />
                <input type="hidden" name="pageid" value="<?php echo $thispage['pageid']; ?>" />
                <input type="hidden" name="action" value="pagesettings" />
                <input type="hidden" name="source" value="editor" />
                <button type="button" class="btn btn-secondary" data-dismiss="modal"><?php $Strings->get("cancel"); ?></button>
                <button type="submit" class="btn btn-success" id="pageSettingsModalSave"><i class="fas fa-save"></i> <?php $Strings->get("save"); ?></button>
            </div>
        </form>
    </div>
</div>

<div class="modal fade" id="newPageModal" tabindex="-1" role="dialog" aria-labelledby="newPageLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <form class="modal-content" action="action.php" method="POST">
            <div class="modal-header">
                <h5 class="modal-title" id="newPageLabel"><i class="fas fa-plus"></i> <?php $Strings->get("new page"); ?></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="newPageModalBody">
                <div class="form-group">
                    <label><i class="fas fa-font"></i> <?php $Strings->get("title"); ?></label>
                    <input type="text" id="newPageTitle" name="title" class="form-control" required="required" minlength="1" maxlength="200" />
                </div>
                <!--<div class="form-group">
                    <label><i class="fas fa-link"></i> <?php $Strings->get("page id"); ?></label>
                    <input type="text" id="newPageSlug" name="slug" class="form-control" placeholder="" minlength="1" maxlength="200" />
                </div>-->
                <div class="form-group">
                    <label><i class="fas fa-paint-brush"></i> <?php $Strings->get("template"); ?></label>
                    <select id="newPageTemplate" name="template" class="form-control" required="required">
                        <?php
                        $json = file_get_contents(__DIR__ . "/../public/themes/" . $sitedata['theme'] . "/theme.json");
                        $templates = json_decode($json, true)["templates"];
                        foreach ($templates as $name => $value) {
                            echo "<option value=\"" . $name . "\">" . $value['title'] . "</option>\n";
                        }
                        ?>
                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <input type="hidden" name="siteid" value="<?php echo $sitedata['siteid']; ?>" />
                <input type="hidden" name="action" value="newpage" />
                <input type="hidden" name="source" value="editor" />
                <button type="button" class="btn btn-secondary" data-dismiss="modal"><?php $Strings->get("cancel"); ?></button>
                <button type="submit" class="btn btn-success" id="newPageModalSave"><i class="fas fa-plus"></i> <?php $Strings->get("add page"); ?></button>
            </div>
        </form>
    </div>
</div>

<div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editLabel"><?php $Strings->get("edit component"); ?></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="editModalBody">
                <div class="form-group d-none" id="iconEdit">
                    <label><i class="fas fa-paint-brush"></i> <?php $Strings->get("icon"); ?></label>
                    <br /> <div class="card d-inline-block mb-2">
                        <div class="card-body p-1">
                            <?php $Strings->get("current"); ?>: <span id="selectedicon"><i class="fa-fw"></i></span>
                        </div>
                    </div>
                    <div id="iconpicker"><i class="fas fa-spin fa-circle-notch ml-2"></i> <?php $Strings->get("loading"); ?></div>
                </div>
                <div class="form-group d-none" id="imageEdit" data-image="">
                    <label><i class="fas fa-image"></i> <?php $Strings->get("image"); ?></label>
                    <br /> <div class="card d-inline-block mb-2">
                        <div class="card-body p-1">
                            <img id="selectedimage" class="img-responsive" />
                            <br />
                            <span class="btn btn-sm btn-outline-danger mt-1" id="removeimagebtn">
                                <span class="fas fa-times fa-fw"></span> <?php $Strings->get("remove image"); ?>
                            </span>
                        </div>
                    </div>
                    <div id="imagepicker">
                        <i class="fas fa-spin fa-circle-notch ml-2"></i> <?php $Strings->get("loading"); ?>
                    </div>
                </div>
                <div class="form-group" id="linkEdit">
                    <label><i class="fas fa-link"></i> <?php $Strings->get("link"); ?></label>
                    <select id="linkPage" class="form-control">
                        <option value=""><?php $Strings->get("select page or enter url"); ?></option>
                        <?php
                        foreach ($pagedata as $p) {
                            echo "<option value=\"" . $p['slug'] . "\">" . $p['title'] . ' (' . $p['slug'] . ')' . "</option>\n";
                        }
                        ?>
                    </select>
                    <input type="text" id="linkBox" class="form-control" placeholder="http://example.com" />
                </div>
                <div class="form-group" id="textEdit">
                    <label><i class="fas fa-font"></i> <?php $Strings->get("text"); ?></label>
                    <input type="text" id="textBox" class="form-control" placeholder="Edit me" />
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal"><?php $Strings->get("cancel"); ?></button>
                <button type="button" class="btn btn-success" id="editModalSave"><i class="fas fa-save"></i> <?php $Strings->get("save"); ?></button>
            </div>
        </div>
    </div>
</div>

<div class="row mb-2 justify-content-between">
    <div class="col-12 col-sm-6">
        <div class="btn-group">
            <div class="btn btn-success" id="savebtn">
                <i class="fas fa-save"></i> <?php $Strings->get("save"); ?>
            </div>
            <div class="btn btn-secondary" id="pagesettingsbtn">
                <i class="fas fa-cog"></i> <?php $Strings->get("settings"); ?>
            </div>
            <a class="btn btn-info" id="viewbtn" target="_BLANK" href="public/index.php?id=<?php echo $slug; ?>&siteid=<?php echo $VARS['siteid']; ?>">
                <i class="fas fa-eye"></i> <?php $Strings->get("view"); ?>
            </a>
            <?php if (!$singlepage) { ?>
                <div class="btn btn-primary" id="newpagebtn">
                    <i class="fas fa-plus"></i> <?php $Strings->get("new"); ?>
                </div>
            <?php } ?>
        </div>
        <span class="badge badge-success d-none" id="savedBadge"><i class="fas fa-check"></i> <?php $Strings->get("saved"); ?></span>
        <div id="reloadprompt" class="badge badge-info d-none">
            <i class="fas fa-sync-alt"></i>
            <?php $Strings->get("save needed"); ?>
        </div>
    </div>
    <?php if (!$singlepage) { ?>
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
                    <button type="submit" class="btn btn-primary"><i class="fas fa-edit"></i> <?php $Strings->get("edit"); ?></button>
                </div>
            </div>
        </form>
    <?php } ?>
</div>

<iframe id="editorframe" src="public/index.php?id=<?php echo $slug; ?>&edit&siteid=<?php echo $VARS['siteid']; ?>"></iframe>