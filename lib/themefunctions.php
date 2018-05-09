<?php

/*
 * This Source Code Form is subject to the terms of the Mozilla Public
 * License, v. 2.0. If a copy of the MPL was not distributed with this
 * file, You can obtain one at http://mozilla.org/MPL/2.0/.
 */

require_once __DIR__ . "/requiredpublic.php";

/**
 * Get the name of the website.
 * @param boolean $echo default true
 * @return string
 */
function get_site_name($echo = true) {
    $db = getdatabase();
    $title = $db->get('sites', "sitename", ["siteid" => getsiteid()]);
    if ($echo) {
        echo $title;
    } else {
        return $title;
    }
}

/**
 * Get the URL of the website.
 * @param boolean $echo default true
 * @return string
 */
function get_site_url($echo = true) {
    $db = getdatabase();
    $url = formatsiteurl($db->get('sites', "url", ["siteid" => getsiteid()]));
    if ($echo) {
        echo $url;
    } else {
        return $url;
    }
}

/**
 * Get the page title.
 * @param boolean $echo default true
 * @return string
 */
function get_page_title($echo = true) {
    $db = getdatabase();
    $title = $db->get("pages", "title", ["AND" => ["slug" => getpageslug(), "siteid" => getsiteid()]]);
    if ($echo) {
        echo $title;
    } else {
        return $title;
    }
}

/**
 * Get the page title stripped of any HTML.
 * @param boolean $echo default true
 * @return string
 */
function get_page_clean_title($echo = true) {
    $title = strip_tags(get_page_title(false));
    if ($echo) {
        echo $title;
    } else {
        return $title;
    }
}

/**
 * Get the page slug for the current page.
 * @param boolean $echo default true
 * @return string
 */
function get_page_slug($echo = true) {
    if ($echo) {
        echo getpageslug();
    } else {
        return getpageslug();
    }
}

/**
 * Get a valid minimal URL for a page.
 * @param boolean $echo default true
 * @param string $slug page slug, or null for current
 * @return string
 */
function get_page_clean_url($echo = true, $slug = null) {
    if ($slug == null) {
        $slug = get_page_slug(false);
    }
    if (PRETTY_URLS) {
        $url = formatsiteurl(get_site_url(false)) . "$slug";
    } else {
        $url = formatsiteurl(get_site_url(false)) . "index.php?id=$slug";
    }
    if ($echo) {
        echo $url;
    } else {
        return $url;
    }
}

/**
 * Get a valid URL for a page.
 * @param boolean $echo default true
 * @param string $slug page slug, or null for current
 * @return string
 */
function get_page_url($echo = true, $slug = null) {
    if ($slug == null) {
        $slug = get_page_slug(false);
    }
    $edit = "";
    if (isset($_GET['edit'])) {
        $edit = "&edit";
    }
    $theme = "";
    if (isset($_GET['theme'])) {
        $theme = "&theme=" . preg_replace("/[^A-Za-z0-9]/", '', $_GET['theme']);
    }
    $template = "";
    if (isset($_GET['template'])) {
        $template = "&template=" . preg_replace("/[^A-Za-z0-9]/", '', $_GET['template']);
    }
    $color = "";
    if (isset($_GET['color'])) {
        $color = "&color=" . preg_replace("/[^A-Za-z0-9]/", '', $_GET['color']);
    }
    $siteid = "";
    if (isset($_GET['siteid'])) {
        $siteid = "&siteid=" . preg_replace("/[^0-9]/", '', $_GET['siteid']);
    }
    $args = "$edit$theme$template$color$siteid";
    if (PRETTY_URLS) {
        if ($args != "") {
            $args = "?$args";
        }
        $url = formatsiteurl(get_site_url(false)) . "$slug$args";
    } else {
        $url = formatsiteurl(get_site_url(false)) . "index.php?id=$slug$args";
    }
    if ($echo) {
        echo $url;
    } else {
        return $url;
    }
}

