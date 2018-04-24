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
    $url = $db->get('sites', "url", ["siteid" => getsiteid()]);
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
    $siteid = "";
    if (isset($_GET['siteid'])) {
        $siteid = "&siteid=" . preg_replace("/[^0-9]/", '', $_GET['siteid']);
    }
    $url = get_site_url(false) . "index.php?id=$slug$edit$theme$template$siteid";
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
        $content = "Click here to edit me";
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

function get_icon($name, $context = null, $echo = true) {
    $db = getdatabase();
    if ($context == null) {
        $context = get_page_slug(false);
    }
    $pageid = $db->get("pages", "pageid", ["AND" => ["slug" => $context, "siteid" => getsiteid()]]);
    $content = "";
    if ($db->has("icons", ["AND" => ["pageid" => $pageid, "name" => $name]])) {
        $content = $db->get("icons", "content", ["AND" => ["pageid" => $pageid, "name" => $name]]);
    }
    if ($echo) {
        echo $content;
    } else {
        return $content;
    }
}

function get_complex_component($name, $context = null) {
    $db = getdatabase();
    if ($context == null) {
        $context = get_page_slug(false);
    }
    $pageid = $db->get("pages", "pageid", ["AND" => ["slug" => $context, "siteid" => getsiteid()]]);
    $content = ["icon" => "", "link" => "", "text" => ""];
    if ($db->has("complex_components", ["AND" => ["pageid" => $pageid, "name" => $name]])) {
        $content = json_decode($db->get("complex_components", "content", ["AND" => ["pageid" => $pageid, "name" => $name]]), true);
    }
    return $content;
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

}

function get_theme_url($echo = true) {
    $db = getdatabase();
    $site = $db->get('sites', ["sitename", "url", "theme"], ["siteid" => getsiteid()]);
    $url = $site["url"] . "themes/" . $site["theme"];
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
    if (!file_exists(__DIR__ . "/../public/themes/" . SITE_THEME . "/colors/" . $site['color'])) {
        $site['color'] = "default";
    }
    $url = $site["url"] . "themes/" . SITE_THEME . "/colors/" . $site["color"];
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

function return_site_ver() {
    // Stub for GetSimple
    return "SiteWriter";
}
