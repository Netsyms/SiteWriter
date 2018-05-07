<?php

/*
 * This Source Code Form is subject to the terms of the Mozilla Public
 * License, v. 2.0. If a copy of the MPL was not distributed with this
 * file, You can obtain one at http://mozilla.org/MPL/2.0/.
 */

require_once __DIR__ . "/requiredpublic.php";

function get_site_name($echo = true) {
    $db = getdatabase();
    $title = $db->get('sites', "sitename", ["siteid" => getsiteid()]);
    if ($echo) {
        echo $title;
    } else {
        return $title;
    }
}

function get_site_url($echo = true) {
    $db = getdatabase();
    $url = formatsiteurl($db->get('sites', "url", ["siteid" => getsiteid()]));
    if ($echo) {
        echo $url;
    } else {
        return $url;
    }
}

function get_page_title($echo = true) {
    $db = getdatabase();
    $title = $db->get("pages", "title", ["AND" => ["slug" => getpageslug(), "siteid" => getsiteid()]]);
    if ($echo) {
        echo $title;
    } else {
        return $title;
    }
}

function get_page_clean_title($echo = true) {
    $title = strip_tags(get_page_title(false));
    if ($echo) {
        echo $title;
    } else {
        return $title;
    }
}

function get_page_slug($echo = true) {
    if ($echo) {
        echo getpageslug();
    } else {
        return getpageslug();
    }
}

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

function get_component($name, $context = null, $echo = true) {
    $db = getdatabase();
    if ($context == null) {
        $context = get_page_slug(false);
    }
    $pageid = $db->get("pages", "pageid", ["AND" => ["slug" => $context, "siteid" => getsiteid()]]);
    $content = "";
    if (isset($_GET['edit'])) {
        $content = "<br>";
    }
    if ($db->has("components", ["AND" => ["pageid" => $pageid, "name" => $name]])) {
        $content = $db->get("components", "content", ["AND" => ["pageid" => $pageid, "name" => $name]]);
    }
    if ($echo) {
        echo $content;
    } else {
        return $content;
    }
}

function is_component_empty($name, $context = null) {
    $comp = get_component($name, $context, false);
    $comp = strip_tags($comp, "<img><object><video><a>");
    if ($comp == "" && !isset($_GET['edit'])) {
        return true;
    }
    return false;
}

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

function is_complex_empty($name, $context = null) {
    if (isset($_GET['edit'])) {
        return false;
    }
    $comp = get_complex_component($name, $context, false);
    foreach ($comp as $c => $v) {
        if (isset($v) && !empty($v)) {
            return false;
        }
    }
    return true;
}

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

function get_page_content($slug = null) {
    get_component("content", $slug);
}

function get_header() {
    $db = getdatabase();
    $siteid = getsiteid();
    if ($db->has('settings', ["AND" => ['siteid' => $siteid, 'key' => "extracode"]])) {
        echo $db->get('settings', "value", ["AND" => ['siteid' => $siteid, 'key' => "extracode"]]);
    }
}

function get_footer() {
    // placeholder stub
}

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
    if ($var == "") {
        return;
    }
    echo str_replace("[[VAR]]", $var, $content);
}

function return_site_ver() {
    // Stub for GetSimple
    return "SiteWriter";
}
