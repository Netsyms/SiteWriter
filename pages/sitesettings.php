<?php
/* This Source Code Form is subject to the terms of the Mozilla Public
 * License, v. 2.0. If a copy of the MPL was not distributed with this
 * file, You can obtain one at http://mozilla.org/MPL/2.0/. */

require_once __DIR__ . '/../required.php';

redirectifnotloggedin();

if (!is_empty($VARS['siteid'])) {
    if ($database->has('sites', ['siteid' => $VARS['siteid']])) {
        $sitedata = $database->select(
                        'sites', [
                    'siteid',
                    'sitename',
                    'url',
                    'theme',
                    'color'
                        ], [
                    'siteid' => $VARS['siteid']
                ])[0];
    } else {
        header('Location: app.php?page=sites');
    }
} else {
    header('Location: app.php?page=sites');
}
?>

<form role="form" action="action.php" method="POST">
    <div class="card border-light-blue">
        <h3 class="card-header text-light-blue">
            <i class="fas fa-edit"></i> <?php lang2("editing site", ['site' => "<span id=\"name_title\">" . htmlspecialchars($sitedata['sitename']) . "</span>"]); ?>
        </h3>
        <div class="card-body">
            <div class="form-group">
                <label for="name"><i class="fas fa-font"></i> <?php lang("name"); ?></label>
                <input type="text" class="form-control" id="name" name="name" placeholder="Foo Bar" required="required" value="<?php echo htmlspecialchars($sitedata['sitename']); ?>" />
            </div>
            <div class="form-group">
                <label for="url"><i class="fas fa-globe"></i> <?php lang("url"); ?></label>
                <input type="text" class="form-control" id="url" name="url" placeholder="https://example.com" required="required" value="<?php echo htmlspecialchars($sitedata['url']); ?>" />
            </div>
            <div class="row">
                <div class="col-12">
                    <div class="form-group">
                        <label for="theme"><i class="fas fa-paint-brush"></i> <?php lang('theme'); ?></label>
                        <div class="theme_bin">
                            <?php
                            $themedir = __DIR__ . "/../public/themes/";
                            $themes = array_diff(scandir($themedir), array('..', '.'));
                            foreach ($themes as $t) {
                                if (!file_exists($themedir . "$t/theme.json")) {
                                    continue;
                                }
                                $info = json_decode(file_get_contents($themedir . "$t/theme.json"), TRUE);
                                $ts = $sitedata["theme"] == $t ? " checked" : "";
                                ?>
                                <label>
                                    <input type="radio" name="theme" value="<?php echo $t; ?>" <?php echo $ts; ?> />
                                    <div class="card theme">
                                        <div class="card-body m-0 p-1">
                                            <span class="d-flex">
                                                <h4 class="mr-auto"><?php echo $info['name']; ?></h4>
                                                <a href="public/index.php?page=index&siteid=<?php echo $VARS['siteid']; ?>&theme=<?php echo $t; ?>" target="_BLANK">
                                                    <i class="fas fa-eye"></i> <?php lang("preview"); ?>
                                                </a>
                                            </span>
                                            <b><?php lang("theme type"); ?></b>:
                                            <?php
                                            if ($info['singlepage'] == true) {
                                                lang("single page");
                                            } else {
                                                lang("multiple page");
                                            }
                                            ?><br />
                                            <b><?php lang("templates"); ?></b>:
                                            <?php
                                            $temtitles = [];
                                            foreach ($info['templates'] as $tem) {
                                                $temtitles[] = $tem['title'];
                                            }
                                            echo implode(", ", $temtitles);
                                            ?><br />
                                            <b><?php lang("color styles"); ?></b>:
                                            <div class="list-group colorSelector">
                                                <?php
                                                if (count($info['colors']) == 0) {
                                                    $info['colors'] = ["default" => ["title" => lang("default", false), "description" => ""]];
                                                }
                                                foreach ($info['colors'] as $c => $color) {
                                                    $checked = "";
                                                    if ($sitedata["theme"] == $t && $sitedata["color"] == $c) {
                                                        $checked = "checked";
                                                    }
                                                    ?>
                                                    <div class="list-group-item">
                                                        <div class="d-flex justify-content-between">
                                                            <div class="form-check form-check-inline">
                                                                <input class="form-check-input" type="radio" name="color" id="color_<?php echo $t; ?>_<?php echo $c; ?>" value="<?php echo $c; ?>" <?php echo $checked; ?>>
                                                                <label class="form-check-label" for="color_<?php echo $t; ?>_<?php echo $c; ?>">
                                                                    <b><?php echo $color["title"]; ?></b>
                                                                </label>
                                                            </div>
                                                            <div>
                                                                <a href="public/index.php?page=index&siteid=<?php echo $VARS['siteid']; ?>&theme=<?php echo $t; ?>&color=<?php echo $c; ?>" target="_BLANK">
                                                                    <i class="fas fa-eye"></i>
                                                                    <?php lang("preview"); ?>
                                                                </a>
                                                            </div>
                                                        </div>
                                                        <?php echo $color["description"]; ?>
                                                    </div>
                                                    <?php
                                                }
                                                ?>
                                            </div>
                                        </div>
                                    </div>
                                </label>
                            <?php } ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <input type="hidden" name="siteid" value="<?php echo $VARS['siteid']; ?>" />
        <input type="hidden" name="action" value="sitesettings" />
        <input type="hidden" name="source" value="sites" />

        <div class="card-footer d-flex">
            <button type="submit" class="btn btn-success mr-auto"><i class="fas fa-save"></i> <?php lang("save"); ?></button>
        </div>
    </div>
</form>