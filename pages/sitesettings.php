<?php
/* This Source Code Form is subject to the terms of the Mozilla Public
 * License, v. 2.0. If a copy of the MPL was not distributed with this
 * file, You can obtain one at http://mozilla.org/MPL/2.0/. */

require_once __DIR__ . '/../required.php';

redirectifnotloggedin();

$user = new User($_SESSION['uid']);

if (!$user->hasPermission("SITEWRITER")) {
    if ($_GET['msg'] != "no_permission") {
        header("Location: app.php?page=sitesettings&msg=no_permission");
    }
    die();
}

$editing = true;

$siteid = "";
$sitedata = [
    "siteid" => "",
    "sitename" => "",
    "url" => "",
    "theme" => "",
    "color" => ""
];
$settings = [];

if (!empty($VARS['siteid'])) {
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

function getsetting($name) {
    global $settings;
    if (isset($settings[$name])) {
        return htmlspecialchars($settings[$name]);
    }
    return "";
}
?>

<form role="form" action="action.php" method="POST">
    <div class="card border-light-blue">
        <h3 class="card-header text-light-blue">
            <?php
            if ($editing) {
                ?>
                <i class="fas fa-edit"></i> <?php $Strings->build("editing site", ['site' => "<span id=\"name_title\">" . htmlspecialchars($sitedata['sitename']) . "</span>"]); ?>
                <?php
            } else {
                ?>
                <i class="fas fa-plus"></i> <?php $Strings->build("adding site", ['site' => "<span id=\"name_title\">" . htmlspecialchars($sitedata['sitename']) . "</span>"]); ?>
                <?php
            }
            ?>
        </h3>
        <div class="card-body">
            <div class="card mb-4">
                <div class="card-body">
                    <h5 class="card-title"><i class="fas fa-info-circle"></i> <?php $Strings->get("site info"); ?></h5>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><label for="name"><i class="fas fa-font"></i> <?php $Strings->get("title"); ?></label></span>
                        </div>
                        <input type="text" class="form-control" id="name" name="name" placeholder="Foo Bar" required="required" value="<?php echo htmlspecialchars($sitedata['sitename']); ?>" />
                    </div>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><label for="url"><i class="fas fa-globe"></i> <?php $Strings->get("url"); ?></label></span>
                        </div>
                        <input type="text" class="form-control" id="url" name="url" placeholder="https://example.com" required="required" value="<?php echo htmlspecialchars($sitedata['url']); ?>" />
                    </div>
                </div>
            </div>
            <div class="card mb-4">
                <div class="card-body">
                    <div class="form-group">
                        <h5 class="card-title"><label for="theme"><i class="fas fa-paint-brush"></i> <?php $Strings->get('theme'); ?></label></h5>
                        <div class="theme_bin_overflow">
                            <div class="theme_bin card-columns">
                                <?php
                                $themedir = __DIR__ . "/../public/themes/";
                                $themes = array_diff(scandir($themedir), array('..', '.'));
                                foreach ($themes as $t) {
                                    if (!file_exists($themedir . "$t/theme.json")) {
                                        continue;
                                    }
                                    $info = json_decode(file_get_contents($themedir . "$t/theme.json"), TRUE);
                                    $ts = $sitedata["theme"] == $t ? " checked" : "";
                                    $preview = (file_exists($themedir . "$t/preview.png") === true);
                                    ?>
                                    <label>
                                        <input type="radio" name="theme" value="<?php echo $t; ?>" <?php echo $ts; ?> />
                                        <div class="card theme">
                                            <?php
                                            if ($preview) {
                                                ?>
                                                <img class="card-img-top" src="public/themes/<?php echo $t; ?>/preview.png" alt="" />
                                                <?php
                                            }
                                            ?>
                                            <div class="card-body m-0 p-1">
                                                <span class="d-flex">
                                                    <h4 class="mr-auto"><?php echo $info['name']; ?></h4>
                                                    <a href="public/index.php?page=index&siteid=<?php echo $siteid; ?>&theme=<?php echo $t; ?>" target="_BLANK">
                                                        <i class="fas fa-eye"></i> <?php $Strings->get("preview"); ?>
                                                    </a>
                                                </span>
                                                <b><?php $Strings->get("theme type"); ?></b>:
                                                <?php
                                                if ($info['singlepage'] == true) {
                                                    $Strings->get("single page");
                                                } else {
                                                    $Strings->get("multiple page");
                                                }
                                                ?><br />
                                                <b><?php $Strings->get("templates"); ?></b>:
                                                <?php
                                                $temtitles = [];
                                                foreach ($info['templates'] as $tem) {
                                                    $temtitles[] = $tem['title'];
                                                }
                                                echo implode(", ", $temtitles);
                                                ?><br />
                                                <b><?php $Strings->get("color styles"); ?></b>:
                                                <div class="list-group colorSelector">
                                                    <?php
                                                    if (count($info['colors']) == 0) {
                                                        $info['colors'] = ["default" => ["title" => $Strings->get("default", false), "description" => ""]];
                                                    }
                                                    foreach ($info['colors'] as $c => $color) {
                                                        $checked = "";
                                                        if ($sitedata["theme"] == $t && $sitedata["color"] == $c) {
                                                            $checked = "checked";
                                                        }
                                                        ?>
                                                        <input type="radio" name="color" id="color_<?php echo $t; ?>_<?php echo $c; ?>" value="<?php echo $c; ?>" data-theme="<?php echo $t; ?>" <?php echo $checked; ?>>
                                                        <label class="list-group-item" for="color_<?php echo $t; ?>_<?php echo $c; ?>">
                                                            <div class="d-flex justify-content-between">
                                                                <b><?php echo $color["title"]; ?></b>
                                                                <div class="text-nowrap">
                                                                    <a href="public/index.php?page=index&siteid=<?php echo $siteid; ?>&theme=<?php echo $t; ?>&color=<?php echo $c; ?>" target="_BLANK">
                                                                        <i class="fas fa-eye"></i> <?php $Strings->get("preview"); ?>
                                                                    </a>
                                                                </div>
                                                            </div>
                                                            <?php echo $color["description"]; ?>
                                                        </label>
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

            <div class="row">
                <div class="col-12 col-md-6">
                    <!-- Company/Org Info -->
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title"><i class="fas fa-briefcase"></i> <?php $Strings->get("company info"); ?></h5>

                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><label for="businessname"><i class="fas fa-font"></i> <?php $Strings->get("name"); ?></label></span>
                                </div>
                                <input type="text" class="form-control" name="settings[businessname]" id="businessname" value="<?php echo getsetting("businessname"); ?>" />
                            </div>

                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><label for="phone"><i class="fas fa-phone"></i> <?php $Strings->get("phone"); ?></label></span>
                                </div>
                                <input type="text" class="form-control" name="settings[phone]" id="phone" value="<?php echo getsetting("phone"); ?>" />
                            </div>

                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><label for="address"><i class="fas fa-map-marker"></i> <?php $Strings->get("address"); ?></label></span>
                                </div>
                                <textarea class="form-control" name="settings[address]" id="address" rows="2"><?php echo getsetting("address"); ?></textarea>
                            </div>

                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><label for="email"><i class="fas fa-envelope"></i> <?php $Strings->get("email"); ?></label></span>
                                </div>
                                <input type="email" class="form-control" name="settings[email]" id="email" value="<?php echo getsetting("email"); ?>" />
                            </div>
                        </div>
                    </div>

                    <!-- Analytics -->
                    <div class="card mt-4">
                        <div class="card-body">
                            <h5 class="card-title"><i class="fas fa-chart-bar"></i> <?php $Strings->get("analytics"); ?></h5>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="settings[analytics]" value="" id="analytics_on" <?php echo ((isset($settings["analytics"]) && $settings["analytics"] === "off") ? "" : "checked") ?>>
                                <label class="form-check-label" for="analytics_on">
                                    <?php $Strings->get("enable built-in analytics"); ?>
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="settings[analytics]" value="off" id="analytics_off" <?php echo ((isset($settings["analytics"]) && $settings["analytics"] === "off") ? "checked" : "") ?>>
                                <label class="form-check-label" for="analytics_off">
                                    <?php $Strings->get("disable built-in analytics"); ?>
                                </label>
                            </div>
                        </div>
                    </div>

                    <!-- Contact Form -->
                    <div class="card mt-4">
                        <div class="card-body">
                            <h5 class="card-title"><i class="fas fa-comments"></i> <?php $Strings->get("contact form"); ?></h5>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><label for="contactemail"><i class="fas fa-envelope"></i> Forward to:</label></span>
                                </div>
                                <input type="email" class="form-control" name="settings[contactemail]" id="contactemail" value="<?php echo getsetting("contactemail"); ?>" />
                            </div>
                            <small class="form-text"><?php $Strings->get("contact form messages will be forwarded to this email address"); ?></small>
                        </div>
                    </div>

                    <!-- Extra code header snippets -->
                    <div class="card mt-4 mb-4">
                        <div class="card-body">
                            <h5 class="card-title"><label for="extracode"><i class="fas fa-code"></i> <?php $Strings->get("extra code"); ?></label></h5>
                            <textarea class="form-control" name="settings[extracode]" id="extracode" placeholder="<script></script>" rows="5"><?php echo (isset($settings["extracode"]) ? $settings["extracode"] : ""); ?></textarea>
                        </div>
                    </div>
                </div>

                <!-- Social Media links -->
                <div class="col-12 col-md-6">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title"><i class="fas fa-share-square"></i> <?php $Strings->get("social links"); ?></h5>

                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><label for="facebook"><i class="fab fa-facebook"></i> Facebook</label></span>
                                </div>
                                <input type="text" class="form-control" name="settings[facebook]" id="facebook" value="<?php echo getsetting("facebook"); ?>" />
                            </div>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><label for="twitter"><i class="fab fa-twitter"></i> Twitter</label></span>
                                </div>
                                <input type="text" class="form-control" name="settings[twitter]" id="twitter" value="<?php echo getsetting("twitter"); ?>" />
                            </div>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><label for="reddit"><i class="fab fa-reddit"></i> Reddit</label></span>
                                </div>
                                <input type="text" class="form-control" name="settings[reddit]" id="reddit" value="<?php echo getsetting("reddit"); ?>" />
                            </div>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><label for="youtube"><i class="fab fa-youtube"></i> Youtube</label></span>
                                </div>
                                <input type="text" class="form-control" name="settings[youtube]" id="youtube" value="<?php echo getsetting("youtube"); ?>" />
                            </div>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><label for="instagram"><i class="fab fa-instagram"></i> Instagram</label></span>
                                </div>
                                <input type="text" class="form-control" name="settings[instagram]" id="instagram" value="<?php echo getsetting("instagram"); ?>" />
                            </div>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><label for="snapchat"><i class="fab fa-snapchat"></i> Snapchat</label></span>
                                </div>
                                <input type="text" class="form-control" name="settings[snapchat]" id="snapchat" value="<?php echo getsetting("snapchat"); ?>" />
                            </div>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><label for="google-plus"><i class="fab fa-google-plus"></i> Google+</label></span>
                                </div>
                                <input type="text" class="form-control" name="settings[google-plus]" id="google-plus" value="<?php echo getsetting("google-plus"); ?>" />
                            </div>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><label for="skype"><i class="fab fa-skype"></i> Skype</label></span>
                                </div>
                                <input type="text" class="form-control" name="settings[skype]" id="skype" value="<?php echo getsetting("skype"); ?>" />
                            </div>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><label for="telegram"><i class="fab fa-telegram"></i> Telegram</label></span>
                                </div>
                                <input type="text" class="form-control" name="settings[telegram]" id="telegram" value="<?php echo getsetting("telegram"); ?>" />
                            </div>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><label for="vimeo"><i class="fab fa-vimeo"></i> Vimeo</label></span>
                                </div>
                                <input type="text" class="form-control" name="settings[vimeo]" id="vimeo" value="<?php echo getsetting("vimeo"); ?>" />
                            </div>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><label for="whatsapp"><i class="fab fa-whatsapp"></i> Whatsapp</label></span>
                                </div>
                                <input type="text" class="form-control" name="settings[whatsapp]" id="whatsapp" value="<?php echo getsetting("whatsapp"); ?>" />
                            </div>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><label for="linkedin"><i class="fab fa-linkedin"></i> LinkedIn</label></span>
                                </div>
                                <input type="text" class="form-control" name="settings[linkedin]" id="linkedin" value="<?php echo getsetting("linkedin"); ?>" />
                            </div>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><label for="diaspora"><i class="fas fa-asterisk"></i> diaspora*</label></span>
                                </div>
                                <input type="text" class="form-control" name="settings[diaspora]" id="diaspora" value="<?php echo getsetting("diaspora"); ?>" />
                            </div>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><label for="mastodon"><i class="fab fa-mastodon"></i> Mastodon</label></span>
                                </div>
                                <input type="text" class="form-control" name="settings[mastodon]" id="mastodon" value="<?php echo getsetting("mastodon"); ?>" />
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-12">
                    <div class="card mt-4">
                        <div class="card-body">
                            <h5 class="card-title"><label><i class="fas fa-list"></i> <?php $Strings->get("site footer links"); ?></label></h5>
                            <div id="footer-link-bin">
                                <?php
                                $footerset = false;
                                if (isset($settings['footerlinks'])) {
                                    $footerlinks = json_decode($settings['footerlinks'], true);
                                } else {
                                    $footerlinks = null;
                                }
                                if (is_array($footerlinks)) {
                                    $footerset = true;
                                }
                                for ($i = 1; $i <= 10; $i++) {
                                    $url = "";
                                    $title = "";
                                    if ($footerset && array_key_exists("$i", $footerlinks)) {
                                        $url = $footerlinks[$i]['link'];
                                        $title = $footerlinks[$i]['title'];
                                    }
                                    ?>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><?php $Strings->get("title"); ?>:</span>
                                        </div>
                                        <input type="text" class="form-control" name="settings[footerlinks][<?php echo $i; ?>][title]" value="<?php echo $title; ?>" />
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><?php $Strings->get("link"); ?>:</span>
                                        </div>
                                        <input type="text" class="form-control" name="settings[footerlinks][<?php echo $i; ?>][link]" value="<?php echo $url; ?>" />
                                    </div>
                                    <?php
                                }
                                ?>
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
            <button type="submit" class="btn btn-success mr-auto"><i class="fas fa-save"></i> <?php $Strings->get("save"); ?></button>
        </div>
    </div>
</form>