<?php
/* This Source Code Form is subject to the terms of the Mozilla Public
 * License, v. 2.0. If a copy of the MPL was not distributed with this
 * file, You can obtain one at http://mozilla.org/MPL/2.0/. */

require_once __DIR__ . '/../required.php';

redirectifnotloggedin();

$editing = true;

$siteid = "";
$sitedata = [];
$settings = [];

if (!is_empty($VARS['siteid'])) {
    if ($database->has('sites', ['siteid' => $VARS['siteid']])) {
        $siteid = $VARS['siteid'];
        $sitedata = $database->select(
                        'sites', [
                    'siteid',
                    'sitename',
                    'url',
                    'theme',
                    'color'
                        ], [
                    'siteid' => $siteid
                ])[0];
        $dbsett = $database->select(
                'settings', [
            'key',
            'value'
                ], [
            'siteid' => $siteid
        ]);
        // Format as ["key"=>"value","key"=>"value"], not [["key", "value"],["key", "value"]]
        foreach ($dbsett as $s) {
            $settings[$s['key']] = $s['value'];
        }
    } else {
        header('Location: app.php?page=sites');
        die();
    }
} else {
    $editing = false;
}
?>

<form role="form" action="action.php" method="POST">
    <div class="card border-light-blue">
        <h3 class="card-header text-light-blue">
            <?php
            if ($editing) {
                ?>
                <i class="fas fa-edit"></i> <?php lang2("editing site", ['site' => "<span id=\"name_title\">" . htmlspecialchars($sitedata['sitename']) . "</span>"]); ?>
                <?php
            } else {
                ?>
                <i class="fas fa-plus"></i> <?php lang2("adding site", ['site' => "<span id=\"name_title\">" . htmlspecialchars($sitedata['sitename']) . "</span>"]); ?>
                <?php
            }
            ?>
        </h3>
        <div class="card-body">
            <div class="card mb-4">
                <div class="card-body">
                    <h5 class="card-title"><i class="fas fa-info-circle"></i> <?php lang("site info"); ?></h5>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><label for="name"><i class="fas fa-font"></i> <?php lang("title"); ?></label></span>
                        </div>
                        <input type="text" class="form-control" id="name" name="name" placeholder="Foo Bar" required="required" value="<?php echo htmlspecialchars($sitedata['sitename']); ?>" />
                    </div>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><label for="url"><i class="fas fa-globe"></i> <?php lang("url"); ?></label></span>
                        </div>
                        <input type="text" class="form-control" id="url" name="url" placeholder="https://example.com" required="required" value="<?php echo htmlspecialchars($sitedata['url']); ?>" />
                    </div>
                </div>
            </div>
            <div class="card mb-4">
                <div class="card-body">
                    <div class="form-group">
                        <h5 class="card-title"><label for="theme"><i class="fas fa-paint-brush"></i> <?php lang('theme'); ?></label></h5>
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
                                                <a href="public/index.php?page=index&siteid=<?php echo $siteid; ?>&theme=<?php echo $t; ?>" target="_BLANK">
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
                                                                <a href="public/index.php?page=index&siteid=<?php echo $siteid; ?>&theme=<?php echo $t; ?>&color=<?php echo $c; ?>" target="_BLANK">
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

            <div class="row">
                <div class="col-12 col-md-6">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title"><i class="fas fa-briefcase"></i> <?php lang("company info"); ?></h5>

                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><label for="businessname"><i class="fas fa-font"></i> <?php lang("name"); ?></label></span>
                                </div>
                                <input type="text" class="form-control" name="settings[businessname]" id="businessname" value="<?php echo htmlspecialchars($settings["businessname"]); ?>" />
                            </div>

                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><label for="phone"><i class="fas fa-phone"></i> <?php lang("phone"); ?></label></span>
                                </div>
                                <input type="text" class="form-control" name="settings[phone]" id="phone" value="<?php echo htmlspecialchars($settings["phone"]); ?>" />
                            </div>

                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><label for="address"><i class="fas fa-map-marker"></i> <?php lang("address"); ?></label></span>
                                </div>
                                <textarea class="form-control" name="settings[address]" id="address" rows="2"><?php echo htmlspecialchars($settings["address"]); ?></textarea>
                            </div>

                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><label for="email"><i class="fas fa-envelope"></i> <?php lang("email"); ?></label></span>
                                </div>
                                <input type="email" class="form-control" name="settings[email]" id="email" value="<?php echo htmlspecialchars($settings["email"]); ?>" />
                            </div>
                        </div>
                    </div>

                    <div class="card mt-4">
                        <div class="card-body">
                            <h5 class="card-title"><i class="fas fa-chart-bar"></i> <?php lang("analytics"); ?></h5>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="settings[analytics]" value="" id="analytics_on" <?php echo ($settings["analytics"] === "off" ? "" : "checked") ?>>
                                <label class="form-check-label" for="analytics_on">
                                    <?php lang("enable built-in analytics"); ?>
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="settings[analytics]" value="off" id="analytics_off" <?php echo ($settings["analytics"] === "off" ? "checked" : "") ?>>
                                <label class="form-check-label" for="analytics_off">
                                    <?php lang("disable built-in analytics"); ?>
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="card mt-4 mb-4">
                        <div class="card-body">
                            <h5 class="card-title"><label for="extracode"><i class="fas fa-code"></i> <?php lang("extra code"); ?></label></h5>
                            <textarea class="form-control" name="settings[extracode]" id="extracode" placeholder="<script></script>" rows="5"><?php echo $settings["extracode"]; ?></textarea>
                        </div>
                    </div>
                </div>

                <div class="col-12 col-md-6">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title"><i class="fas fa-share-square"></i> <?php lang("social links"); ?></h5>

                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><label for="facebook"><i class="fab fa-facebook"></i> Facebook</label></span>
                                </div>
                                <input type="text" class="form-control" name="settings[facebook]" id="facebook" value="<?php echo htmlspecialchars($settings["facebook"]); ?>" />
                            </div>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><label for="twitter"><i class="fab fa-twitter"></i> Twitter</label></span>
                                </div>
                                <input type="text" class="form-control" name="settings[twitter]" id="twitter" value="<?php echo htmlspecialchars($settings["twitter"]); ?>" />
                            </div>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><label for="youtube"><i class="fab fa-youtube"></i> Youtube</label></span>
                                </div>
                                <input type="text" class="form-control" name="settings[youtube]" id="youtube" value="<?php echo htmlspecialchars($settings["youtube"]); ?>" />
                            </div>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><label for="instagram"><i class="fab fa-instagram"></i> Instagram</label></span>
                                </div>
                                <input type="text" class="form-control" name="settings[instagram]" id="instagram" value="<?php echo htmlspecialchars($settings["instagram"]); ?>" />
                            </div>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><label for="snapchat"><i class="fab fa-snapchat"></i> Snapchat</label></span>
                                </div>
                                <input type="text" class="form-control" name="settings[snapchat]" id="snapchat" value="<?php echo htmlspecialchars($settings["snapchat"]); ?>" />
                            </div>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><label for="google-plus"><i class="fab fa-google-plus"></i> Google+</label></span>
                                </div>
                                <input type="text" class="form-control" name="settings[google-plus]" id="google-plus" value="<?php echo htmlspecialchars($settings["google-plus"]); ?>" />
                            </div>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><label for="skype"><i class="fab fa-skype"></i> Skype</label></span>
                                </div>
                                <input type="text" class="form-control" name="settings[skype]" id="skype" value="<?php echo htmlspecialchars($settings["skype"]); ?>" />
                            </div>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><label for="telegram"><i class="fab fa-telegram"></i> Telegram</label></span>
                                </div>
                                <input type="text" class="form-control" name="settings[telegram]" id="telegram" value="<?php echo htmlspecialchars($settings["telegram"]); ?>" />
                            </div>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><label for="vimeo"><i class="fab fa-vimeo"></i> Vimeo</label></span>
                                </div>
                                <input type="text" class="form-control" name="settings[vimeo]" id="vimeo" value="<?php echo htmlspecialchars($settings["vimeo"]); ?>" />
                            </div>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><label for="whatsapp"><i class="fab fa-whatsapp"></i> Whatsapp</label></span>
                                </div>
                                <input type="text" class="form-control" name="settings[whatsapp]" id="whatsapp" value="<?php echo htmlspecialchars($settings["whatsapp"]); ?>" />
                            </div>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><label for="linkedin"><i class="fab fa-linkedin"></i> LinkedIn</label></span>
                                </div>
                                <input type="text" class="form-control" name="settings[linkedin]" id="linkedin" value="<?php echo htmlspecialchars($settings["linkedin"]); ?>" />
                            </div>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><label for="diaspora"><i class="fas fa-asterisk"></i> diaspora*</label></span>
                                </div>
                                <input type="text" class="form-control" name="settings[diaspora]" id="diaspora" value="<?php echo htmlspecialchars($settings["diaspora"]); ?>" />
                            </div>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><label for="mastodon"><i class="fab fa-mastodon"></i> Mastodon</label></span>
                                </div>
                                <input type="text" class="form-control" name="settings[mastodon]" id="mastodon" value="<?php echo htmlspecialchars($settings["mastodon"]); ?>" />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <input type="hidden" name="siteid" value="<?php echo $siteid; ?>" />
        <input type="hidden" name="action" value="sitesettings" />
        <input type="hidden" name="source" value="sites" />

        <div class="card-footer d-flex">
            <button type="submit" class="btn btn-success mr-auto"><i class="fas fa-save"></i> <?php lang("save"); ?></button>
        </div>
    </div>
</form>