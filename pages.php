<?php

/* This Source Code Form is subject to the terms of the Mozilla Public
 * License, v. 2.0. If a copy of the MPL was not distributed with this
 * file, You can obtain one at http://mozilla.org/MPL/2.0/. */

// List of pages and metadata
define("PAGES", [
    "home" => [
        "title" => "home",
        "navbar" => true,
        "icon" => "fas fa-home"
    ],
    "sites" => [
        "title" => "sites",
        "navbar" => true,
        "icon" => "fas fa-sitemap"
    ],
    "sitesettings" => [
        "title" => "settings",
        "styles" => [
            "static/css/themeselector.css"
        ],
        "scripts" => [
            "static/js/sitesettings.js"
        ]
    ],
    "editor" => [
        "title" => "editor",
        "styles" => [
            "static/css/editorparent.css"
        ],
        "scripts" => [
            "static/js/editorparent.js"
        ]
    ],
    "404" => [
        "title" => "404 error"
    ]
]);