/**
 * Echoes or returns the content of a component.
 * @param string $name component name
 * @param string $context page slug, or null for current
 * @param boolean $echo default true
 * @param string $default The content to return if the component is empty
 * @return string
 */
function get_component($name, $context = null, $echo = true, $default = "") {
    $db = getdatabase();
    if ($context == null) {
        $context = get_page_slug(false);
    }
    $pageid = $db->get("pages", "pageid", ["AND" => ["slug" => $context, "siteid" => getsiteid()]]);
    $content = "";
    if ($db->has("components", ["AND" => ["pageid" => $pageid, "name" => $name]])) {
        $content = $db->get("components", "content", ["AND" => ["pageid" => $pageid, "name" => $name]]);
    }
    if ($content == "") {
        $content = $default;
    }
    if ($content == "" && isset($_GET['edit'])) {
        $content = "<br>";
    }
    if ($echo) {
        echo $content;
    } else {
        return $content;
    }
}

/**
 * Check if a component is empty of content.
 * @param string $name component name
 * @param string $context page slug, or null for current
 * @return boolean
 */
function is_component_empty($name, $context = null) {
    $comp = get_component($name, $context, false);
    $comp = strip_tags($comp, "<img><object><video><a>");
    if ($comp == "" && !isset($_GET['edit'])) {
        return true;
    }
    return false;
}

/**
 * Return the data for a complex component (icon, link, text, image, etc)
 * @param string $name component name
 * @param string $context page slug, or null for current
 * @param array $include list of properties to include in the output
 * @return array
 */
function get_complex_component($name, $context = null, $include = []) {
    $db = getdatabase();
    if ($context == null) {
        $context = get_page_slug(false);
    }
    $pageid = $db->get("pages", "pageid", ["AND" => ["slug" => $context, "siteid" => getsiteid()]]);
    $content = ["icon" => "", "link" => "", "text" => ""];
    if ($db->has("complex_components", ["AND" => ["pageid" => $pageid, "name" => $name]])) {
        $content = json_decode($db->get("complex_components", "content", ["AND" => ["pageid" => $pageid, "name" => $name]]), true);
    }

    if (count($include) == 0) {
        return $content;
    }

    $filtered = [];
    foreach ($include as $i) {
        if (array_key_exists($i, $content)) {
            $filtered[$i] = $content[$i];
        } else {
            $filtered[$i] = "";
        }
    }
    return $filtered;
}

/**
 * Check if the specified complex component is empty.
 * @param string $name
 * @param string $context page slug
 * @return boolean
 */
function is_complex_empty($name, $context = null) {
    if (isset($_GET['edit'])) {
        return false;
    }
    $comp = get_complex_component($name, $context);
    foreach ($comp as $c => $v) {
        if (isset($v) && !empty($v)) {
            return false;
        }
    }
    return true;
}

/**
 * Convert a variable into encoded JSON for safe inclusion in an element property.
 * @param $json Object or array to convert to JSON
 * @param boolean $echo default true
 * @return string
 */
function get_escaped_json($json, $echo = true) {
    $text = htmlspecialchars(json_encode($json), ENT_QUOTES, 'UTF-8');
    if ($echo) {
        echo $text;
    } else {
        return $text;
    }
}

/**
 * Detects if a string is a URL or a page slug, and returns something usable for href
 * @param string $str
 * @param boolean $echo
 * @return string
 */
function get_url_or_slug($str, $echo = true) {
    $url = $str;
    if ($str == "") {
        $url = "#";
    } else if (strpos($str, "http") !== 0) {
        $url = get_page_url(false, $str);
    }
    if ($echo) {
        echo $url;
    } else {
        return $url;
    }
}

/**
 * Get a valid URL for a given file path.
 * Detects if the file is uploaded via SiteWriter and acts accordingly.
 * @param string $file
 * @param boolean $echo
 * @return string
 */
