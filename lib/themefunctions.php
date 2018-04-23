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
    $url = get_site_url(false) . "index.php?id=$slug$edit";
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
    $content = "Edit me";
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
    $content = null;
    if ($db->has("complex_components", ["AND" => ["pageid" => $pageid, "name" => $name]])) {
        $content = json_decode($db->get("complex_components", "content", ["AND" => ["pageid" => $pageid, "name" => $name]]), true);
    }
    return $content;
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
    if (!file_exists(__DIR__ . "/../public/themes/" . $site["theme"] . "/colors/" . $site['color'])) {
        $site['color'] = "default";
    }
    $url = $site["url"] . "themes/" . $site["theme"] . "/colors/" . $site["color"];
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