function get_file_url($file, $echo = true) {
    $url = "file.php?file=$file";
    $base = FILE_UPLOAD_PATH;
    $filepath = $base . $file;
    if (!file_exists($filepath) || is_dir($filepath)) {
        $url = $file;
    } else {
        if (strpos(realpath($filepath), FILE_UPLOAD_PATH) !== 0) {
            $url = $file;
        }
    }

    if ($echo) {
        echo $url;
    } else {
        return $url;
    }
}

/**
 * Shortcut for get_component("content").
 * @param string $slug Get the content for the passed page instead of the current.
 */
function get_page_content($slug = null) {
    get_component("content", $slug);
}

/**
 * Echoes invisible page header content.
 */
function get_header() {
    $db = getdatabase();
    $siteid = getsiteid();
    if ($db->has('settings', ["AND" => ['siteid' => $siteid, 'key' => "extracode"]])) {
        echo $db->get('settings', "value", ["AND" => ['siteid' => $siteid, 'key' => "extracode"]]);
    }
}

/**
 * Echoes invisible page footer content.
 */
function get_footer() {
// placeholder stub
}

/**
 * Return or echo the value of the given site setting key, or an empty string if unset.
 * @param string $key
 * @param boolean $echo default false
 * @return string
 */
function get_setting($key, $echo = false) {
    $db = getdatabase();
    $siteid = getsiteid();
    $value = "";
    if ($db->has('settings', ["AND" => ['siteid' => $siteid, 'key' => $key]])) {
        $value = $db->get('settings', "value", ["AND" => ['siteid' => $siteid, 'key' => $key]]);
    }
    if ($echo) {
        echo $value;
    } else {
        return $value;
    }
}

/**
 * Get the URL path for the theme folder, without trailing slash.
 * @param boolean $echo default true
 * @return string
 */
function get_theme_url($echo = true) {
    $db = getdatabase();
    $site = $db->get('sites', ["sitename", "url", "theme"], ["siteid" => getsiteid()]);
    if (isset($_GET['edit']) || isset($_GET['in_sw'])) {
        $url = URL . "/public/themes/" . SITE_THEME;
    } else {
        $url = formatsiteurl($site["url"]) . "themes/" . SITE_THEME;
    }
    if ($echo) {
        echo $url;
    } else {
        return $url;
    }
}

/**
 * Get the URL base for the selected theme color asset folder, without trailing slash.
 * @param boolean $echo default true
 * @return string
 */
function get_theme_color_url($echo = true) {
    $db = getdatabase();
    $site = $db->get('sites', ["sitename", "url", "theme", "color"], ["siteid" => getsiteid()]);
    if ($site["color"] == null) {
        $site["color"] = "default";
    }
    if (isset($_GET['color'])) {
        $site['color'] = preg_replace("/[^A-Za-z0-9]/", '', $_GET['color']);
    }
    if (!file_exists(__DIR__ . "/../public/themes/" . SITE_THEME . "/colors/" . $site['color'])) {
        $site['color'] = "default";
    }
    $url = get_theme_url(false) . "/colors/" . $site["color"];
    if ($echo) {
        echo $url;
    } else {
        return $url;
    }
}

/**
 * Get the page navigation as a string containing a series of <li><a></a></li> elements.
 *
 * Format:
 *   Current page:
 *     <li class="$classPrefix$slug $liclass $currentclass">
 *       <a class="$linkclass $currentlinkclass" href="url">
 *         Link Text
 *       </a>
 *     </li>
 *   Other pages:
 *     <li class="$classPrefix$slug $liclass">
 *       <a class="$linkclass" href="url">
 *         Link Text
 *       </a>
 *     </li>
 * @param string $currentpage The page slug to use for context, or null for current.
 * @param string $classPrefix
 * @param string $liclass
 * @param string $currentclass default "current"
 * @param string $linkclass
 * @param string $currentlinkclass default "active"
 */
function get_navigation($currentpage = null, $classPrefix = "", $liclass = "", $currentclass = "current", $linkclass = "", $currentlinkclass = "active") {
    $db = getdatabase();
    $pages = $db->select('pages', ['pageid', 'parentid', 'slug', 'nav'], ["AND" => ["siteid" => getsiteid(), "nav[!]" => null], "ORDER" => ["navorder" => "ASC"]]);
    if (is_null($currentpage)) {
        $current = getpageslug();
    } else {
        $current = $currentpage;
    }
    foreach ($pages as $p) {
        $class = $classPrefix . $p['slug'] . " $liclass";
        $aclass = $linkclass;
        if ($p['slug'] == $current) {
            $class .= " $currentclass";
            $aclass .= " $currentlinkclass";
        }
        echo '<li class="' . $class . '">'
        . '<a class="' . $aclass . '" href="' . get_page_url(false, $p['slug']) . '">'
        . $p['nav']
        . '</a>'
        . '</li>' . "\n";
    }
}

/**
 * Replace "[[VAR]]" with the contents of $var and echo $content,
 * but only if $var isn't empty.
 * @param string $content
 * @param string $var
 */
function output_conditional($content, $var) {
    if ($var == "" && !isset($_GET['edit'])) {
        return;
    }
    echo str_replace("[[VAR]]", $var, $content);
}

/**
 * Echos or returns a URL for the FontAwesome 5 JavaScript.
 * @param boolean $echo default true
 * @return string
 */
function get_fontawesome_js($echo = true) {
    $url = "assets/fontawesome-all.min.js";
    if ($echo) {
        echo $url;
    } else {
        return $url;
    }
}

/**
 * Echos or returns a URL for the FontAwesome 5 CSS WebFont.
 * @param boolean $echo default true
 * @return string
 */
function get_fontawesome_css($echo = true) {
    $url = "assets/css/fontawesome-all.min.css";
    if ($echo) {
        echo $url;
    } else {
        return $url;
    }
}

/**
 * Returns an array of social media URLs, with FontAwesome icon classes and labels.
 * @return array [["icon", "name", "url"]]
 */
function get_socialmedia_urls() {
    $socials = [
        [
            "icon" => "fab fa-facebook-f",
            "name" => "Facebook",
            "setting" => "facebook"
        ],
        [
            "icon" => "fab fa-twitter",
            "name" => "Twitter",
            "setting" => "twitter"
        ],
        [
            "icon" => "fab fa-youtube",
            "name" => "YouTube",
            "setting" => "youtube"
        ],
        [
            "icon" => "fab fa-instagram",
            "name" => "Instagram",
            "setting" => "instagram"
        ],
        [
            "icon" => "fab fa-snapchat",
            "name" => "Snapchat",
            "setting" => "snapchat"
        ],
        [
            "icon" => "fab fa-google-plus-g",
            "name" => "Google+",
            "setting" => "google-plus"
        ],
        [
            "icon" => "fab fa-skype",
            "name" => "Skype",
            "setting" => "skype"
        ],
        [
            "icon" => "fab fa-telegram",
            "name" => "Twitter",
            "setting" => "telegram"
        ],
        [
            "icon" => "fab fa-vimeo",
            "name" => "Vimeo",
            "setting" => "vimeo"
        ],
        [
            "icon" => "fab fa-whatsapp",
            "name" => "Whatsapp",
            "setting" => "whatsapp"
        ],
        [
            "icon" => "fab fa-linkedin",
            "name" => "LinkedIn",
            "setting" => "linkedin"
        ],
        [
            "icon" => "fas fa-asterisk",
            "name" => "diaspora*",
            "setting" => "diaspora"
        ],
        [
            "icon" => "fab fa-mastodon",
            "name" => "Mastodon",
            "setting" => "mastodon"
        ],
    ];
    $urls = [];
    foreach ($socials as $s) {
        $url = get_setting($s["setting"]);
        if ($url != "") {
            $urls[] = [
                "name" => $s["name"],
                "icon" => $s["icon"],
                "url" => $url
            ];
        }
    }
    return $urls;
}
